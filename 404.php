<?php

  do_action ( 'fw_before_header' );

  get_header();

  do_action ( 'fw_after_header' );

  //
  // SECTION LOOP
  //

	$post_template = get_page_by_title ( '404 Page', OBJECT, 'template' );

	if ( $post_template ) {

		content_loop ( 'elements', $post_template->ID );

	  do_action ( 'fw_before_content_loop' );

	  include ( locate_template ( 'template/loop.php' ) );

	  do_action ( 'fw_after_content_loop' );

	} else {
		
		echo '<h1>Page not found</h1>';

	}

  do_action ( 'fw_before_footer' );

  get_footer();

?>
