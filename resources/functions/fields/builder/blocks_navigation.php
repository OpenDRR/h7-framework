<?php

//
// GROUPS
//

$GLOBALS['fw_fields']['builder_groups']['block_navigation']['menu'] = array(
	'settings' => array (
		'title' => 'Navigation — Menu'
	),
	'field_group' => array (
		'key' => 'builder_block_navigation_menu',
		'title' => 'Menu',
		'fields' => array(
			array(
				'key' => 'field_5c475d2ee2c94',
				'label' => 'Menu Type',
				'name' => 'type',
				'type' => 'select',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'choices' => array(
					'wp' => 'Wordpress menu',
					'manual' => 'Add items manually',
					'hierarchy' => 'Current page hierarchy',
				),
				'default_value' => false,
				'allow_null' => 0,
				'multiple' => 0,
				'ui' => 0,
				'return_format' => 'value',
				'ajax' => 0,
				'placeholder' => '',
			),
			array(
				'key' => 'field_5c475d86e2c95',
				'label' => 'Menu',
				'name' => 'menu',
				'type' => 'menu-chooser',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_5c475d2ee2c94',
							'operator' => '==',
							'value' => 'wp',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
			),
			array(
				'key' => 'field_5ae21ed07f151',
				'label' => 'Menu Items',
				'name' => 'items',
				'type' => 'repeater',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_5c475d2ee2c94',
							'operator' => '==',
							'value' => 'manual',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'collapsed' => 'field_5ae21eda7f152',
				'min' => 0,
				'max' => 0,
				'layout' => 'block',
				'button_label' => 'Add Item',
				'sub_fields' => array(
					array(
						'key' => 'field_5ae21eda7f152',
						'label' => 'Type',
						'name' => 'type',
						'type' => 'select',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '25',
							'class' => '',
							'id' => '',
						),
						'choices' => array(
							'post' => 'Post / Page',
							'url' => 'URL',
							'divider' => 'Divider',
						),
						'default_value' => false,
						'allow_null' => 0,
						'multiple' => 0,
						'ui' => 0,
						'return_format' => 'value',
						'ajax' => 0,
						'placeholder' => '',
					),
					array(
						'key' => 'field_5ae21f1c7f153',
						'label' => 'Post',
						'name' => 'post',
						'type' => 'post_object',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => array(
							array(
								array(
									'field' => 'field_5ae21eda7f152',
									'operator' => '==',
									'value' => 'post',
								),
							),
						),
						'wrapper' => array(
							'width' => '50',
							'class' => '',
							'id' => '',
						),
						'post_type' => array(
							0 => 'post',
							1 => 'page',
						),
						'taxonomy' => array(
						),
						'allow_null' => 0,
						'multiple' => 0,
						'return_format' => 'id',
						'ui' => 1,
					),
					array(
						'key' => 'field_5c47630238098',
						'label' => 'URL',
						'name' => 'url',
						'type' => 'url',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => array(
							array(
								array(
									'field' => 'field_5ae21eda7f152',
									'operator' => '==',
									'value' => 'url',
								),
							),
						),
						'wrapper' => array(
							'width' => '50',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
					),
					array(
						'key' => 'field_5c47637d62679',
						'label' => 'Link Text',
						'name' => 'text',
						'type' => 'text',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '25',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
					),
					array(
						'key' => 'field_5ae21f5a7f155',
						'label' => 'Hierarchy',
						'name' => 'hierarchy',
						'type' => 'radio',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '25',
							'class' => '',
							'id' => '',
						),
						'choices' => array(
							'parent' => 'Parent',
							'child' => 'Child',
						),
						'allow_null' => 0,
						'other_choice' => 0,
						'save_other_choice' => 0,
						'default_value' => '',
						'layout' => 'horizontal',
						'return_format' => 'value',
					),
					array(
						'key' => 'field_5ae21f407f154',
						'label' => 'Class',
						'name' => 'class',
						'type' => 'text',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => array(
							array(
								array(
									'field' => 'field_5ae21eda7f152',
									'operator' => '!=',
									'value' => 'divider',
								),
							),
						),
						'wrapper' => array(
							'width' => '25',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
					),
					array(
						'key' => 'field_5ae2207a257a4',
						'label' => 'Target',
						'name' => 'target',
						'type' => 'select',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => array(
							array(
								array(
									'field' => 'field_5ae21eda7f152',
									'operator' => '!=',
									'value' => 'divider',
								),
							),
						),
						'wrapper' => array(
							'width' => '25',
							'class' => '',
							'id' => '',
						),
						'choices' => array(
							'same' => 'Same window',
							'blank' => 'New window',
						),
						'default_value' => false,
						'allow_null' => 0,
						'multiple' => 0,
						'ui' => 0,
						'ajax' => 0,
						'return_format' => 'value',
						'placeholder' => '',
					),
				),
			),
			array(
				'key' => 'field_5d63f306ba9dc',
				'label' => 'Display',
				'name' => '',
				'type' => 'accordion',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'open' => 0,
				'multi_expand' => 0,
				'endpoint' => 0,
			),
			array(
				'key' => 'field_5ec7cb34ccb84',
				'label' => 'Dropdowns',
				'name' => 'dropdowns',
				'type' => 'true_false',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '25',
					'class' => '',
					'id' => '',
				),
				'message' => '',
				'default_value' => 0,
				'ui' => 1,
				'ui_on_text' => '',
				'ui_off_text' => '',
			),
			array(
				'key' => 'field_5d63f316ba9dd',
				'label' => 'List Class',
				'name' => 'list_class',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '25',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
			),
			array(
				'key' => 'field_5d63f326ba9de',
				'label' => 'Item Class',
				'name' => 'item_class',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '25',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
			),
			array(
				'key' => 'field_5d6531147a28d',
				'label' => 'Link Class',
				'name' => 'link_class',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '25',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
			),
		),
		'active' => false,
	)
);

