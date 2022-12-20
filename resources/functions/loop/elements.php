<?php

//
// CONTENT LOOP
// this is the main recursive loop that cycles through the 'elements' flex field
// and sets up / outputs the elements
//

function content_loop ( $loop_key = null, $loop_ID = null, $resolve = true ) {

  // default values for the have_rows functions

  if ( !isset ( $loop_key ) ) $loop_key = 'elements';
  if ( !isset ( $loop_ID ) ) $loop_ID = get_the_ID();

  $GLOBALS['vars']['loop_key'] = $loop_key;
  $GLOBALS['vars']['loop_ID'] = $loop_ID;

	// reset the section counter

	// $GLOBALS['elements']['counters']['section'] = 1;

  // start cycling through the 'elements' flex field
  // and setup each element

  if ( have_rows ( $loop_key, $loop_ID ) ) {

    $GLOBALS['vars']['loop_key'] = $loop_key;
    $GLOBALS['vars']['loop_ID'] = $loop_ID;

    while ( have_rows ( $loop_key, $loop_ID ) ) {
      the_row();

      //
      // LAYOUT TYPES
      // i.e. section, container, column, block
      //

      $this_layout = get_row_layout();

      if ( $this_layout == 'include' ) {

        // FILE INCLUDE

        // make sure the file exists

        if ( locate_template ( 'template/' . get_sub_field ( 'filename' ) . '.php' ) != '' ) {

          // close the previous element,
          // dump the contents of the provided file name and move on

					if (
						isset ( $GLOBALS['elements']['current'] ) &&
						get_sub_field ( 'resolve' ) == 1
					) {
						close_element ( $GLOBALS['elements']['current']['type'] );
					}

          include ( locate_template ( 'template/' . get_sub_field ( 'filename' ) . '.php' ) );

        }

      } elseif ( $this_layout == 'template' ) {

        // TEMPLATE POST OBJECT

        // get the post ID selected in the template sub-field
        $template_ID = get_sub_field ( 'template' );
        
        if ( $template_ID != '' ) {

          $GLOBALS['vars']['loop_ID'] = $template_ID;
  
          // if the field layout is a template,
          // run content_loop recursively for the given template ID
          // if THAT template includes ANOTHER template,
          // the function will run again (and again and again...)
  
          $template_resolve = false;
          
          if ( get_sub_field ( 'resolve' ) == 1 ) {
            $template_resolve = true;
          }
          
          content_loop ( 'elements', $template_ID, $template_resolve );
          
        } else {
          
          if ( current_user_can ( 'administrator' ) ) {
            echo '<p class="alert alert-primary">';
            echo 'Error: Empty template field in post ID <code>' . $loop_ID . '</code>';
            echo '</p>';
          }
          
        }

      } else {

        $GLOBALS['vars']['loop_key'] = $loop_key;
        $GLOBALS['vars']['loop_ID'] = $loop_ID;

        // ANYTHING ELSE
        // i.e. a layout element with sub-fields, settings, etc.

				if ( strpos ( $this_layout, '_' ) !== false ) {

					$layout_array = array (
						'type' => explode ( '_', $this_layout )[0],
						'subtype' => explode ( '_', $this_layout )[1]
					);

				} else {

					$layout_array = array (
						'type' => $this_layout,
						'subtype' => ''
					);

				}

        // resolve surrounding elements
        resolve_elements ( $layout_array, $loop_ID );

        // open this element
        open_element ( $layout_array, 'field', $loop_ID );

      }

    }
  }

  if ( $resolve == true ) {

    foreach ( array_reverse ( $GLOBALS['elements']['types'] ) as $type => $data ) {

      if ( $data['is_open'] == true ) {
        close_element ( $type );
      }

    }

  }

}


//
// SET UP ELEMENT
// create an $element array from the given sub-field type
//

