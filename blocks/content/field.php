<?php

$field_post_ID = get_the_ID();
$field_key = get_sub_field ( 'field' );

if ( is_archive () ) {

	$field_post_ID = $GLOBALS['vars']['current_query']->taxonomy . '_' . $GLOBALS['vars']['current_query']->term_id;

}

if ( get_sub_field ( 'post_id' ) != '' ) {
  $field_post_ID = get_sub_field ( 'post_id' );
}

if ( get_sub_field ( 'display') == 'block' ) {

	$field_type = get_sub_field ( 'template' );

	if ( have_rows ( $field_key, $field_post_ID ) ) {

		$field_object = get_field_object ( $field_key, $field_post_ID );

		while ( have_rows ( $field_key, $field_post_ID ) ) {
			the_row();

			include ( locate_template ( 'blocks/' . $field_type . '.php' ) );

		}
	}

} else {

	if ( get_post_meta ( $field_post_ID, $field_key, true ) != '' ) {

		echo '<' . get_sub_field ( 'display' ) . '>';

		echo get_field ( $field_key, $field_post_ID );

		echo '</' . get_sub_field ( 'display' ) . '>';

	}

}
