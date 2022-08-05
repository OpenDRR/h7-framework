<?php

  do_action ( 'fw_before_header' );

  get_header();

  do_action ( 'fw_after_header' );

  if ( have_posts() ) : while ( have_posts() ) : the_post();

    //
    // SECTION LOOP
    //

    do_action ( 'fw_before_content_loop' );

    include ( locate_template ( 'template/loop.php' ) );

    do_action ( 'fw_after_content_loop' );

  endwhile; endif;

  do_action ( 'fw_before_footer' );

  get_footer();

?>
