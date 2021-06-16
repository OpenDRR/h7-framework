<?php

//
// MARKUP FUNCTIONS
// functions for setup / extra markup that's required for certain element types and settings
//

//
// ELEMENT TYPES
//

// SECTION

function section_output ( $action ) {

	if (
    $action == 'open' &&
    $GLOBALS['elements']['current']['type'] == 'section'
  ) {

		echo "\n";

		echo str_repeat ( "\t", $GLOBALS['vars']['indent'] );
		$GLOBALS['vars']['indent']++;

    echo '<div class="section-wrap">';

  } elseif ( $action == 'close' ) {

    echo "\n";

		$GLOBALS['vars']['indent']--;
		echo str_repeat ( "\t", $GLOBALS['vars']['indent'] );

		echo '</div><!-- .section-wrap -->';

	}

	// remove_action ( 'after_element_open', 'section_output', 10, 2 );

}

// CONTAINER

function container_output ( $action ) {

  if (
    $action == 'open' &&
    $GLOBALS['elements']['current']['type'] == 'container'
  ) {

    echo "\n";

		echo str_repeat ( "\t", $GLOBALS['vars']['indent'] );
		$GLOBALS['vars']['indent']++;

		echo '<div class="row fw-container-row ' . get_sub_field ( 'row_classes' ) . '">';

  } elseif ( $action == 'close' ) {

		echo "\n\n";
		$GLOBALS['vars']['indent']--;
		echo str_repeat ( "\t", $GLOBALS['vars']['indent'] );

    echo '</div><!-- row -->';

  }

	// remove_action ( 'after_element_open', 'container_output', 10, 2 );

}

// BLOCK

function block_output ( $action ) {

	echo "\n";

	if (
    $action == 'open' &&
    $GLOBALS['elements']['current']['type'] == 'block'
  ) {

		$GLOBALS['elements']['current']['row_index'] = (int) get_row_index() - 1;

		$current_element = $GLOBALS['elements']['current'];

		echo str_repeat ( "\t", $GLOBALS['vars']['indent'] );
		$GLOBALS['vars']['indent']++;

    $theme_include_path = 'blocks/';

    if ( $GLOBALS['elements']['current']['subtype'] != '' ) {
			$theme_include_path .= $current_element['subtype'] . '/';
		}

    if ( have_rows ( 'blocks' ) ) {
      while ( have_rows ( 'blocks' ) ) {
        the_row();

				$theme_include_path .= get_row_layout() . '.php';

				$field_obj = $GLOBALS['fw_fields']['builder_groups']['block_' . $current_element['subtype']][get_row_layout()];

				if (
					isset ( $field_obj['settings']['template'] ) &&
					!empty ( $field_obj['settings']['template'] )
				) {
					$field_include_path = $field_obj['settings']['template'];
				}

        if ( locate_template ( $theme_include_path ) != '' ) {

					// look for a template in the theme folders

          include ( locate_template ( $theme_include_path ) );

        } elseif ( isset ( $field_include_path ) ) {

					// then in the builder field settings

					include ( $field_include_path );

				} else {

					// then throw an error

          echo '<p class="alert alert-secondary">';
          echo $field_include_path . ' not found';
          echo '</p>';

        }

      }
    }

  } elseif ( $action == 'close' ) {

		// echo str_repeat ( "\t", $GLOBALS['vars']['indent'] );
		$GLOBALS['vars']['indent']--;

    // no output

  }

  // acf_form( array(
  //   'post_id' => get_the_ID(),
  //   'fields' => array('field_5d09354453c81')
  // ) );

	// remove_action ( 'after_element_open', 'block_output', 10, 2 );

}

//
// BACKGROUND
//