function setup_element ( $layout, $generator, $acf_ID ) {

  // index of the given layout type in the master array

  $index = array_search ( $layout['type'], array_keys ( $GLOBALS['elements']['types'] ) );

  //
  // CURRENT ELEMENT ARRAY
  // stores all the data and settings about the element
  //

  $GLOBALS['elements']['current'] = array(
    'type' => $layout['type'],
		'subtype' => $layout['subtype'],
    'bg' => array(),
    'classes' => array(),
    'settings' => array(),
		'generator' => $generator
  );
	
	if ( $generator == 'field' ) {
		$GLOBALS['elements']['current']['row_index'] = (int) get_row_index() - 1;
	}

  //
  // BLOCK ADJUSTMENTS
  // if it's a block then there are a bunch of other things to check for
  // before getting settings etc.
  //

	if ( isset ( $layout['subtype'] ) && $layout['subtype'] != '' ) {

    $content_field = get_sub_field ( 'blocks' );

    $GLOBALS['elements']['current']['block'] = array (
      'type' => $layout['type'],
			'subtype' => $layout['subtype'],
      'content' => $content_field[0]['acf_fc_layout']
    );

    $GLOBALS['elements']['current']['classes'][] = 'block-type-' . $GLOBALS['elements']['current']['block']['content'];

    if ( $GLOBALS['elements']['current']['block']['content'] == 'jumbotron' ) {
      $GLOBALS['elements']['current']['classes'][] = 'jumbotron jumbotron-fluid';
    }

		if ( $GLOBALS['elements']['current']['block']['content'] == 'card' ) {
      $GLOBALS['elements']['current']['classes'][] = 'card';
    }

  }

  //
  // ATTRIBUTES
  //

  // id

  $the_ID = '';

  if ( get_sub_field ( $layout['type'] . '_id' ) != '' ) {

    // this element has a custom ID

    $the_ID = strtolower ( str_replace ( ' ', '-', get_sub_field ( $layout['type'] . '_id' ) ) );

    // set the 'current_id' key of this element type to the custom value
    // this is what will let future elements pick up the parent's custom ID
    // in their own ID strings

    $GLOBALS['elements']['types'][$layout['type']]['current_id'] = $the_ID;

  } else {

    // loop through the element types and start building an ID string.
    // if we hit a custom one, replace everything stored so far with the ID

    $break_next = false;
    $i = 0;

		if ( $acf_ID != get_the_ID() ) {
			$the_ID = 'template-' . $acf_ID . '-';
		}

    foreach ( $GLOBALS['elements']['types'] as $parent_type => $data ) {

      if ( $break_next ) break;

      if ( $data['current_id'] != '' ) {

        $the_ID = $data['current_id'];

      } else {

        if ( $i != 0 ) $the_ID .= '-';
        $the_ID .= $parent_type . '-' . $GLOBALS['elements']['counters'][$parent_type];

      }

      if ( $parent_type == $layout['type'] ) $break_next = true;

      $i++;

    }

  }

  $GLOBALS['elements']['current']['id'] = $the_ID;

  // classes

  $GLOBALS['elements']['current']['classes'] = array_merge ( $GLOBALS['elements']['current']['classes'], $GLOBALS['elements']['types'][$layout['type']]['classes'] );

  if ( $generator == 'auto' ) {
		
    $GLOBALS['elements']['current']['classes'][] = 'auto-generated';
		
  } elseif ( get_sub_field ( 'classes' ) != '' ) {
		
    $GLOBALS['elements']['current']['classes'][] = get_sub_field ( 'classes' );
		
  }

	// first of type

  if ( $GLOBALS['elements']['counters'][$layout['type']] == 1 ) {
    $GLOBALS['elements']['current']['classes'][] = 'first-of-type';
  }

  // if there's currently an open carousel

  if (
    isset ( $GLOBALS['vars']['current_carousel'] ) &&
    $GLOBALS['vars']['current_carousel'] != ''
  ) {
    
    $carousel_el = $GLOBALS['vars']['current_carousel'];
    $carousel_classes = $GLOBALS['elements']['types'][$carousel_el]['carousel']->classes;

    // if this element is one level below the carousel

    $is_carousel = false;

    // go through all the element types

    foreach ( $GLOBALS['elements']['types'] as $element_type => $element_data ) {

      // if is_carousel was set to true on the previous level,
      // the current element is a swiper slide

      if ( $is_carousel == true && $element_type == $layout['type'] ) {
        
        $GLOBALS['elements']['current']['classes'][] = 'swiper-slide';
        
        $GLOBALS['elements']['current']['classes'] = array_merge ( $GLOBALS['elements']['current']['classes'], $carousel_classes['content'] );
      
      }

      if ( $element_type == $GLOBALS['vars']['current_carousel'] ) {

	      // the current element is the carousel element,
				// so set the flag for next time

        $is_carousel = true;
        
        $GLOBALS['elements']['current']['classes'] = array_merge ( $GLOBALS['elements']['current']['classes'], $carousel_classes['container'] );

      } else {

				$is_carousel = false;

			}

    }

  }

  // if there's currently an open tab

  if (
    isset ( $GLOBALS['vars']['current_tabs'] ) &&
    $GLOBALS['vars']['current_tabs'] != ''
  ) {

    if ( $layout['type'] == $GLOBALS['elements']['types'][$GLOBALS['vars']['current_tabs']]['tabs']['panel'] ) {
        $GLOBALS['elements']['current']['classes'][] = 'tab-panel';
    }

  }

  //
  // SETTINGS
  //

  // DEFAULTS

  // spacing

  $GLOBALS['elements']['current']['settings'] = $GLOBALS['defaults'][$layout['type']];

  //
  // ELEMENT DEFAULTS
  // if the element has defaults, merge them with the settings that were pulled from the element field
  //

  if ( is_array ( get_element_defaults ( $GLOBALS['elements']['current'] ) ) ) {

    $GLOBALS['elements']['current']['settings'] = array_merge ( $GLOBALS['defaults'][$layout['type']], get_element_defaults ( $GLOBALS['elements']['current'] ) );

  }
	
	$GLOBALS['elements']['current']['settings']['has_spacing'] = false;

  //
  // SETTINGS FIELDS
  // get settings that have been added manually
  // and override the $element['settings'] keys that have just been set
  //

  if (
    $generator != 'auto' && 
    have_rows ( 'settings', $acf_ID ) 
  ) {
    
    while ( have_rows ( 'settings', $acf_ID ) ) {
      the_row();
      
      $setting = get_row();

      switch ( get_row_layout() ) {

        case 'background' :

          $GLOBALS['elements']['current']['bg'] = array (
            'type' => get_sub_field ( 'type' ),
            'colour' => get_sub_field ( 'background' ),
            'image' => get_sub_field ( 'image' ),
            'video' => get_sub_field ( 'video' ),
            'opacity' => get_sub_field ( 'opacity' ),
            'position' => get_sub_field ( 'position' ),
            'attachment' => get_sub_field ( 'attachment' ),
            'size' => get_sub_field ( 'size' )
          );

          $GLOBALS['elements']['current']['classes'][] = 'has-bg';

          if (
            get_sub_field ( 'colour' ) != '' &&
            get_sub_field ( 'colour' ) != 'inherit'
          ) {
            $GLOBALS['elements']['current']['classes'][] = 'bg-' . get_sub_field ( 'colour' );

            // if there's a colour & opacity set, but no image or video
            // add the bg-opacity-x class

            if (
              get_sub_field ( 'opacity' ) != '' &&
              get_sub_field ( 'opacity' ) != 100 &&
              (
                ( get_sub_field ( 'type' ) == 'image' && get_sub_field ( 'image' ) == '' ) ||
								( get_sub_field ( 'type' ) == 'video' && get_sub_field ( 'video' ) == '' )
              )
            ) {

              $GLOBALS['elements']['current']['classes'][] = 'bg-opacity-' . get_sub_field ( 'opacity' );

            }

          }

					add_action ( 'after_element_open', 'output_bg', 10, 2 );

          break;

        case 'flexbox' :

          $GLOBALS['elements']['current']['classes'][] = 'd-flex';

          if ( get_sub_field ( 'direction' ) != '' ) {
            $GLOBALS['elements']['current']['classes'][] = 'flex-' . get_sub_field ( 'direction' );
          }

          if ( get_sub_field ( 'align' ) != '' ) {
            $GLOBALS['elements']['current']['classes'][] = 'align-items-' . get_sub_field ( 'align' );
          }

          if ( get_sub_field ( 'justify' ) != '' ) {
            $GLOBALS['elements']['current']['classes'][] = 'justify-content-' . get_sub_field ( 'justify' );
          }

          break;

        case 'alignment' :
          $GLOBALS['elements']['current']['classes'][] = 'text-' . get_sub_field ( 'alignment' );
          break;

        case 'colours' :

          $GLOBALS['css'] .= generate_css ( get_sub_field ( 'colours' ), '#' . $GLOBALS['elements']['current']['id'] );

          break;

        case 'spacing' :
					
					if ( have_rows ( 'spacing' ) ) {
						
						$GLOBALS['elements']['current']['settings']['has_spacing'] = true;
						
						while ( have_rows ( 'spacing' ) ) {
							the_row();
							
							$new_class = get_sub_field ( 'property' ) . get_sub_field ( 'sides' );
							$new_class .= ( get_sub_field ( 'breakpoint' ) != '' ) ? '-' . get_sub_field ( 'breakpoint' ) : '';
							$new_class .= '-' . get_sub_field ( 'value' );
							
							$GLOBALS['elements']['current']['classes'][] = $new_class;
							
						}
					}

          break;

        case 'sticky' :

          $GLOBALS['elements']['current']['classes'][] = 'sticky';

          if ( get_sub_field ( 'parent' ) != '' ) {
            $GLOBALS['elements']['current']['atts']['sticky-parent'] = get_sub_field ( 'parent' );
          } else {
            $GLOBALS['elements']['current']['atts']['sticky-parent'] = 'body';
          }

          $GLOBALS['elements']['current']['atts']['sticky-offset'] = get_sub_field ( 'offset' );

          $GLOBALS['elements']['current']['atts']['sticky-breakpoint'] = get_sub_field ( 'breakpoint' );

          break;

        case 'collapsible' :

          $GLOBALS['elements']['types'][$layout['type']]['collapsible'] = collapsible_setup();

					// add_action ( 'after_element_open', 'collapsible_output', 20, 2 );
					// add_action ( 'before_element_close', 'collapsible_output', 20, 2 );

          break;

        case 'carousel' :
        case 'swiper' :

					// add_filter ( 'element_setup_classes', function ( $classes ) { $classes[] = 'carousel-container'; return $classes; } );
          
          // echo '<H2>' . get_current_element_ID() . '</H2>';

          $GLOBALS['elements']['current']['classes'][] = 'carousel-container';

          $GLOBALS['elements']['types'][$layout['type']]['carousel'] = new Carousel ( get_current_element_ID() );
          
          // convert $setting to $swiper
          
          $swiper = array();
          
          foreach ( $setting as $key => $value ) {
            
            $key = str_replace( 'builder_setting_carousel_clone_builder_setting_carousel_', '', $key );
            
            if ( $key == 'classes' ) $key = 'carousel_classes';
            
            if ( is_array ( $value ) ) {
              
              $swiper[$key] = array();
              
              foreach ( $value as $sub_key => $sub_val ) {
                
                $sub_key = str_replace( 'builder_setting_carousel_' . $key . '_', '', $sub_key );
                
                $swiper[$key][$sub_key] = $sub_val;
                
              }
              
            } else {
              
              $swiper[$key] = $value;
              
            }
            
          }

					$GLOBALS['elements']['types'][$layout['type']]['carousel']->init ( $swiper );
          
          break;

        case 'tabs' :

          wp_enqueue_script ( 'jquery-ui-core' );
          wp_enqueue_script ( 'jquery-ui-widget' );
          wp_enqueue_script ( 'jquery-effects-core' );
          wp_enqueue_script ( 'jquery-ui-tabs' );

          $GLOBALS['elements']['current']['classes'][] = 'tab-container';
          $GLOBALS['elements']['current']['classes'][] = 'renderable';

          $GLOBALS['elements']['current']['atts']['renderable-type'] = 'tabs';

          $GLOBALS['elements']['types'][$layout['type']]['tabs'] = tabs_setup ();

					break;

				case 'header_style' :

					if ( get_sub_field ( 'header_style' ) != '' ) {
						$GLOBALS['elements']['current']['atts']['header-style'] = get_sub_field ( 'header_style' );
					}

					break;

				case 'aos' :

					wp_enqueue_style ( 'aos' );
					wp_enqueue_script ( 'aos' );

					if ( get_sub_field ( 'animation' ) != '' ) {
						$GLOBALS['elements']['current']['atts']['aos'] = get_sub_field ( 'animation' );
					}

					if ( get_sub_field ( 'easing' ) != '' ) {
						$GLOBALS['elements']['current']['atts']['aos-easing'] = get_sub_field ( 'easing' );
					}
					
					if ( get_sub_field ( 'delay' ) != '' ) {
						$GLOBALS['elements']['current']['atts']['aos-delay'] = get_sub_field ( 'delay' );
					}
					
					if ( get_sub_field ( 'offset' ) != '' ) {
						$GLOBALS['elements']['current']['atts']['aos-offset'] = get_sub_field ( 'offset' );
					}
					
					if ( get_sub_field ( 'once' ) == 1 ) {
						$GLOBALS['elements']['current']['atts']['aos-once'] = 'true';
					}

					break;


      }

    }
  }
	
  // spacing
	
	if (
		$GLOBALS['elements']['current']['settings']['has_spacing'] == false &&
		!empty ( $GLOBALS['elements']['current']['settings']['spacing'] )
	) {

		foreach ( $GLOBALS['elements']['current']['settings']['spacing'] as $prop ) {
		
			$new_class = $prop['property'] . $prop['sides'];
			$new_class .= ( $prop['breakpoint'] != '' ) ? '-' . $prop['breakpoint'] : '';
			$new_class .= '-' . $prop['value'];
			
			$GLOBALS['elements']['current']['classes'][] = $new_class;
			
		}
		
  }
	
  // colours

  if ( isset ( $GLOBALS['elements']['current']['settings']['colours'] ) ) {

    // $GLOBALS['css'] .= generate_css ( $GLOBALS['elements']['current']['settings']['colours'], '#' . $GLOBALS['elements']['current']['id'] );

  }

  //
  // ADJUSTMENTS FOR SPECIFIC TYPES
  //

  // SECTION

  if ( $GLOBALS['elements']['current']['type'] == 'section' ) {

		// if the loop ID is not equal to the current post ID,
		// this is probably a template

		if ( $acf_ID != get_the_ID() ) {
			$GLOBALS['elements']['current']['classes'][] = 'template-' . $acf_ID;
		}

		// add_action ( 'after_element_open', 'section_output', 20, 2 );
		// add_action ( 'before_element_close', 'section_output', 20, 2 );

  }

  // CONTAINER

  if ( $GLOBALS['elements']['current']['type'] == 'container' ) {

    // DEFAULTS

    $GLOBALS['elements']['current']['settings']['width'] = $GLOBALS['defaults']['container']['width'];
    $GLOBALS['elements']['current']['settings']['row_classes'] = $GLOBALS['defaults']['container']['width'];

    if ( get_sub_field ( 'width' ) != '' ) {
      $GLOBALS['elements']['current']['settings']['width'] = get_sub_field ( 'width' );
    }

    if ( $GLOBALS['elements']['current']['settings']['width'] != 'full' ) {
      $GLOBALS['elements']['current']['classes'][] = 'has-max-width';
    }

		// add_action ( 'after_element_open', 'container_output', 20, 2 );
		// add_action ( 'before_element_close', 'container_output', 20, 2 );

  }

  // COLUMN

  if ( $GLOBALS['elements']['current']['type'] == 'column' ) {

    $breakpoints = get_breakpoints ( get_sub_field ( 'breakpoints' ) );

    if ( !empty ( $breakpoints ) ) {
      $GLOBALS['elements']['current']['classes'] = array_merge ( $GLOBALS['elements']['current']['classes'], $breakpoints );
    } else {
      $GLOBALS['elements']['current']['classes'][] = 'col';
    }

  }

  // BLOCK

  if ( $GLOBALS['elements']['current']['type'] == 'block' ) {

    $block_content = get_sub_field ( 'blocks' );

    $block_type = $block_content[0]['acf_fc_layout'];

    // $GLOBALS['elements']['current']['classes'][] = 'block-type-' . $block_type;

		// add_action ( 'after_element_open', 'block_output', 20, 2 );
		// add_action ( 'before_element_close', 'block_output', 20, 2 );

  }

  // echo "\n\n" . '<!--' . "\n\n";
  // print_r($element);
  // echo "\n\n" . '-->' . "\n\n";

	//
	// FILTERS
	//

	$GLOBALS['elements']['current']['classes'] = apply_filters ( 'element_setup_classes', $GLOBALS['elements']['current']['classes'] );

	//
	// RETURN
	//

  return $GLOBALS['elements']['current'];

}

