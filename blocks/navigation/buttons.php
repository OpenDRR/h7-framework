<?php

  $btn_size = '';
  $btn_class = '';

  if ( 
		$block['btn_size'] != '' && 
		$block['btn_size'] != 'default' 
	) {
    $btn_size = get_sub_field ( 'btn_size' );
	}

  if ( $block['btn_classes'] != '' )
    $btn_class = $block['btn_classes'];

  if ( !empty ( $block['buttons'] ) ) {
    foreach ( $block['buttons'] as $button ) {

      $button_element = array ( 'class' => array() );

      if ( $btn_size != '' ) {
        $button_element['class'][] = 'btn-' . $btn_size;
      }

      if ( $btn_class != '' ) {
        $button_element['class'][] = $btn_class;
      }

      include ( locate_template( 'elements/button.php' ) );

    }
  }

?>