function output_bg ( $element ) {

  if ( isset ( $element['bg'] ) && $element['bg'] != null && !empty ( $element['bg'] ) ) {

		$bg_element = array (
			'classes' => array(),
			'img_url' => '',
			'video_url' => ''
		);

		// tab index

    $index = array_search ( get_current_element_type(), array_keys ( $GLOBALS['elements']['types'] ) );

    echo "\n";

    // image / video URL

    switch ( $element['bg']['type'] ) {
      case 'thumbnail' :

				$bg_element['img_url'] = get_the_post_thumbnail_url ( get_the_ID(), 'bg' );

        if ( is_archive() ) {

          $bg_element['img_url'] = wp_get_attachment_image_url ( get_field ( 'term_image', $GLOBALS['vars']['current_query']->taxonomy . '_' . $GLOBALS['vars']['current_query']->term_id ), 'bg' );

        } elseif ( is_author() || is_category() || is_home() || is_tag() ) {

					$bg_element['img_url'] = get_the_post_thumbnail_url ( get_option ( 'page_for_posts' ), 'bg' );

				}

        break;

      case 'image' :
        $bg_element['img_url'] = wp_get_attachment_image_url ( $element['bg']['image'], 'bg' );
        break;

      case 'video' :

        if ( $element['bg']['image'] != '' ) {
          $bg_element['img_url'] = wp_get_attachment_image_url ( $element['bg']['image'], 'bg' );
        }

        if ( $element['bg']['video'] != '' ) {
          $bg_element['classes'][] = 'video';
          $bg_element['video_url'] = wp_get_attachment_url ( $element['bg']['video'] );
        }

        break;

    }

    //
    // BG image settings
    // if a BG URL exists, either by a subfield or set manually in a template
    //

    // opacity

    $bg_element['classes'][] = 'opacity-' . $element['bg']['opacity'];

    if ( $bg_element['img_url'] != '' ) {

      // position

      if (
        !is_array ( $element['bg']['position'] ) &&
        $element['bg']['position'] != ''
      ) {
        $bg_element['classes'][] = 'bg-position-' . str_replace( ' ', '-', $element['bg']['position'] );
      }

      // size

      if (
        !is_array ( $element['bg']['size'] ) &&
        $element['bg']['size'] != ''
      ) {
        $bg_element['classes'][] = 'bg-size-' . $element['bg']['size'];
      }

      // attachment

      if (
        !is_array ( $element['bg']['attachment'] ) &&
        $element['bg']['attachment'] != ''
      ) {
        $bg_element['classes'][] = 'bg-attachment-' . $element['bg']['attachment'];
      }

    }

    if ( $bg_element['img_url'] != '' || $bg_element['video_url'] != '' ) {

			echo "\n" . str_repeat ( "\t", $GLOBALS['vars']['indent'] );

			echo '<div class="bg ' . implode ( ' ', $bg_element['classes'] ) . '" style="background-image: url(' . $bg_element['img_url'] . ');">';

			$GLOBALS['vars']['indent']++;

      if ( $bg_element['video_url'] != '' ) {

        echo "\n" . str_repeat ( "\t", $GLOBALS['vars']['indent'] ) . "\t" . '<video loop autoplay muted>';
        echo "\n" . str_repeat ( "\t", $GLOBALS['vars']['indent'] ) . "\t\t" . '<source src="' . $bg_element['video_url'] . '" type="video/mp4">';
        echo "\n" . str_repeat ( "\t", $GLOBALS['vars']['indent'] ) . "\t\t" . 'Your browser does not support the video tag.';
        echo "\n" . str_repeat ( "\t", $GLOBALS['vars']['indent'] ) . "\t" . '</video>';

      }

			$GLOBALS['vars']['indent']--;

      echo "\n" . str_repeat ( "\t", $GLOBALS['vars']['indent'] ) . '</div>';


    }

  } // if bg

	remove_action ( 'after_element_open', 'output_bg', 10, 2 );

}

//
// UX FUNCTIONALITY
// e.g. collapsible, carousel
//

//
// COLLAPSIBLE
//

// SETUP

function collapsible_setup() {

	$GLOBALS['elements']['current']['classes'][] = 'collapsible';

  $collapsible = array (
    'breakpoint' => get_sub_field ( 'breakpoint' ),
		'trigger' => get_sub_field ( 'trigger' )
  );

	$GLOBALS['elements']['current']['atts']['collapsible'] = $collapsible['trigger'];

  return $collapsible;

}

// OUTPUT

