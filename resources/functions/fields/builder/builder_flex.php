<?php

//
// BLOCK - CONTENT
//

$GLOBALS['fw_fields']['builder_flex']['block_content'] = array(
	'key' => 'builder_block_content',
	'title' => 'Block Type — Content',
	'fields' => array(
		array(
			'key' => 'field_5dbc5d79be45e',
			'label' => 'Block Content',
			'name' => 'blocks',
			'type' => 'flexible_content',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => 'blocks-loop-field hide-label',
				'id' => '',
			),
			'layouts' => array(),
			'button_label' => 'Add Block',
			'min' => 1,
			'max' => 1,
		),
	),
	'active' => false,
);

//
// BLOCK - NAVIGATION
//

$GLOBALS['fw_fields']['builder_flex']['block_navigation'] = array(
	'key' => 'builder_block_navigation',
	'title' => 'Block Type — Navigation',
	'fields' => array(
		array(
			'key' => 'field_5dc4423f87c9b',
			'label' => 'Navigation',
			'name' => 'blocks',
			'type' => 'flexible_content',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => 'blocks-loop-field hide-label',
				'id' => '',
			),
			'layouts' => array(),
			'button_label' => 'Add Block',
			'min' => 1,
			'max' => 1,
		),
	),
	'active' => false,
);

// echo '<pre style="font-size: 9px;">';

// 2. filter
$GLOBALS['fw_fields']['builder_flex'] = apply_filters ( 'custom_builder_flex', $GLOBALS['fw_fields']['builder_flex'] );


//
// POPULATE LAYOUTS
// builder_groups -> builder_flex
// add the content fields from 'builder_groups' to the flex field's layouts array
//

foreach ( $GLOBALS['fw_fields']['builder_groups'] as $key => $type ) {

	foreach ( $type as $field_group_name => $block ) {

		$GLOBALS['fw_fields']['builder_flex'][$key]['fields'][0]['layouts'][$block['field_group']['key'] . '_layout'] = array(
			'key' => $block['field_group']['key'] . '_layout',
			'name' => $field_group_name,
			'label' => $block['field_group']['title'],
			'display' => 'block',
			'sub_fields' => array(
				array(
					'key' => $block['field_group']['key'] . '_clone',
					'label' => $block['field_group']['title'],
					'name' => $field_group_name,
					'type' => 'clone',
					'clone' => array(
						0 => $block['field_group']['key']
					),
					'display' => 'seamless',
					'layout' => 'block',
				),
			),
		);

	}


}
