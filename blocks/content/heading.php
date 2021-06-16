<?php

  do_action ( 'fw_before_heading_block' );

  $head_tag = get_sub_field ( 'level' );

  $head_class = array();

	if ( get_sub_field ( 'classes' ) != '' ) {
		$head_class = explode ( ' ', get_sub_field ( 'classes' ) );
	}

  if ( get_sub_field ( 'text' ) != '' ) {

    echo '<' . $head_tag . ' class="heading-text ' . implode ( ' ', $head_class ) . '">';

    echo do_shortcode ( get_sub_field ( 'text' ) );

    echo '</' . $head_tag . '>';

  }

  do_action ( 'fw_after_heading_block' );

?>
