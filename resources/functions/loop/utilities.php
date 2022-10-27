<?php

//
// CURRENT ELEMENT TYPE
//
//

function get_current_element_type() {
	// print_r($GLOBALS['elements']['current']['type']);

  return $GLOBALS['elements']['current']['type'];
}

//
// CURRENT ELEMENT ID
// less ugly way of getting the ID of the current element from the globals var
//

function get_current_element_ID() {
	
	if ( isset ( $GLOBALS['elements']['current']['id'] ) ) {
		return $GLOBALS['elements']['current']['id'];
	} else {
		return 'element';
	}
	
}

//
// ELEMENT DEFAULTS
// given an element object, get the default settings from the globals var
//

function get_element_defaults ( $element ) {

	// print_r($element);

  $element_key = $element['type'];

  // if ( $element['type'] == 'block' ) {
  //   $element_key = $element['block']['content'];
  // }

  if ( !isset ( $element_defaults[$element_key] ) ) {
    $element_defaults[$element_key] = get_field ( $element_key . '_defaults', 'option' );
  }

  return $element_defaults[$element_key];

}

// GENERATE CSS

function generate_css ( $field, $element_ID ) {

  // echo $element_ID . ': <br>';
  // print_r($field);

  if (
    isset ( $field['text_colour'] ) &&
    $field['text_colour'] != '' &&
    $field['text_colour'] != 'inherit'
  ) {

    $GLOBALS['css'] .= "\n" . $element_ID . ' { color: var(--' . $field['text_colour'] . '); }';

  }

  if (
    isset ( $field['heading_colour'] ) &&
    $field['heading_colour'] != '' &&
    $field['heading_colour'] != 'inherit'
  ) {

    $GLOBALS['css'] .= "\n" . $element_ID . ' h1, ' . $element_ID . ' h2, ' . $element_ID . ' h3, ' . $element_ID . ' h4, ' . $element_ID . ' h5, ' . $element_ID . ' h6 { color: var(--' . $field['heading_colour'] . '); }';
  }

  if (
    isset ( $field['link_colour'] ) &&
    $field['link_colour'] != '' &&
    $field['link_colour'] != 'inherit'
  ) {

    $GLOBALS['css'] .= "\n" . $element_ID . ' a { color: var(--' . $field['link_colour'] . '); }';

  }

}

//
// FIELD DEFAULTS
//

function get_field_defaults ( $field ) {

  global $block_names;
  global $field_names;
  global $clones;
  global $block_names_by_key;

  // if __key is set it's a clone

  if ( isset ( $field['__key'] ) ) {
    $this_key = $field['__key'];

    // convert the field key to an array and use the first element
    // to identify the block's key
    // i.e. 5d261b80f4347 => jumbotron

    $field_key = explode ( '_', str_replace ( 'field_', '', $field['key'] ) );
    $block_key = $field_key[0];

    $defaults_field_name = $block_names_by_key[$field_key[0]] . '_defaults';

  } elseif ( isset ( $field['key'] ) ) {

    $this_key = $field['key'];
    $field_key = $this_key;

    $defaults_field_name = 'buttons_defaults';

  }

  $this_value = $field['value'];

  if ( $this_value == '' || empty ( $this_value ) ) {

    // go to the block defaults field from the theme settings page
    // i.e. jumbotron_defaults

    if ( have_rows ( $defaults_field_name, 'option' ) ) {
      while ( have_rows ( $defaults_field_name, 'option' ) ) {
        the_row();

        // run through the field_names array (i.e. colours, background)

        foreach ( $field_names as $group_field => $sub_fields ) {

          // find the sub-group in the x_defaults field

          if ( have_rows ( $group_field ) ) {
            while ( have_rows ( $group_field ) ) {
              the_row();

              foreach ( $sub_fields as $sub_field => $field_key ) {

                if ( 'field_' . $field_key == $this_key ) {

                  if (
                    ( $this_value == '' || empty ( $this_value ) ) &&
                    get_sub_field ( $sub_field ) != ''
                  ) {

                    $this_value = get_sub_field ( $sub_field );

                  }

                }

              }

            }
          }

        }

        // clones

        foreach ( $clones as $group_field => $sub_fields ) {

          // find the sub-group in the x_defaults field

          if ( have_rows ( $group_field ) ) {
            while ( have_rows ( $group_field ) ) {
              the_row();

              foreach ( $sub_fields as $sub_field => $field_key ) {

                if ( 'field_' . $field_key == $this_key ) {

                  if (
                    ( $this_value == '' || empty ( $this_value ) ) &&
                    get_sub_field ( $sub_field ) != ''
                  ) {

                    $this_value = get_sub_field ( $sub_field );

                  }

                }

              }

            }
          }

        }

      }
    }

    $field['value'] = $this_value;

  }

  return $field;

}