function collapsible_output ( $element, $action ) {

	if ( $action == 'open' ) {
		$type = $GLOBALS['elements']['current']['type'];
	} else {
		$type = $element;
	}

  if ( isset ( $GLOBALS['elements']['types'][$type]['collapsible'] ) ) {

		$collapsible = $GLOBALS['elements']['types'][$type]['collapsible'];

    if ( $action == 'open' ) {

      if ( $collapsible['breakpoint'] != 'default' ) {

        echo "\n" . '<nav class="navbar navbar-expand-' . $collapsible['breakpoint'] . '">';

      }

  ?>

      <button class="navbar-toggler btn btn-lg btn-transparent text-secondary" type="button" data-toggle="collapse" data-target="#<?php echo $GLOBALS['elements']['current']['id']; ?>-collapse" aria-controls="<?php echo $GLOBALS['elements']['current']['id']; ?>-collapse" aria-expanded="false" aria-label="Toggle navigation">

        <?php

          if ( $collapsible['trigger']['expand_text'] != '' ) {
            echo '<span>' . $collapsible['trigger']['expand_text'] . '</span>';
          }

					if ( $collapsible['trigger']['expand_icon'] != '' ) {

        ?>

        <i class="<?php echo $collapsible['trigger']['expand_icon']; ?>"></i>

				<?php

					}

				?>
      </button>

      <!-- begin collapsible area -->

      <div id="<?php echo $GLOBALS['elements']['current']['id']; ?>-collapse" class="collapse navbar-collapse">

  <?php

    } elseif ( $action == 'close' ) {

      // close the .collapse wrapper
      echo "\n\t" . '</div><!-- .collapse -->';

      // if the breakpoint is set, close the <nav> element
      if (
        $collapsible['breakpoint'] != '' &&
        $collapsible['breakpoint'] != 'default'
      ) {
        echo "\n" . '</nav>';
      }

      echo "\n";// . '<!-- end collapsible area -->';

      // reset the breakpoint
      // $GLOBALS['elements']['types'][$type]['collapsible']['breakpoint'] = '';

			unset ( $GLOBALS['elements']['types'][$type]['collapsible'] );

    }

  }

	// remove_action ( 'after_element_open', 'collapsible_output', 20, 2 );

}



//
// TABS
//

// SETUP

function tabs_setup() {

  $tabs = array (
    'labels' => get_sub_field ( 'labels' ),
		'menu_class' => get_sub_field ( 'menu_classes' )
  );

  $all_types = array_keys ( $GLOBALS['elements']['types'] );
  $tabs_position = array_search ( get_current_element_type(), $all_types );

  if ( isset ( $all_types[$tabs_position + 1] ) ) {
    $next_type = $all_types[$tabs_position + 1];
  }

  $tabs['panel'] = $next_type;

  return $tabs;

}

// OUTPUT

function tabs_output ( $action, $type ) {

  if ( isset ( $GLOBALS['elements']['types'][$type]['tabs'] ) ) {

    if ( $action == 'open' ) {

      $GLOBALS['vars']['current_tabs'] = $type;
      //
      // echo "\n" . '<div class="row">';
      // echo "\n\t" . '<div class="col">';

      echo "\n\t\t" . '<ul class="col-12 tab-menu ' . $GLOBALS['elements']['types'][$type]['tabs']['menu_class'] . '">';

      if (
        isset ( $GLOBALS['elements']['types'][$type]['tabs']['labels'] ) &&
        !empty ( $GLOBALS['elements']['types'][$type]['tabs']['labels'] )
      ) {

        remove_filter ('the_content', 'wpautop');

        foreach ( $GLOBALS['elements']['types'][$type]['tabs']['labels'] as $label ) {



          echo "\n\t\t\t" . '<li><a>' . apply_filters ( 'the_content', $label['label'] ) . '</a></li>';
        }

        add_filter ('the_content', 'wpautop');

      }

      echo "\n\t\t" . '</ul>';
      // echo "\n\t" . '</div>';
      // echo "\n" . '</div>';

    } elseif ( $action == 'close' ) {

      $GLOBALS['vars']['current_tabs'] = '';

      unset ( $GLOBALS['elements']['types'][$type]['tabs'] );

    }

  }

}
