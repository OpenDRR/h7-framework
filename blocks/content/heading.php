<?php

  do_action ( 'fw_before_heading_block' );

  $head_tag = $block['level'];

  $head_class = array();

	if ( $block['classes'] != '' ) {
		$head_class = explode ( ' ', $block['classes'] );
	}

  if ( $block['text'] != '' ) {

    echo '<' . $head_tag . ' class="heading-text ' . implode ( ' ', $head_class ) . '">';

    echo do_shortcode ( $block['text'] );

    echo '</' . $head_tag . '>';

  }

  do_action ( 'fw_after_heading_block' );

?>
