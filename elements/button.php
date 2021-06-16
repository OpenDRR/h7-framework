<?php

  $show_btn = true;

  //
  // BUTTON ELEMENT
  //

  if ( !isset ( $button ) )
    $button = array();

  if ( !isset ( $button['href'] ) )
    $button['href'] = '#';

  if ( !isset ( $button['text'] ) )
    $button['text'] = 'Learn more';

  if ( !isset ( $button['target'] ) )
    $button['target'] = '';

  if ( !isset ( $button['class'] ) ) {
    $button['class'] = array ( 'btn' );
  } else {
    $button['class'][] = 'btn';
  }

  //
  // LINK TYPE
  //

  switch ( get_sub_field ( 'type' ) ) {

    case 'scroll' :

      if ( get_sub_field ( 'id' ) != '' ) {
        $button['href'] = '#' . get_sub_field ( 'id' );
      } else {
        $button['class'][] = 'next-section';
      }

      break;

    case 'post' :
    case 'page' :

      if ( get_sub_field ( get_sub_field ( 'type' ) ) != '' ) {
        $button['href'] = get_permalink ( get_sub_field ( get_sub_field ( 'type' ) ) );
        $button['text'] = get_the_title ( get_sub_field ( get_sub_field ( 'type' ) ) );
      }

      break;

    case 'url' :

      $button['href'] = get_sub_field ( 'url' );

      break;

    default :

      $show_btn = false;
      break;

  }

  //
  // TARGET
  //

  if ( get_sub_field ( 'target' ) != 'default' ) {
    $button['target'] = get_sub_field ( 'target' );
  }

  //
  // ID
  //

  if ( get_sub_field ( 'button_id' ) != '' ) {
    $button['id'] = get_sub_field ( 'button_id' );
  }

  //
  // CLASS
  //

  if ( get_sub_field ( 'classes' ) != '' ) {
    $button['class'][] = get_sub_field ( 'classes' );
  }

  //
  // TEXT
  // override the default if the text field has any value
  //

  if ( get_sub_field ( 'text' ) != '' ) {
    $button['text'] = get_sub_field ( 'text' );
  }

  // wrap in .btn-text span

  $button['text'] = '<span class="btn-text">' . $button['text'] . '</span>';

  //
  // ICON
  //

  if ( have_rows ( 'icon' ) ) {
    while ( have_rows ( 'icon' ) ) {
      the_row();

      if ( get_sub_field ( 'placement' ) != 'none' ) {

        $button['class'][] = 'has-icon';
        $button['icon'] = '<i class="icon ' . get_sub_field ( 'icon' ) . '"></i>';

        if ( get_sub_field ( 'placement' ) == 'before' ) {

          $button['class'][] = 'icon-before';
          $button['text'] = $button['icon'] . $button['text'];

        } elseif ( get_sub_field ( 'placement' ) == 'after' ) {

          $button['class'][] = 'icon-after';
          $button['text'] .= $button['icon'];

        }

      }

    }
  }

  //
  // COLOURS
  //

  if ( have_rows ( 'colours' ) ) {
    while ( have_rows ( 'colours' ) ) {
      the_row();

      if ( get_sub_field ( 'btn_bg' ) != '' ) {
        $button['class'][] = 'btn-' . get_sub_field ( 'btn_bg' );
      }

      if ( get_sub_field ( 'btn_text' ) != '' ) {
        $button['class'][] = 'text-' . get_sub_field ( 'btn_text' );
      }

      if ( get_sub_field ( 'btn_border' ) != '' ) {
        $button['class'][] = 'btn-outline-' . get_sub_field ( 'btn_border' );
      }

    }
  }

  if ( $show_btn == true ) {

    echo '<a href="' . $button['href'] . '"';

    echo ( isset ( $button['id'] ) ) ? ' id="' . $button['id'] . '"' : '';

    echo ( $button['target'] != '' ) ? ' target="' . $button['target'] . '"' : '';

    echo ' class="' . implode ( ' ', $button['class'] ) . '"';

    echo '>' . $button['text'] . '</a>';

  }
