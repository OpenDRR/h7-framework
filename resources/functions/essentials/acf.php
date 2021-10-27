<?php

//
// OPTIONS PAGE HOOKS
//

function update_general_options ( $post_id ) {

	$screen = get_current_screen();

  // print_r($screen);

  if ( strpos ( $screen->id, 'acf-options-component-settings' ) !== false ) {

    // echo '<pre>';

    // get highcharts options JS

		$highcharts_options = array();

		if ( locate_template ( 'resources/json/highcharts.json' ) != '' ) {

	    $highcharts_filename = get_bloginfo ( 'template_directory') . '/resources/json/highcharts.json';

	    $highcharts_json = file_get_contents ( $highcharts_filename );
	    $highcharts_options = json_decode ( $highcharts_json, true );

		}

    // echo 'current:<br>';
    // print_r($highcharts_options);
    // echo '<hr>';
    //
    // echo 'new:';
    // print_r(get_field('chart_options', 'option'));
    // echo '<hr>';

    // print_r($_POST);
    // echo '<hr>';

    $groups = array ( 'chart', 'title', 'subtitle', 'legend', 'rangeSelector' );

    while ( have_rows ( 'chart_options', 'option' ) ) {
      the_row();

      foreach ( $groups as $group ) {

        $group_fields = get_sub_field ( $group );

        foreach ( $group_fields as $key => $value ) {

          // echo $key . ': ';
          // print_r($value);
          // echo '<hr>';

          if ( $key == 'style' ) {

            if ( is_array ( $value ) && !empty ( $value ) ) {

              foreach ( $value as $style_rule ) {
                $highcharts_options[$group]['style'][$style_rule['rule']] = $style_rule['value'];
              }

            }

          } else {

            if ( $key == 'backgroundColor' && $value == '' ) {
              $value = 'transparent';
            }

            // echo $group . ', ' . $key . ', ' . $value . '<br>';

            if ( $value == 'true' ) {
              $highcharts_options[$group][$key] = true;
            } elseif ( $value == 'false' ) {
              $highcharts_options[$group][$key] = false;
            } else {
              $highcharts_options[$group][$key] = $value;
            }

            // echo $group . '<br>';
            // print_r($highcharts_options[$group]);
            // echo '<hr>';
          }

        }

      }

    }

    // echo 'updated:<br>';

    // var_dump($highcharts_options);

    // echo 'test1';

    file_put_contents ( get_template_directory() . '/resources/json/highcharts.json', json_encode ( $highcharts_options ) );

    // echo 'test2';


	}


}

add_action ( 'acf/save_post', 'update_general_options', 20 );

//
// PAGE LEVEL RULE
//

add_filter('acf/location/rule_types', 'acf_location_rules_page_level');
function acf_location_rules_page_level($choices) {
	$choices['Page']['page_level'] = 'Page Level';
	return $choices;
}

add_filter('acf/location/rule_operators', 'acf_location_rules_page_level_operators');
function acf_location_rules_page_level_operators($choices) {
	// remove operators that you do not need
	$new_choices = array(
		'<' => 'is less than',
		'<=' => 'is less than or equal to',
		'>=' => 'is greater than or equal to',
		'>' => 'is greater than'
	);
	foreach ($new_choices as $key => $value) {
		$choices[$key] = $value;
	}
	return $choices;
}

add_filter('acf/location/rule_values/page_level', 'acf_location_rules_values_page_level');
function acf_location_rules_values_page_level($choices) {
	// adjust the for loop to the number of levels you need
	for($i=1; $i<=10; $i++) {
		$choices[$i] = $i;
	}
	return $choices;
}

add_filter('acf/location/rule_match/page_level', 'acf_location_rules_match_page_level', 10, 3);
function acf_location_rules_match_page_level($match, $rule, $options) {
	if (!isset($options['post_id'])) {
		return $match;
	}
	$post_type = get_post_type($options['post_id']);
	$page_parent = 0;


	if (!isset ( $options['page_parent'] ) ) {
		$post = get_post($options['post_id']);
		$page_parent = $post->post_parent;
	} else {
		$page_parent = $options['page_parent'];
	}
	if (!$page_parent) {
		$page_level = 1;
	} else {
		$ancestors = get_ancestors($page_parent, $post_type);
		$page_level = count($ancestors) + 2;
	}
	$operator = $rule['operator'];
	$value = $rule['value'];
	switch ($operator) {
		case '==':
			$match = ($page_level == $value);
			break;
		case '!=':
			$match = ($page_level != $value);
			break;
		case '<':
			$match = ($page_level < $value);
			break;
		case '<=':
			$match = ($page_level <= $value);
			break;
		case '>=':
			$match = ($page_level >= $value);
			break;
		case '>':
			$match = ($page_level > $value);
			break;
	} // end switch
	return $match;
}

//
// QUERY REPEATER VALUE
//

/*
function box_posts_where ( $where ) {

	$where = str_replace ( "meta_key = 'content_$", "meta_key LIKE 'content_%", $where );

	return $where;

}

add_filter('posts_where', 'box_posts_where');
*/

//
// LIMIT POST OBJECT FIELD TO PARENTS
//

/*
function limit_to_parents ( $args, $field, $post ) {
  $args['post_parent'] = 0;
  return $args;
}

add_filter ( 'acf/fields/post_object/query/key=field_5b4f5c056cb46', 'limit_to_parents', 10, 3 );
*/

//
// INCLUDE FIELD VALUES IN SEARCH RESULTS
//