//
// OPEN ELEMENT
// output the opening tag of an element
//

function open_element ( $layout, $generator, $acf_ID ) {

  //
  // SETUP ELEMENT
  // create the $element array that stores the ID, classes and other settings
  //

  $GLOBALS['elements']['current'] = setup_element ( $layout, $generator, $acf_ID );

  // index of the given $type in the master array

  $index = array_search ( $GLOBALS['elements']['current']['type'], array_keys ( $GLOBALS['elements']['types'] ) );

  //
  // BEGIN OUTPUT
  //

  echo "\n" . str_repeat ( "\t", $GLOBALS['vars']['indent'] );

	$GLOBALS['vars']['indent']++;

	echo '<div id="' . $GLOBALS['elements']['current']['id'] . '"';

  echo ' class="' . implode ( ' ', $GLOBALS['elements']['current']['classes'] ) . '"';

  if ( !empty ( $GLOBALS['elements']['current']['atts'] ) ) {
    foreach ( $GLOBALS['elements']['current']['atts'] as $att => $val ) {

			if ( is_array ( $val ) ) {
				echo ' data-' . $att . "='" . json_encode ( $val ) . "'";
			} else {
				echo ' data-' . $att . '="' . $val . '"';
			}

    }
  }

  echo '>';


	do_action ( 'after_element_open', $GLOBALS['elements']['current'], 'open' );

  // BACKGROUND

  // output_bg ( $GLOBALS['elements']['current']['bg'] );

  // COLLAPSIBLE

  collapsible_output ( 'open', $layout['type'] );

  // SECTION

  section_output ( 'open' );

  // CONTAINER

  container_output ( 'open' );

	do_action ( 'after_element_wrapper_open', $GLOBALS['elements']['current'], 'open' );

  // CAROUSEL

  // carousel_output ( 'open', $layout['type'] );

  // TABS

  tabs_output ( 'open', $layout['type'] );

  // BLOCK

  block_output ( 'open' );

  // set the element to 'open'

  $GLOBALS['elements']['types'][$GLOBALS['elements']['current']['type']]['is_open'] = true;

}

