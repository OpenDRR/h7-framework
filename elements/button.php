<?php

  $show_btn = true;

  //
  // BUTTON ELEMENT
  //

  if ( !isset ( $button_element ) )
    $button_element = array();

  if ( !isset ( $button_element['href'] ) )
    $button_element['href'] = '#';

  if ( !isset ( $button_element['text'] ) )
    $button_element['text'] = 'Learn more';

  if ( !isset ( $button_element['target'] ) )
    $button_element['target'] = '';

  if ( !isset ( $button_element['class'] ) ) {
    $button_element['class'] = array ( 'btn' );
  } else {
    $button_element['class'][] = 'btn';
  }

  //
  // LINK TYPE
  //

  switch ( $button['type'] ) {

    case 'scroll' :

      if ( $button['id'] != '' ) {
        $button_element['href'] = '#' . $button['id'];
      } else {
        $button_element['class'][] = 'next-section';
      }

      break;

    case 'post' :
    case 'page' :

      if ( get_sub_field ( $button['type'] ) != '' ) {
        $button_element['href'] = get_permalink ( get_sub_field ( $button['type'] ) );
        $button_element['text'] = get_the_title ( get_sub_field ( $button['type'] ) );
      }

      break;

    case 'url' :

      $button_element['href'] = $button['url'];

      break;

    default :

      $show_btn = false;
      break;

  }

  //
  // TARGET
  //

  if ( $button['target'] != 'default' ) {
    $button_element['target'] = $button['target'];
  }

  //
  // ID
  //

  if ( $button['button_id'] != '' ) {
    $button_element['id'] = $button['button_id'];
  }

  //
  // CLASS
  //

  if ( $button['classes'] != '' ) {
    $button_element['class'][] = $button['classes'];
  }

  //
  // TEXT
  // override the default if the text field has any value
  //

  if ( $button['text'] != '' ) {
    $button_element['text'] = $button['text'];
  }

  // wrap in .btn-text span

  $button_element['text'] = '<span class="btn-text">' . $button_element['text'] . '</span>';

  //
  // ICON
  //

	if ( !empty ( $button['icon'] ) ) {
		
    if ( $button['icon']['placement'] != 'none' ) {

      $button_element['class'][] = 'has-icon';
      $button_element['icon'] = '<i class="icon ' . $button['icon']['icon'] . '"></i>';

      if ( $button['icon']['placement'] == 'before' ) {

        $button_element['class'][] = 'icon-before';
        $button_element['text'] = $button_element['icon'] . $button_element['text'];

      } elseif ( $button['icon']['placement'] == 'after' ) {

        $button_element['class'][] = 'icon-after';
        $button_element['text'] .= $button_element['icon'];

      }

    }

  }

  //
  // COLOURS
  //

	if ( !empty ( $button['colours'] ) ) {
		
    if ( $button['colours']['btn_bg'] != '' ) {
      $button_element['class'][] = 'btn-' . $button['colours']['btn_bg'];
    }

    if ( $button['colours']['btn_text'] != '' ) {
      $button_element['class'][] = 'text-' . $button['colours']['btn_text'];
    }

    if ( $button['colours']['btn_border'] != '' ) {
      $button_element['class'][] = 'btn-outline-' . $button['colours']['btn_border'];
    }

  
  }

  if ( $show_btn == true ) {

    echo '<a href="' . $button_element['href'] . '"';

    echo ( isset ( $button_element['id'] ) ) ? ' id="' . $button_element['id'] . '"' : '';

    echo ( $button_element['target'] != '' ) ? ' target="' . $button_element['target'] . '"' : '';

    echo ' class="' . implode ( ' ', $button_element['class'] ) . '"';

    echo '>' . $button_element['text'] . '</a>';

  }
