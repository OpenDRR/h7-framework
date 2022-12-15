<?php

$field_post_ID = $GLOBALS['vars']['current_query']->ID;
$field_key = $block['field'];

if ( is_archive () ) {

	$field_post_ID = $GLOBALS['vars']['current_query']->taxonomy . '_' . $GLOBALS['vars']['current_query']->term_id;

}

if ( $block['post_id'] != '' ) {
  $field_post_ID = $block['post_id'];
}

if ( $block['display'] == 'block' ) {

	$field_type = $block['template'];

	if ( have_rows ( $field_key, $field_post_ID ) ) {

		$field_object = get_field_object ( $field_key, $field_post_ID );

		while ( have_rows ( $field_key, $field_post_ID ) ) {
			the_row();

			include ( locate_template ( 'blocks/' . $field_type . '.php' ) );

		}
	}

} else {
	
	if ( get_post_meta ( $field_post_ID, $field_key, true ) != '' ) {
		
		$output = get_post_meta ( $field_post_ID, $field_key, true );
		
		// convert new lines to paragraphs if
		
		if ( $block['display'] == 'p' ) {
			
			echo apply_filters ( 'the_content', $output );
			
		} else {
		
			echo '<' . $block['display'] . '>';
	
			echo $output;
	
			echo '</' . $block['display'] . '>';
			
		}
		
	}

}
