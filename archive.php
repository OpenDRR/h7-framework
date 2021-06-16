<?php

  get_header();

  //
  // TAXONOMY DATA
  //

  $current_query = get_queried_object();

  //
  // SECTION LOOP
  //

  $section_field = 'sections';
  $section_field_ID = $current_query->taxonomy . '_' . $current_query->term_id;

  do_action ( 'fw_before_sections_loop' );

	content_loop ( 'elements', $section_field_ID );

  do_action ( 'fw_after_sections_loop' );

  get_footer();

?>
