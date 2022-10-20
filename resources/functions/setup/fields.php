<?php

function fw_acf_fields_init() {
	
	if ( function_exists ( 'acf_add_local_field_group' ) ) {

		$GLOBALS['fw_fields'] = array (
			'defaults' => array(),					// default values i.e. colour choices
			'common' => array(),						// common field groups i.e. settings, functions, elements
			'builder_groups' => array(),		// inactive field groups to be used as clones in the builder flex i.e. heading, text
			'builder_flex' => array(),			// content flex fields to be used as clones in the page builder i.e. block (content), block (navigation)
			'builder' => array(),						// the main page builder field group
			'admin' => array()							// backend & admin utilities
		);

		//
		// DEFAULTS
		//

		include ( locate_template ( 'resources/functions/fields/utilities/defaults.php' ) );
		
		$GLOBALS['defaults']['theme_colours'] = array (
			'primary' => 'Primary',
			'secondary' => 'Secondary',
			'light' => 'Light',
			'dark' => 'Dark',
			'white' => 'White',
			'black' => 'Black',
			'body' => 'Body Colour',
			'transparent' => 'Transparent'
		);

		if ( get_field ( 'theme_colours', 'option' ) != '' ) {

			$GLOBALS['defaults']['theme_colours'] = array();

			$colours = explode ( "\n", get_field ( 'theme_colours', 'option' ) );

			foreach ( $colours as $choice ) {
				$GLOBALS['defaults']['theme_colours'][explode ( ' : ', $choice )[0]] = explode ( ' : ', $choice )[1];
			}

		}

		//
		// COMMON
		//

		// common elements i.e. background, button
		// populates ['common']['elements']
		include ( locate_template ( 'resources/functions/fields/utilities/elements.php' ) );

		// functions i.e. breakpoints, query builder
		// populates ['common']['function']
		include ( locate_template ( 'resources/functions/fields/utilities/functions.php' ) );

		// settings i.e. flexbox, spacing
		// populates ['common']['settings']
		include ( locate_template ( 'resources/functions/fields/builder/settings.php' ) );

		// admin
		// populates ['admin']
		include ( locate_template ( 'resources/functions/fields/utilities/admin.php' ) );

		// filter
		// $GLOBALS['fw_fields']['common'] = apply_filters ( 'custom_field_groups', $GLOBALS['fw_fields']['common'] );

		//
		// POPULATE BUILDER FIELD GROUPS
		// 1. include template files containing the field group arrays
		// 2. filter the arrays to get custom elements from the child theme or plugins
		//

		// BLOCKS

		include ( locate_template ( 'resources/functions/fields/builder/blocks_content.php' ) );
		include ( locate_template ( 'resources/functions/fields/builder/blocks_navigation.php' ) );

		$GLOBALS['fw_fields']['builder_groups'] = apply_filters ( 'custom_builder_groups', $GLOBALS['fw_fields']['builder_groups'] );

		// BUILDER FLEX
		include ( locate_template ( 'resources/functions/fields/builder/builder_flex.php' ) );

		// BUILDER
		include ( locate_template ( 'resources/functions/fields/builder/builder.php' ) );

		//
		// REGISTER FIELD GROUPS
		//

		// COMMON

		foreach ( $GLOBALS['fw_fields']['common'] as $key => $type ) {

			foreach ( $type as $name => $field_group ) {

				acf_add_local_field_group ( $field_group['field_group'] );

			}
		}

		// ADMIN

		foreach ( $GLOBALS['fw_fields']['admin'] as $key => $type ) {

			//echo $key . '<br>';

			foreach ( $type as $name => $field_group ) {

				//echo $name . '<br>';

				acf_add_local_field_group ( $field_group );

			}
		}

		// SETTINGS FLEX
		// after common elements are registered but before content flex

		acf_add_local_field_group ( $GLOBALS['fw_fields']['settings_flex']['field_group'] );

		// BUILDER GROUPS

		foreach ( $GLOBALS['fw_fields']['builder_groups'] as $key => $type ) {

			foreach ( $type as $name => $field_group ) {

				acf_add_local_field_group ( $field_group['field_group'] );

			}
		}

		//
		// BUILDER
		//

		// FLEX

		foreach ( $GLOBALS['fw_fields']['builder_flex'] as $key => $type ) {

			acf_add_local_field_group ( $type );

		}

		// FIELD GROUP

		acf_add_local_field_group ( $GLOBALS['fw_fields']['builder'] );

	}

	if ( !is_admin() ) {
		// echo '<pre style="font-size: 9px;">';
		//
		// // print_r($GLOBALS['fw_fields']['settings_flex']);
		//
		// 		echo '<hr>';
		// 		print_r(get_field_object('field_5dc1d75b83c2c'));
		//
		// echo '</pre>';
	}
}

add_action ( 'acf/init', 'fw_acf_fields_init' );