//
// CLOSE ELEMENT
//

function close_element ( $type ) {

  if ( strpos ( $type, 'block_' ) !== false ) {
    $type = 'block';
  }

  $index = array_search ( $type , array_keys ( $GLOBALS['elements']['types'] ) );

	do_action ( 'before_element_wrapper_close', $type, 'close' );

  // COLLAPSIBLE

  collapsible_output ( 'close', $type );

  // CAROUSEL

  // carousel_output ( 'close', $type );

  // CONTAINER

  if ( $type == 'container' ) {
    container_output ( 'close' );
  }

  // SECTION

  if ( $type == 'section' ) {
    section_output ( 'close' );
  }

  // BLOCK

  if ( $type == 'block' ) {
    block_output ( 'close' );
  }

	do_action ( 'before_element_close', $type, 'close' );

  // ELEMENT

  echo "\n";

	$GLOBALS['vars']['indent']--;

  echo str_repeat ( "\t", $GLOBALS['vars']['indent'] );

  echo '</div><!-- ' . $type . ' -->';

  // TABS

  tabs_output ( 'close', $type );

  // set this element type to closed

  $GLOBALS['elements']['types'][$type]['is_open'] = false;
  $GLOBALS['elements']['current'] = null;

  //
  // ID STUFF
  //

  // unset the 'current id' value of this element type
  // since it won't be used for any more children

  $GLOBALS['elements']['types'][$type]['current_id'] = '';

  // pop the last element off the global ID array

  $remove_ID = array_pop ( $GLOBALS['elements']['id'] );

  //
  // COUNTERS
  // reset the counters for each type below this one
  // i.e. if closing a container, reset the counter for column and block to 1
  //

  foreach ( array_reverse ( $GLOBALS['elements']['types'] ) as $element_type => $data ) {

    if ( $element_type != $type ) {

      // this is a child of the element we're closing,
      // so reset it to 1

      $GLOBALS['elements']['counters'][$element_type] = 1;

    } elseif ( $element_type == $type ) {

      // increment the counter of the element we're closing by 1
      $GLOBALS['elements']['counters'][$element_type]++;

      // found what we needed, stop now
      break;

    }
  }

}

