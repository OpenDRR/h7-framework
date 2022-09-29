<?php

/*

  1. get_breakpoints() - generate a block's breakpoint classes

*/

//
// 1.
// GET BREAKPOINTS
//

function get_breakpoints ( $field, $block_type = null ) {
	
  $classes = array();

  if ( is_array ( $field ) ) {

    $block_hidden = false;

    foreach ( $field as $breakpoint => $values ) {

      $breakpoint_has_values = false;

      $breakpoint_classes = array();

      $breakpoint_name = '';
      if ( $breakpoint != 'xs' ) $breakpoint_name = $breakpoint . '-';

      if ( $values['hide'] == 1 ) {

        // the block is hidden at this breakpoint

        if ( $block_hidden == false ) {

          // the block was not previously hidden

          $breakpoint_classes[] = 'd-' . $breakpoint_name . 'none';
          $block_hidden = true;

        }

      } else {

        // this breakpoint is not set to hidden

        foreach ( $values as $key => $val ) {

          // skip the hide checkbox

          if ( $key != 'hide' ) {

            if ( $val != '' ) {

              // this key has a value

              $breakpoint_has_values = true;

              $breakpoint_classes[] = $key . '-' . $breakpoint_name . $val;

            }

          }

        }

        if ( $breakpoint_has_values == true ) {

          // this breakpoint has values

          if ( $block_hidden == true ) {

            // the block was previously hidden and needs to be shown again

            $classes[] = 'd-' . $breakpoint_name . 'block';

            $block_hidden = false;

          }

        }

      }

      $classes = array_merge ( $classes, $breakpoint_classes );

    }

  }

  return $classes;

}
