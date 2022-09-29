<?php

  do_action ( 'fw_before_header' );

  get_header();

  do_action ( 'fw_after_header' );

  if ( have_posts() ) : while ( have_posts() ) : the_post();

		$this_ID = get_the_ID();

		//
		// LAYOUT
		// figure out which layout to use
		//

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