//
// RESOLVE ELEMENTS
// given an element type, go through the element_types array and resolve open/closed statuses
// first go backwards and close elements under the current one
// then go forwards and open new elements as necessary
//

function resolve_elements ( $layout, $acf_ID = null ) {

	if ( $acf_ID == null ) {
		$acf_ID = get_the_ID();
	}

	$this_layout = $layout['type'];

  // if ( strpos ( $this_layout, 'block_' ) !== false ) {
  //   $this_layout = 'block';
  // }

  // if this element type is open,
  // we need to close this and everyone BELOW it
  // i.e. if this is a container, make sure block, column, and container are closed

  // go backwards through $GLOBALS['elements']['types'] until we get to this type
  // and close it if necessary

  $array_index = count ( $GLOBALS['elements']['types'] ) - 1;

  foreach ( array_reverse ( $GLOBALS['elements']['types'] ) as $type => $data ) {

    if ( $type != $layout['type'] && $data['is_open'] == true ) {

      close_element ( $type );

    } elseif ( $type == $layout['type'] ) {

      // we got to the element type that matches the field
      // if it's open, then it's either the same type as the previous field
      // or it's a parent that was skipped over in the layout

      // i.e. a new container after a block
      // block and column have been closed already, now close container

      if ( $data['is_open'] == true ) {
        close_element ( $type );
      }

      break;

    }

    $array_index--;

  }

  // now go forwards through $GLOBALS['elements']['types'] and open new elements

  $array_index = 0;

  foreach ( $GLOBALS['elements']['types'] as $type => $data ) {

    if ( $type != $layout['type'] && $data['is_open'] == false ) {

      open_element ( array ( 'type' => $type, 'subtype' => '' ), 'auto', $acf_ID );

    } elseif ( $type == $layout['type'] ) {

      break;

    }

    $array_index++;

  }

}