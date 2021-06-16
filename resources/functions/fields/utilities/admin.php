<?php

//
// TEMPLATE
//

$GLOBALS['fw_fields']['admin']['template_settings'] = array (
	'settings' => array (
		'title' => 'Template Settings'
	),
	'field_group' => array(
		'key' => 'admin_template',
		'title' => 'Template Settings',
		'fields' => array(
			array(
				'key' => 'admin_template_insertable',
				'label' => 'Insertable',
				'name' => 'template_insertable',
				'type' => 'true_false',
				'instructions' => 'Allow this template\'s fields to be inserted into other posts and pages.',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'message' => '',
				'default_value' => 0,
				'ui' => 1,
				'ui_on_text' => '',
				'ui_off_text' => '',
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'template',
				),
			),
		),
		'menu_order' => 11,
		'position' => 'side',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
	)
);

//
// LAYOUT
//

$GLOBALS['fw_fields']['admin']['layout'] = array (
	'settings' => array (
		'title' => 'Layout'
	),
	'field_group' => array(
		'key' => 'admin_layout',
		'title' => 'Layout',
		'fields' => array(
			array(
				'key' => 'admin_layout_files',
				'label' => 'Theme Files',
				'name' => 'layout_file',
				'type' => 'checkbox',
				'instructions' => 'Select which theme files will use this layout.',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => 'layout-theme-files',
					'id' => '',
				),
				'choices' => array(
					'index' => 'Default (index.php)',
					'page' => 'Page (page.php)',
					'front-page' => 'Home (front-page.php)',
					'single' => 'Single Post (single.php)',
					'archive' => 'Archive (archive.php)',
					'search' => 'Search Results (search.php)',
					'404' => 'Error page (404.php)',
				),
				'allow_custom' => 1,
				'save_custom' => 0,
				'default_value' => array(
				),
				'layout' => 'vertical',
				'toggle' => 0,
				'return_format' => 'value',
			),
			array(
				'key' => 'admin_layout_builder',
				'label' => 'Layout',
				'name' => 'layout_builder',
				'type' => 'flexible_content',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'layouts' => array(
					'admin_layout_builder_template' => array(
						'key' => 'admin_layout_builder_template',
						'name' => 'template',
						'label' => 'Template',
						'display' => 'block',
						'sub_fields' => array(
							array(
								'key' => 'field_60c76ee7b4cf6',
								'label' => 'Template',
								'name' => 'template',
								'type' => 'post_object',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array(
									'width' => '',
									'class' => '',
									'id' => '',
								),
								'post_type' => array(
									0 => 'template',
								),
								'taxonomy' => '',
								'allow_null' => 0,
								'multiple' => 0,
								'return_format' => 'id',
								'ui' => 1,
							),
						),
						'min' => '',
						'max' => '',
					),
					'admin_layout_builder_loop' => array(
						'key' => 'admin_layout_builder_loop',
						'name' => 'content',
						'label' => 'Page Content',
						'display' => 'block',
						'sub_fields' => array(
							array(
								'key' => 'field_60c76f4cb4cf9',
								'label' => 'Loop',
								'name' => '',
								'type' => 'message',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array(
									'width' => '',
									'class' => '',
									'id' => '',
								),
								'message' => 'This is where the main page content loop will be inserted.',
								'new_lines' => 'wpautop',
								'esc_html' => 0,
							),
						),
						'min' => '',
						'max' => '1',
					),
				),
				'button_label' => 'Add Element',
				'min' => '',
				'max' => '',
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'layout',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'seamless',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
	)
);