// Make the search to index custom
/**
 * Extend WordPress search to include custom fields
 * http://adambalee.com
 *
 * Join posts and postmeta tables
 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_join
 */

function cf_search_join( $join ) {
    global $wpdb;
    if ( is_search() ) {
        $join .=' LEFT JOIN '.$wpdb->postmeta. ' ON '. $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
    }
    return $join;
}
add_filter('posts_join', 'cf_search_join' );

/**
 * Modify the search query with posts_where
 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_where
 */
function cf_search_where( $where ) {
    global $pagenow, $wpdb;
    if ( is_search() ) {
        $where = preg_replace(
            "/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
            "(".$wpdb->posts.".post_title LIKE $1) OR (".$wpdb->postmeta.".meta_value LIKE $1)", $where );
    }
    return $where;
}
add_filter( 'posts_where', 'cf_search_where' );

/**
 * Prevent duplicates
 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_distinct
 */
function cf_search_distinct( $where ) {
    global $wpdb;
    if ( is_search() ) {
        return "DISTINCT";
    }
    return $where;
}
add_filter( 'posts_distinct', 'cf_search_distinct' );

//
// CHOOSE MENU
//

function include_field_types_menu_chooser( $version ) {

	class acf_field_menu_chooser extends acf_field {


  	/*
  	*  __construct
  	*
  	*  This function will setup the field type data
  	*
  	*  @type	function
  	*  @date	5/03/2014
  	*  @since	5.0.0
  	*
  	*  @param	n/a
  	*  @return	n/a
  	*/

  	function __construct() {

  		/*
  		*  name (string) Single word, no spaces. Underscores allowed
  		*/

  		$this->name = 'menu-chooser';


  		/*
  		*  label (string) Multiple words, can include spaces, visible when selecting a field type
  		*/

  		$this->label = __('Menu Chooser', 'acf-menu-chooser');


  		/*
  		*  category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
  		*/

          $this->category = 'choice';


  		/*
  		*  defaults (array) Array of default settings which are merged into the field object. These are used later in settings
  		*/

  		$this->defaults = array();


  		/*
  		*  l10n (array) Array of strings that are used in JavaScript. This allows JS strings to be translated in PHP and loaded via:
  		*  var message = acf._e('menu-chooser', 'error');
  		*/

  		$this->l10n = array(
  			'error'	=> __('Error! Please enter a higher value', 'acf-menu-chooser'),
  		);


  		// do not delete!
      	parent::__construct();

  	}



  	function render_field_settings( $field ) {

  		//Noting

  	}



  	function render_field( $field ) {

  		$field_value = $field['value'];


  		$field['choices'] = array();
  		$menus = wp_get_nav_menus();

  		echo '<select name="' . $field['name'] . '" class="acf-menu-chooser">';

  				if ( ! empty( $menus ) ) {
  					foreach ( $menus as $choice ) {
  						$field['choices'][ $choice->menu_id ] = $choice->term_id;
  						$field['choices'][ $choice->name ] = $choice->name;

  						echo '<option  value="' . $field['choices'][ $choice->menu_id ] . '" ' . selected($field_value, $field['choices'][ $choice->menu_id ], false) . ' >' . $field['choices'][ $choice->name ] . '</option>' ;
  					}
  				}
  		echo '</select>';

  	}

  }

  // create field
  new acf_field_menu_chooser();

}

add_action ( 'acf/include_field_types', 'include_field_types_menu_chooser' );

//
// PRE-POPULATE COLOUR SELECTS
//

function acf_load_color_field_choices ( $field ) {

  if ( get_field ( 'colour_options', 'option' ) != '' ) {

    $field['choices'] = array();

    $choices = get_field ( 'colour_options', 'option', false);

    // explode the value so that each line is a new array piece
    $choices = explode ( "\n", $choices );

    // remove any unwanted white space
    $choices = array_map ( 'trim', $choices );

    // loop through array and add to field 'choices'

    $field['choices']['inherit'] = 'Inherited from Parent';

    if ( is_array ( $choices ) ) {

      foreach ( $choices as $choice ) {

        $choice_array = explode ( ' : ', $choice );

        $field['choices'][$choice_array[0]] = $choice_array[1];

      }

    }

    // $field['default_value'] = 'primary';

		// echo '<pre>';
		// print_r($field);
		// echo "\n\n\n";
		// echo '</pre>';

    return $field;

  }

}

// add to fields

$colour_fields = array (
  'colour',
  'heading_colour',
  'text_colour',
  'link_colour',
  'social_menu_bg',
  'social_menu_text',
  'social_menu_link',
  'social_menu_icon',
  'btn_bg',
  'btn_text',
  'btn_border'
);

foreach ( $colour_fields as $colour_field ) {
  // add_filter ( 'acf/load_field/name=' . $colour_field, 'acf_load_color_field_choices' );
}


//
// ADD EDIT LINK TO TEMPLATE ELEMENTS
//

function template_field_add_edit_link ( $field ) {
	if (
		$field['type'] == 'post_object' &&
		!empty ( $field['value'] )
	) {
		echo '<a href="' . get_edit_post_link ( $field['value'] ) . '" target="_blank" style="display: inline-flex; align-items: center; margin-top: 1em; font-size: 0.75em; text-decoration: none;"><span class="dashicons-before dashicons-edit" style="margin-right: 0.25em;"></span>Edit this template</a>';
	}
}

add_action (
	'acf/render_field/key=builder_layout_template_post',
	'template_field_add_edit_link'
);
