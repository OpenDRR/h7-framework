<?php

wp_enqueue_script ( 'acf-query', get_bloginfo ( 'template_directory' ) . '/resources/vendor/pe-acf-query/acf-query.js', array ( 'jquery' ), NULL, true );

if ( !isset ( $new_query ) ) $new_query = array();

if ( !isset ( $new_query['type'] ) ) $new_query['type'] = 'posts';

if ( get_sub_field ( 'query_type' ) != '' ) {
  $new_query['type'] = get_sub_field ( 'query_type' );
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

    if ( get_sub_field ( 'posts_per_page' ) != '' ) {

      $new_query['args']['posts_per_page'] = get_sub_field ( 'posts_per_page' );

    } else {

      $new_query['args']['posts_per_page'] = '-1';

    }

  }

  // 'POST PARAMETERS' GROUP

  if ( have_rows ( 'post' ) ) {
    while ( have_rows ( 'post' ) ) {
      the_row();

      // 'post types' group

      if ( have_rows ( 'post_types' ) ) {
        while ( have_rows ( 'post_types' ) ) {
          the_row();

          if ( !isset ( $new_query['args']['post_type'] ) ) {
            // $new_query['args']['post_type'] = 'any';

						$new_query['args']['post_type'] = get_post_types ( array (
							'public' => true
						) );

          }

          if ( get_sub_field ( 'post_type' ) != '' ) {

            $selected_post_types = get_sub_field ( 'post_type' );

            if ( $selected_post_types[0] != '' ) {

              $new_query['args']['post_type'] = get_sub_field ( 'post_type' );

            }

          }

					// if searching for attachments, post status needs to be set to 'inherit'

					if ( in_array ( 'attachment', $new_query['args']['post_type'] ) ) {
						$new_query['args']['post_status'] = 'inherit';
					}

          if ( get_sub_field ( 'filterable' ) == 1 ) {
            $new_query['filters'][] = array (
              'type' => 'post-type',
              'multi' => false
            );
          }

        }
      }

      // 'sort' group

      if ( have_rows ( 'sort' ) ) {
        while ( have_rows ( 'sort' ) ) {
          the_row();

          $new_query['args']['orderby'] = get_sub_field ( 'orderby' );

          if ( get_sub_field ( 'orderby' ) != 'rand' ) {
            $new_query['args']['order'] = get_sub_field ( 'order' );
          }

          if ( $new_query['args']['orderby'] == 'meta_value_num' || $new_query['args']['orderby'] == 'meta_value' ) {
            $new_query['args']['meta_key'] = get_sub_field ( 'meta_key' );
          }

					// random seed

					if ( get_sub_field ( 'orderby' ) == 'rand' ) {

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
      }

      // 'tax query' group

      if ( have_rows ( 'tax_query' ) ) {
        while ( have_rows ( 'tax_query' ) ) {
          the_row();

          $args = array();

          if ( have_rows ( 'arguments' ) ) {
            while ( have_rows ( 'arguments' ) ) {
              the_row();

							$terms = array();

							if ( get_sub_field ( 'taxonomy' ) != '' ) {

								if ( get_sub_field ( 'terms' ) != '' ) {

									$terms = explode ( ',', get_sub_field ( 'terms' ) );

									if ( strpos ( get_sub_field ( 'terms' ), ',' ) !== false ) {

										$terms = explode ( ',', get_sub_field ( 'terms' ) );

										foreach ( $terms as $term ) {

											$args[] = array (
			                  'taxonomy' => get_sub_field ( 'taxonomy' ),
			                  'field' => 'slug',
			                  'terms' => $term
			                );

										}

									} else {

		                $args[] = array (
		                  'taxonomy' => get_sub_field ( 'taxonomy' ),
		                  'field' => 'slug',
		                  'terms' => get_sub_field ( 'terms' )
		                );

		              }

								}

								// if filterable, add taxonomy to filters array

								if ( get_sub_field ( 'filterable' ) == 1 ) {

	                $new_query['filters'][] = array (
	                  'type' => 'taxonomy',
	                  'multi' => get_sub_field ( 'multiple' ),
	                  'taxonomy' => get_sub_field ( 'taxonomy' ),
										'terms' => $terms
	                );

	              }

							}

            }
          }

          if ( !empty ( $args ) ) {

            $new_query['args']['tax_query'] = $args;
            $new_query['args']['tax_query']['relation'] = get_sub_field ( 'relation' );

          }

        }
      }

      // 'meta query' group

      if ( have_rows ( 'meta_query' ) ) {
        while ( have_rows ( 'meta_query' ) ) {
          the_row();

          $args = array();

          if ( have_rows ( 'arguments' ) ) {
            while ( have_rows ( 'arguments' ) ) {
              the_row();

              $args[] = array (
                'key' => get_sub_field ( 'key' ),
                'value' => get_sub_field ( 'value' ),
                'compare' => get_sub_field ( 'compare' )
              );

            }
          }

          if ( !empty ( $args ) ) {

            $new_query['args']['meta_query'] = $args;

            if ( count ( $args ) > 1 ) {
              $new_query['args']['meta_query']['relation'] = get_sub_field ( 'relation' );
            }

          }

        }
      }

      // page parameters

      if ( have_rows ( 'page_params' ) ) {
        while ( have_rows ( 'page_params' ) ) {
          the_row();

					// depth filters
					// all (default), top (0), or parent ID

					if ( get_sub_field ( 'depth' ) == 'top' ) {

						$new_query['args']['post_parent'] = 0;

					} elseif ( get_sub_field ( 'depth' ) == 'parent' ) {

						if ( get_sub_field ( 'post_parent' ) != '' ) {
            	$new_query['args']['post_parent'] = get_sub_field ( 'post_parent' );
						}

          }

					// manually select pages
					// TO DO: should probably ofverride/unset other options

          if ( get_sub_field ( 'post__in' ) != '' ) {

            $new_query['args']['post__in'] = get_sub_field ( 'post__in' );

          }

        }
      }

    }
  }

	//
	// FILTER SETTINGS
	//

	// search

	if ( have_rows ( 'search' ) ) {
		while ( have_rows ( 'search' ) ) {
			the_row();

			$new_filter = array (
				'type' => 'search',
				'input_class' => get_sub_field ( 'input_class' )
			);

			if ( get_sub_field ( 'location' ) == 'filter' ) {

				array_unshift ( $new_query['filters'], $new_filter );

			} elseif ( get_sub_field ( 'location' ) == 'above' ) {

				$new_query['header_filters'][] = $new_filter;

			}

		}
	}

	// post count

	if ( have_rows ( 'post_count' ) ) {
		while ( have_rows ( 'post_count' ) ) {
			the_row();

			$new_filter = array (
				'type' => 'post_count'
			);

			if ( get_sub_field ( 'location' ) == 'filter' ) {

				array_unshift ( $new_query['filters'], $new_filter );

			} elseif ( get_sub_field ( 'location' ) == 'above' ) {

				$new_query['header_filters'][] = $new_filter;

			}

		}
	}

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

    // echo 'has query string';

    foreach ( $new_query['filter_args'] as $arg ) {

      $new_arg = explode ( '_', $arg );

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

  if ( have_rows ( 'taxonomy' ) ) {
    while ( have_rows ( 'taxonomy' ) ) {
      the_row();

      $tax_val = get_sub_field ( 'taxonomy' );

      if ( strpos ( $tax_val, ',' ) !== false ) {
        $tax_val = explode ( ',', $tax_val );
      }

      $new_query['args']['taxonomy'] = $tax_val;

    }
  }

  //

  if (
		get_sub_field ( 'posts_per_page' ) != -1 &&
		get_sub_field ( 'posts_per_page' ) != '-1' &&
		get_sub_field ( 'posts_per_page' ) != ''
	) {

    $new_query['args']['number'] = (int) get_sub_field ( 'posts_per_page' );

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

if ( get_sub_field ( 'debug' ) == 1 && current_user_can ( 'administrator' ) ) {

  echo '<pre class="ajax-output d-none">';

  print_r ( $new_query['args'] );

  echo '</pre>';

}
