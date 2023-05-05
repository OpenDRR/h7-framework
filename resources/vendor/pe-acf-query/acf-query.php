<?php

wp_enqueue_script ( 'acf-query', get_bloginfo ( 'template_directory' ) . '/resources/vendor/pe-acf-query/acf-query.js', array ( 'jquery' ), NULL, true );

if ( !isset ( $new_query ) ) $new_query = array();

if ( !isset ( $new_query['type'] ) ) $new_query['type'] = 'posts';

if ( $block['query_type'] != '' ) {
  $new_query['type'] = $block['query_type'];
}

if ( !isset ( $new_query['filters'] ) ) {
	$new_query['filters'] = array();
}

//
// GET POSTS
//

if ( $new_query['type'] == 'posts' ) {

  if ( !isset ( $new_query['args'] ) ) {
    $new_query['args'] = array();
  }

  if ( !isset ( $new_query['args']['posts_per_page'] ) ) {

    if ( $block['posts_per_page'] != '' ) {

      $new_query['args']['posts_per_page'] = $block['posts_per_page'];

    } else {

      $new_query['args']['posts_per_page'] = '-1';

    }

  }

  // 'POST PARAMETERS' GROUP
	
	if ( 
		isset ( $block['post'] ) &&
		!empty ( $block['post'] )
	) {
		
		$post_params = $block['post'];
		
		// 'post types' group
		
		if ( !empty ( $post_params['post_types'] ) ) {
			
			if ( !isset ( $new_query['args']['post_type'] ) ) {
				
				// if not already set manually, 
				// create the 'post_type' parameter,
				// and force public = true
			
				$new_query['args']['post_type'] = get_post_types ( array (
					'public' => true
				) );
			
			}
			
			// 'post type' checkbox
			
			if ( !empty ( $post_params['post_types']['post_type'] ) ) {
			
				$new_query['args']['post_type'] = $post_params['post_types']['post_type'];
			
			}
			
			// if searching for attachments, 
			// post status needs to be set to 'inherit'
			
			if ( in_array ( 'attachment', $new_query['args']['post_type'] ) ) {
				$new_query['args']['post_status'] = 'inherit';
			}
			
			// add parameter to the filters if necessary
			
			if ( $post_params['post_types']['filterable'] == 1 ) {
				$new_query['filters'][] = array (
					'type' => 'post-type',
					'multi' => false
				);
			}
			
		}
		
		// 'sort' group
		
		if ( !empty ( $post_params['sort'] ) ) {
			
			$new_query['args']['orderby'] = $post_params['sort']['orderby'];
	
			if ( $post_params['sort']['orderby'] != 'rand' ) {
				$new_query['args']['order'] = $post_params['sort']['order'];
			}
	
			if ( $new_query['args']['orderby'] == 'meta_value_num' || $new_query['args']['orderby'] == 'meta_value' ) {
				$new_query['args']['meta_key'] = $post_params['sort']['meta_key'];
			}
	
			// random seed
	
			if ( $post_params['sort']['orderby'] == 'rand' ) {
	
				$new_seed = mt_rand ( 100000, 999999 );
	
				// if this element has never created a seed
	
				if ( !isset ( $_SESSION['seeds'][get_current_element_ID()] ) ) {
					$_SESSION['seeds'][get_current_element_ID()] = $new_seed;
				}
	
				// if resetting to page 1 because of a filter change
	
				if ( !empty ( $_GET ) && $new_query['args']['paged'] == 1 ) {
					$_SESSION['seeds'][get_current_element_ID()] = $new_seed;
				}
	
				// update the query arg
	
				$new_query['args']['orderby'] = 'RAND(' . $_SESSION['seeds'][get_current_element_ID()] . ')';
	
			}
	
		}
		
		// 'tax query' group
		
		if ( !empty ( $post_params['tax_query'] ) ) {
	
			$args = array();
			
			if ( !empty ( $post_params['tax_query']['arguments'] ) ) {
				foreach ( $post_params['tax_query']['arguments'] as $tax_arg ) {
					
					$terms = array();
					
					if ( $tax_arg['taxonomy'] != '' ) {
					
						if ( $tax_arg['terms'] != '' ) {
					
							$terms = explode ( ',', $tax_arg['terms'] );
					
							if ( strpos ( $tax_arg['terms'], ',' ) !== false ) {
					
								$terms = explode ( ',', $tax_arg['terms'] );
					
								foreach ( $terms as $term ) {
					
									$args[] = array (
										'taxonomy' => $tax_arg['taxonomy'],
										'field' => 'slug',
										'terms' => $term
									);
					
								}
					
							} else {
					
								$args[] = array (
									'taxonomy' => $tax_arg['taxonomy'],
									'field' => 'slug',
									'terms' => $tax_arg['terms']
								);
					
							}
					
						}
					
						// if filterable, add taxonomy to filters array
					
						if ( $arg['filterable'] == 1 ) {
					
							$new_query['filters'][] = array (
								'type' => 'taxonomy',
								'multi' => $tax_arg['multiple'],
								'taxonomy' => $tax_arg['taxonomy'],
								'terms' => $terms
							);
					
						}
					
					}
					
				}
			}
	
			if ( !empty ( $args ) ) {
	
				$new_query['args']['tax_query'] = $args;
				$new_query['args']['tax_query']['relation'] = $post_params['tax_query']['relation'];
	
			}
	
		}
		
		// 'meta query' group
		
		if ( !empty ( $post_params['meta_query'] ) ) {
	
			$args = array();
			
			if ( !empty ( $post_params['meta_query']['arguments'] ) ) {
				foreach ( $post_params['meta_query']['arguments'] as $meta_arg ) {
					
					// are we adding an argument
					
					if ( $meta_arg['compare'] != '' ) {
						
						$args[] = array (
							'key' => $meta_arg['key'],
							'value' => $meta_arg['value'],
							'compare' => $meta_arg['compare']
						);
						
					}
					
					if ( $meta_arg['filterable'] == 1 ) {
					
						$new_query['filters'][] = array (
							'type' => 'meta',
							'multi' => $meta_arg['multiple'],
							'key' => $meta_arg['key']
						);
					
					}
					
				}
			}
	
			if ( !empty ( $args ) ) {
	
				$new_query['args']['meta_query'] = $args;
	
				if ( count ( $args ) > 1 ) {
					$new_query['args']['meta_query']['relation'] = $post_params['meta_query']['relation'];
				}
	
			}
	
		}
		
		// page parameters
		
		if ( !empty ( $post_params['page_params'] ) ) {
	
			// depth filters
			// all (default), top (0), or parent ID
	
			if ( $post_params['page_params']['depth'] == 'top' ) {
	
				$new_query['args']['post_parent'] = 0;
	
			} elseif ( $post_params['page_params']['depth'] == 'parent' ) {
	
				if ( $post_params['page_params']['post_parent'] != '' ) {
					$new_query['args']['post_parent'] = $post_params['page_params']['post_parent'];
				}
	
			}
	
			// manually select pages
			// TO DO: should probably ofverride/unset other options
	
			if ( $post_params['page_params']['post__in'] != '' ) {
	
				$new_query['args']['post__in'] = $post_params['page_params']['post__in'];
	
			}
	
		}
		
	}

	//
	// FILTER SETTINGS
	// UNUSED??
	//

	// search

// // 	if ( have_rows ( 'search' ) ) {
// // 		while ( have_rows ( 'search' ) ) {
// // 			the_row();
// // 
// // 			$new_filter = array (
// // 				'type' => 'search',
// // 				'input_class' => $block['input_class']
// // 			);
// // 
// // 			if ( $block['location'] == 'filter' ) {
// // 
// // 				array_unshift ( $new_query['filters'], $new_filter );
// // 
// // 			} elseif ( $block['location'] == 'above' ) {
// // 
// // 				$new_query['header_filters'][] = $new_filter;
// // 
// // 			}
// // 
// // 		}
// // 	}
// 
// 	// post count
// 
// 	if ( have_rows ( 'post_count' ) ) {
// 		while ( have_rows ( 'post_count' ) ) {
// 			the_row();
// 
// 			$new_filter = array (
// 				'type' => 'post_count'
// 			);
// 
// 			if ( $block['location'] == 'filter' ) {
// 
// 				array_unshift ( $new_query['filters'], $new_filter );
// 
// 			} elseif ( $block['location'] == 'above' ) {
// 
// 				$new_query['header_filters'][] = $new_filter;
// 
// 			}
// 
// 		}
// 	}

  //
  // FILTER ARGS
  //
  //
  // $new_query['filter_args'] = array();
  //
  // foreach ( $new_query['filters'] as $filter ) {
  //
  //   if ( $filter['type'] == 'taxonomy' ) {
  //
  //     $new_query['filter_args'][$filter['type']][$filter['taxonomy']] = array();
  //
  //   } else {
  //
  //     $new_query['filter_args'][$filter['type']] = array();
  //
  //   }
  //
  // }
  //
  // echo '<pre>';
  //
  // print_r($new_query['filter_args']);
  //
  // echo '</pre>';

  //
  // ADJUST BASED ON QUERY STRING PARAMETERS
  //

  // $new_query['filter_args'] = $new_query['filters'];

  if ( isset ( $new_query['filter_args'] ) ) {

    $pt_reset = false;
    $tx_reset = false;
		$meta_reset = false;

    // echo 'has query string';

    foreach ( $new_query['filter_args'] as $arg ) {

      $new_arg = explode ( '|', $arg );

			switch ( $new_arg[0] ) {
				case 'search' :

					$new_query['args']['s'] = $new_arg[1];
					break;

				case 'sort' :

					$new_query['args']['orderby'] = $new_arg[1];
					$new_query['args']['order'] = $new_arg[2];
					break;

				case 'pt' :

	        if ( $pt_reset == false ) {
	          $new_query['args']['post_type'] = array();
	          $pt_reset = true;
	        }

	        $new_query['args']['post_type'] = $new_arg[1];

					break;

				case 'tx' :

					// if filtering by taxonomy and
					// tax_query exists

					if (
						is_array ( $new_query['args']['tax_query'] ) &&
						!empty ( $new_query['args']['tax_query'] )
					) {

						$i = 0;

						foreach ( $new_query['args']['tax_query'] as $tax_arg ) {

							// unset any tax_query argument
							// where the taxonomy matches the one that
							// is being filtered

							if ( isset ( $tax_arg['taxonomy'] ) ) {

								if ( $tax_arg['taxonomy'] == $new_arg[1] ) {
									unset ( $new_query['args']['tax_query'][$i] );
								}

							}

							$i++;

						}

					}

	        // if ( $tx_reset == false ) {
	        //   $new_query['args']['tax_query'] = array( 'relation' => 'AND' );
	        //   $tx_reset = true;
	        // }

	        $new_query['args']['tax_query'][] = array (
	          'taxonomy' => $new_arg[1],
	          'field' => 'id',
	          'terms' => $new_arg[2]
	        );

					break;
					
				case 'cf' :
					
					// grab the last element of new arg as the value
					
					$cf_val = array_pop ( $new_arg );
					
					// drop the first element from new arg ('cf_')
					
					$cf_remove = array_shift ( $new_arg );
					
					// implode what's left as the key
					
					$new_arg = implode( '_', $new_arg );
					
					// does this filter allow multiple selections
					
					$filter_multi = false;
					
					foreach ( $new_query['filters'] as $filter ) {
						
						if ( $filter['key'] == $new_arg && $filter['multi'] == 'true' ) {
							$filter_multi = true;
						}
					} 
					
					// if meta_query exists in query['args']
					
					if (
						isset ( $new_query['args']['meta_query'] ) &&
						is_array ( $new_query['args']['meta_query'] ) &&
						!empty ( $new_query['args']['meta_query'] )
					) {
						
						$new_query['args']['meta_query']['relation'] = 'AND';
						
						// if this filter doesn't allow multiple selections
						
						if ( $filter_multi == false ) {
						
							$i = 0;
						
							foreach ( $new_query['args']['meta_query'] as $meta_arg ) {
						
								// unset any meta_query argument
								// where the key matches the one that
								// is being filtered
						
								if ( $meta_arg['key'] == $new_arg ) {
									unset ( $new_query['args']['meta_query'][$i] );
								}
						
								$i++;
						
							}
							
						}
					
					}
					
					// add this filter
					
					$new_query['args']['meta_query'][] = array (
						'key' => $new_arg,
						'value' => $cf_val,
						'compare' => '='
					);
					
					break;

      }

    }


  } else {
    $new_query['filter_args'] = array();
  }

  //
  // PAGE
  //

  //
  // CREATE ITEMS
  //

  $items = array();

  // $new_query['results'] = get_posts ( $new_query['args'] );

  // if ( !empty ( $new_query['results'] ) ) {
  //   foreach ( $new_query['results'] as $result ) {
  //     $items[] = $result->ID;
  //   }
  //
  //   $new_query['items'] = $items;
  // }

	$new_query['args'] = apply_filters ( 'acf_query_results', $new_query['args'] );

  $new_query['results'] = new WP_Query ( $new_query['args'] );

	// echo '<div class="ajax-output d-none">';

	// print_r($new_query['args']);
	// echo "\n\n";
	// print_r($new_query['results']);


	// echo '</div>';

  if ( $new_query['results']->have_posts () ) :

    $old_post = $post;

    while ( $new_query['results']->have_posts () ) :

      $new_query['results']->the_post();

      // $new_query['items'][] = get_the_ID();

      $new_query['items'][] = array (
        'id' => get_the_ID(),
        'title' => get_the_title(),
        'permalink' => get_permalink(),
        'post_type' => get_post_type(),
        'content' => $post->post_content,
        'thumbnail' => get_post_thumbnail_id ( get_the_ID() )
      );

    endwhile;

    wp_reset_postdata();

    $post = $old_post;

    setup_postdata ( $post );

  endif;

} elseif ( $new_query['type'] == 'terms' ) {

  $new_query['args'] = array (
    'hide_empty' => false
  );

  // 'TAXONOMY PARAMETERS' GROUP
	
	if ( 
		isset ( $block['taxonomy'] ) &&
		!empty ( $block['taxonomy'] )
	) {
		
		$tax_params = $block['taxonomy'];
		
		if ( $tax_params['taxonomy'] != '' ) {
			$tax_val = $tax_params['taxonomy'];

    	if ( strpos ( $tax_val, ',' ) !== false ) {
      	$tax_val = explode ( ',', $tax_val );
    	}
	
    	$new_query['args']['taxonomy'] = $tax_val;

    }
		
  }

	// PAGINATION

  if (
		$block['posts_per_page'] != -1 &&
		$block['posts_per_page'] != '-1' &&
		$block['posts_per_page'] != ''
	) {

    $new_query['args']['number'] = (int) $block['posts_per_page'];

  }

  $new_query['results'] = get_terms ( $new_query['args'] );

  if ( !empty ( $new_query['results'] ) ) {

    foreach ( $new_query['results'] as $result ) {

      $new_query['items'][] = array (
        'id' => $result->term_id,
        'title' => $result->name,
        'permalink' => get_term_link ( $result->term_id, $result->taxonomy ),
        'post_type' => 'term',
        'content' => $result->description,
        'thumbnail' => ''
      );

    }

  }

}

//
// DEBUGGING OUTPUT
//

if ( $block['debug'] == 1 && current_user_can ( 'administrator' ) ) {

  echo '<pre class="ajax-output d-none">';

  print_r ( $new_query['args'] );

  echo '</pre>';

}