$GLOBALS['fw_fields']['builder_groups']['block_navigation']['buttons'] = array(
	'settings' => array(
		'title' => 'Navigation — Button Group'
	),
	'field_group' => array (
		'key' => 'builder_block_navigation_buttons',
		'title' => 'Button Group',
		'fields' => array(
			array(
				'key' => 'field_5f049db52aefb',
				'label' => 'Buttons',
				'name' => 'buttons',
				'type' => 'repeater',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'collapsed' => '',
				'min' => 0,
				'max' => 0,
				'layout' => 'block',
				'button_label' => 'Add Button',
				'sub_fields' => array(
					array(
						'key' => 'field_5f049de94cd81',
						'label' => 'Button',
						'name' => 'button',
						'type' => 'clone',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'clone' => array(
							0 => 'builder_element_button',
						),
						'display' => 'seamless',
						'layout' => 'block',
						'prefix_label' => 0,
						'prefix_name' => 0,
					),
				),
			),
			array(
				'key' => 'field_5d5d57fcd78b1',
				'label' => 'Display',
				'name' => '',
				'type' => 'accordion',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'open' => 0,
				'multi_expand' => 0,
				'endpoint' => 0,
			),
			array(
				'key' => 'field_5d5d580bd78b2',
				'label' => 'Button Size',
				'name' => 'btn_size',
				'type' => 'select',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '33',
					'class' => '',
					'id' => '',
				),
				'choices' => array(
					'default' => 'Default',
					'sm' => 'Small',
					'lg' => 'Large',
				),
				'default_value' => 'default',
				'allow_null' => 0,
				'multiple' => 0,
				'ui' => 0,
				'return_format' => 'value',
				'ajax' => 0,
				'placeholder' => '',
			),
			array(
				'key' => 'field_5d5d5857d78b4',
				'label' => 'Additional CSS Classes',
				'name' => 'btn_classes',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '66',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
			),
		),
		'active' => false,
	)
);

$GLOBALS['fw_fields']['builder_groups']['block_navigation']['scroll_progress'] = array(
	'settings' => array(
		'title' => 'Navigation — Scroll Progress'
	),
	'field_group' => array (
		'key' => 'builder_block_navigation_scrollprogress',
		'title' => 'Scroll Progress',
		'fields' => array(
			array(
				'key' => 'field_5d2f5a96e865e',
				'label' => 'Section Selector',
				'name' => 'section_selector',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '.fw-page-section',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
			),
			array(
				'key' => 'field_5d2f5bf34f059',
				'label' => 'Heading Selector',
				'name' => 'head_selector',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => 'h2',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
			),
			array(
				'key' => 'field_5d31e56ed1460',
				'label' => 'Style',
				'name' => 'style',
				'type' => 'select',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'choices' => array(
					'bar' => 'Bar',
					'circle' => 'Circle',
				),
				'default_value' => 'bar',
				'allow_null' => 0,
				'multiple' => 0,
				'ui' => 0,
				'return_format' => 'value',
				'ajax' => 0,
				'placeholder' => '',
			),
		),
		'active' => false,
	)
);

$GLOBALS['fw_fields']['builder_groups']['block_navigation']['next'] = array(
	'settings' => array(
		'title' => 'Navigation — Next Page'
	),
	'field_group' => array (
		'key' => 'builder_block_navigation_next',
		'title' => 'Next Page',
		'fields' => array(
			array(
				'key' => 'field_5d5570b98d5b5',
				'label' => 'Link',
				'name' => 'link',
				'type' => 'select',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'choices' => array(
					'menu' => 'Find next page in order',
					'manual' => 'Select page manually',
				),
				'default_value' => false,
				'allow_null' => 0,
				'multiple' => 0,
				'ui' => 0,
				'return_format' => 'value',
				'ajax' => 0,
				'placeholder' => '',
			),
			array(
				'key' => 'field_5dbb307f4f5a5',
				'label' => 'Exclude',
				'name' => 'exclude',
				'type' => 'post_object',
				'instructions' => 'Select posts to skip when querying for the next page.',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_5d5570b98d5b5',
							'operator' => '==',
							'value' => 'menu',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'post_type' => array(
					0 => 'page',
				),
				'taxonomy' => '',
				'allow_null' => 0,
				'multiple' => 1,
				'return_format' => 'id',
				'ui' => 1,
			),
		),
		'active' => false,
	)
);

