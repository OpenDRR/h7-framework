<?php

  do_action ( 'fw_before_header' );

  get_header();

  do_action ( 'fw_after_header' );

  if ( have_posts() ) : while ( have_posts() ) : the_post();

		// post ID
		$this_ID = get_the_ID();
		
		//
		// LAYOUT
		// figure out which layout to use
		//
		
		// take '.php' off of the template filename
		// to use in the layout meta query
		
		$this_template = str_replace ( '.php', '', $GLOBALS['vars']['current_template'] );

		$layout_query = new WP_Query ( array (
			'post_type' => 'layout',
			'posts_per_page' => 1,
			'meta_query' => array (
				array (
					'key' => 'layout_file',
					'value' => str_replace ( '.php', '', $GLOBALS['vars']['current_template'] ),
					'compare' => 'LIKE'
				)
			)
		) );
		
		if ( $layout_query->have_posts() ) {
			while ( $layout_query->have_posts() ) {
				$layout_query->the_post();
				
				// found the post,
				// iterate through the builder field
				
				if ( have_rows ( 'layout_builder' ) ) {
					while ( have_rows ( 'layout_builder' ) ) {
						the_row();

						switch ( get_row_layout() ) {

							case 'content' :

						    //
						    // THIS PAGE'S CONTENT LOOP
						    //

								if ( current_user_can ( 'administrator' ) ) {
									echo "\n\n";
									echo '<!-- MAIN CONTENT LOOP -->';
									echo "\n\n";
								}

								echo '<main>';
								
								// add the requested builder field to the global vars
								
								do_action ( 'fw_before_content_loop', 'elements', $this_ID );

								content_loop ( 'elements', $this_ID );

								do_action ( 'fw_after_content_loop' );

								echo '</main>';

								break;

							case 'include' :
								
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
								
								break;
								
							case 'template' :

								//
								// PULL A TEMPLATE
								//

								if ( current_user_can ( 'administrator' ) ) {
									echo "\n\n";
									echo '<!-- TEMPLATE LOOP: ' . get_sub_field ( 'template' ) . ' -->';
									echo "\n\n";
								}
								
								do_action ( 'fw_before_content_loop', 'elements', get_sub_field ( 'template' ) );

								content_loop ( 'elements', get_sub_field ( 'template' ) );
								
								do_action ( 'fw_after_content_loop' );
								
								break;

						}

					}
				}

			}
			
		} else {
		
			do_action ( 'fw_before_content_loop', 'elements', $this_ID );

			content_loop ( 'elements', $this_ID );

			do_action ( 'fw_after_content_loop' );

		}

  endwhile; endif;

  do_action ( 'fw_before_footer' );

  get_footer();

?>
