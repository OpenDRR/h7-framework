<?php

  $btn_size = '';
  $btn_class = '';

  if ( get_sub_field ( 'btn_size' ) != '' && get_sub_field ( 'btn_size' ) != 'default' )
    $btn_size = get_sub_field ( 'btn_size' );

  if ( get_sub_field ( 'btn_classes' ) != '' )
    $btn_class = get_sub_field ( 'btn_classes' );

  if ( have_rows ( 'buttons' ) ) {
    while ( have_rows ( 'buttons' ) ) {
      the_row();

      $button = array ( 'class' => array() );

      if ( $btn_size != '' ) {
        $button['class'][] = 'btn-' . $btn_size;
      }

      if ( $btn_class != '' ) {
        $button['class'][] = $btn_class;
      }

      include ( locate_template( 'elements/button.php' ) );

    }
  }

?>