$GLOBALS['fw_fields']['builder_groups']['block_navigation']['social'] = array(
	'settings' => array(
		'title' => 'Navigation — Social Widget'
	),
		'field_group' => array (
		'key' => 'builder_block_navigation_social',
		'title' => 'Social Widget',
		'fields' => array(
			array(
				'key' => 'field_5d35f359aaa01',
				'label' => 'Widget Type',
				'name' => 'type',
				'type' => 'button_group',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '25',
					'class' => '',
					'id' => '',
				),
				'choices' => array(
					'follow' => 'Follow',
					'share' => 'Share',
				),
				'allow_null' => 0,
				'default_value' => '',
				'layout' => 'horizontal',
				'return_format' => 'value',
			),
			array(
				'key' => 'field_5da71a221e5e2',
				'label' => 'Display',
				'name' => 'display',
				'type' => 'select',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '75',
					'class' => '',
					'id' => '',
				),
				'choices' => array(
					'dropdown' => 'Dropdown',
					'list' => 'List',
					'icons' => 'Icons',
				),
				'default_value' => false,
				'allow_null' => 0,
				'multiple' => 0,
				'ui' => 0,
				'return_format' => 'value',
				'ajax' => 0,
				'placeholder' => '',
			),
			array(
				'key' => 'field_5d35f320631c9',
				'label' => 'Shareable URL',
				'name' => 'url',
				'type' => 'select',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_5d35f359aaa01',
							'operator' => '==',
							'value' => 'share',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'choices' => array(
					'site' => 'Site URL',
					'page' => 'Current page URL',
					'section' => 'Current section ID',
				),
				'default_value' => false,
				'allow_null' => 0,
				'multiple' => 0,
				'ui' => 0,
				'return_format' => 'value',
				'ajax' => 0,
				'placeholder' => '',
			),
			array(
				'key' => 'field_5d5ab5bb5c648',
				'label' => 'Menu Background',
				'name' => 'social_menu_bg',
				'type' => 'select',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '25',
					'class' => '',
					'id' => '',
				),
				'choices' => array_merge ( array ( 'inherit' => 'Inherited from Parent' ), $GLOBALS['defaults']['theme_colours'] ),
				'default_value' => false,
				'allow_null' => 0,
				'multiple' => 0,
				'ui' => 0,
				'return_format' => 'value',
				'ajax' => 0,
				'placeholder' => '',
			),
			array(
				'key' => 'field_5d5ab5dc5c649',
				'label' => 'Menu Text Colour',
				'name' => 'social_menu_text',
				'type' => 'select',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '25',
					'class' => '',
					'id' => '',
				),
				'choices' => array_merge ( array ( 'inherit' => 'Inherited from Parent' ), $GLOBALS['defaults']['theme_colours'] ),
				'default_value' => false,
				'allow_null' => 0,
				'multiple' => 0,
				'ui' => 0,
				'return_format' => 'value',
				'ajax' => 0,
				'placeholder' => '',
			),
			array(
				'key' => 'field_5d5ab5f25c64a',
				'label' => 'Menu Link Colour',
				'name' => 'social_menu_link',
				'type' => 'select',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '25',
					'class' => '',
					'id' => '',
				),
				'choices' => array_merge ( array ( 'inherit' => 'Inherited from Parent' ), $GLOBALS['defaults']['theme_colours'] ),
				'default_value' => false,
				'allow_null' => 0,
				'multiple' => 0,
				'ui' => 0,
				'return_format' => 'value',
				'ajax' => 0,
				'placeholder' => '',
			),
			array(
				'key' => 'field_5e5d5b348f9ae',
				'label' => 'Icon Colour',
				'name' => 'social_menu_icon',
				'type' => 'select',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '25',
					'class' => '',
					'id' => '',
				),
				'choices' => array_merge ( array ( 'inherit' => 'Inherited from Parent' ), $GLOBALS['defaults']['theme_colours'] ),
				'default_value' => false,
				'allow_null' => 0,
				'multiple' => 0,
				'ui' => 0,
				'return_format' => 'value',
				'ajax' => 0,
				'placeholder' => '',
			),
		),
		'active' => false,
	)
);

$GLOBALS['fw_fields']['builder_groups']['block_navigation']['breadcrumb'] = array(
	'settings' => array(
		'title' => 'Navigation — Breadcrumb'
	),
		'field_group' => array (
		'key' => 'builder_block_navigation_breadcrumb',
		'title' => 'Breadcrumb',
		'fields' => array(
			array(
				'key' => 'field_5e8c951b2b84d',
				'label' => 'Include Home',
				'name' => 'include_home',
				'type' => 'true_false',
				'instructions' => '',
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
		'active' => false,
	)
);

//
// FLEX
//
