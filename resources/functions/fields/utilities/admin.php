<?php

//
// THEME SETUP
//

$GLOBALS['fw_fields']['admin']['status'] = array (
	'settings' => array (
		'title' => 'Theme Setup'
	),
	'field_group' => array (
		'key' => 'admin_setup',
		'title' => 'Theme Setup',
		'fields' => array(
			array(
				'key' => 'admin_setup_status',
				'label' => 'Theme Status',
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
				'message' => '',
				'new_lines' => 'wpautop',
				'esc_html' => 0,
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'acf-options-setup',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
		'show_in_rest' => 0,
	)
);

//
// COMPONENT SETTINGS
//

$GLOBALS['fw_fields']['admin']['components'] = array (
	'settings' => array (
		'title' => 'Component Settings'
	),
	'field_group' => array(
		'key' => 'admin_components',
		'title' => 'Component Settings',
		'fields' => array(
			array(
				'key' => 'admin_components_social',
				'label' => 'Social Options',
				'name' => 'social_options',
				'type' => 'group',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'layout' => 'block',
				'sub_fields' => array(
					array(
						'key' => 'admin_components_social_fb',
						'label' => 'Facebook',
						'name' => 'facebook',
						'type' => 'group',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'layout' => 'block',
						'sub_fields' => array(
							array(
								'key' => 'admin_components_social_fb_account',
								'label' => 'Account',
								'name' => 'account',
								'type' => 'text',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array(
									'width' => '40',
									'class' => '',
									'id' => '',
								),
								'default_value' => '',
								'placeholder' => '',
								'prepend' => 'facebook.com/',
								'append' => '',
								'maxlength' => '',
							),
							array(
								'key' => 'admin_components_social_fb_icon',
								'label' => 'Icon',
								'name' => 'icon',
								'type' => 'text',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array(
									'width' => '30',
									'class' => '',
									'id' => '',
								),
								'default_value' => 'fab fa-facebook-f',
								'placeholder' => 'fab fa-facebook-f',
								'prepend' => '',
								'append' => '',
								'maxlength' => '',
							),
							array(
								'key' => 'admin_components_social_include',
								'label' => 'Include in Widgets',
								'name' => 'widgets',
								'type' => 'checkbox',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array(
									'width' => '30',
									'class' => '',
									'id' => '',
								),
								'choices' => array(
									'follow' => 'Follow',
									'share' => 'Share',
								),
								'allow_custom' => 0,
								'default_value' => array(
								),
								'layout' => 'horizontal',
								'toggle' => 0,
								'return_format' => 'value',
								'save_custom' => 0,
							),
						),
					),
					array(
						'key' => 'admin_components_social_twitter',
						'label' => 'Twitter',
						'name' => 'twitter',
						'type' => 'group',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'layout' => 'block',
						'sub_fields' => array(
							array(
								'key' => 'admin_components_social_twitter_account',
								'label' => 'Account',
								'name' => 'account',
								'type' => 'text',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array(
									'width' => '40',
									'class' => '',
									'id' => '',
								),
								'default_value' => '',
								'placeholder' => '',
								'prepend' => '@',
								'append' => '',
								'maxlength' => '',
							),
							array(
								'key' => 'admin_components_social_twitter_icon',
								'label' => 'Icon',
								'name' => 'icon',
								'type' => 'text',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array(
									'width' => '30',
									'class' => '',
									'id' => '',
								),
								'default_value' => 'fab fa-twitter',
								'placeholder' => 'fab fa-twitter',
								'prepend' => '',
								'append' => '',
								'maxlength' => '',
							),
							array(
								'key' => 'admin_components_social_twitter_include',
								'label' => 'Include in Widgets',
								'name' => 'widgets',
								'type' => 'checkbox',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array(
									'width' => '30',
									'class' => '',
									'id' => '',
								),
								'choices' => array(
									'follow' => 'Follow',
									'share' => 'Share',
								),
								'allow_custom' => 0,
								'default_value' => array(
								),
								'layout' => 'horizontal',
								'toggle' => 0,
								'return_format' => 'value',
								'save_custom' => 0,
							),
						),
					),
					array(
						'key' => 'admin_components_social_instagram',
						'label' => 'Instagram',
						'name' => 'instagram',
						'type' => 'group',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'layout' => 'block',
						'sub_fields' => array(
							array(
								'key' => 'admin_components_social_instagram_account',
								'label' => 'Account',
								'name' => 'account',
								'type' => 'text',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array(
									'width' => '40',
									'class' => '',
									'id' => '',
								),
								'default_value' => '',
								'placeholder' => '',
								'prepend' => '@',
								'append' => '',
								'maxlength' => '',
							),
							array(
								'key' => 'admin_components_social_instagram_icon',
								'label' => 'Icon',
								'name' => 'icon',
								'type' => 'text',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array(
									'width' => '30',
									'class' => '',
									'id' => '',
								),
								'default_value' => 'fab fa-instagram',
								'placeholder' => 'fab fa-instagram',
								'prepend' => '',
								'append' => '',
								'maxlength' => '',
							),
							array(
								'key' => 'admin_components_social_instagram_include',
								'label' => 'Include in Widgets',
								'name' => 'widgets',
								'type' => 'checkbox',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array(
									'width' => '30',
									'class' => '',
									'id' => '',
								),
								'choices' => array(
									'follow' => 'Follow',
								),
								'allow_custom' => 0,
								'default_value' => array(
								),
								'layout' => '',
								'toggle' => 0,
								'return_format' => '',
								'save_custom' => 0,
							),
						),
					),
					array(
						'key' => 'admin_components_social_linkedin',
						'label' => 'LinkedIn',
						'name' => 'linkedin',
						'type' => 'group',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'layout' => 'block',
						'sub_fields' => array(
							array(
								'key' => 'admin_components_social_linkedin_account',
								'label' => 'Account',
								'name' => 'account',
								'type' => 'text',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array(
									'width' => '40',
									'class' => '',
									'id' => '',
								),
								'default_value' => '',
								'placeholder' => '',
								'prepend' => 'linkedin.com/',
								'append' => '',
								'maxlength' => '',
							),
							array(
								'key' => 'admin_components_social_linkedin_icon',
								'label' => 'Icon',
								'name' => 'icon',
								'type' => 'text',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array(
									'width' => '30',
									'class' => '',
									'id' => '',
								),
								'default_value' => 'fab fa-linkedin',
								'placeholder' => 'fab fa-linkedin',
								'prepend' => '',
								'append' => '',
								'maxlength' => '',
							),
							array(
								'key' => 'admin_components_social_linkedin_include',
								'label' => 'Include in Widgets',
								'name' => 'widgets',
								'type' => 'checkbox',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array(
									'width' => '30',
									'class' => '',
									'id' => '',
								),
								'choices' => array(
									'follow' => 'Follow',
									'share' => 'Share',
								),
								'allow_custom' => 0,
								'save_custom' => 0,
								'default_value' => array(
								),
								'layout' => 'horizontal',
								'toggle' => 0,
								'return_format' => 'value',
							),
						),
					),
					array(
						'key' => 'admin_components_social_pinterest',
						'label' => 'Pinterest',
						'name' => 'pinterest',
						'type' => 'group',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'layout' => 'block',
						'sub_fields' => array(
							array(
								'key' => 'admin_components_social_pinterest_account',
								'label' => 'Account',
								'name' => 'account',
								'type' => 'text',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array(
									'width' => '40',
									'class' => '',
									'id' => '',
								),
								'default_value' => '',
								'placeholder' => '',
								'prepend' => 'pinterest.com/',
								'append' => '',
								'maxlength' => '',
							),
							array(
								'key' => 'admin_components_social_pinterest_icon',
								'label' => 'Icon',
								'name' => 'icon',
								'type' => 'text',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array(
									'width' => '30',
									'class' => '',
									'id' => '',
								),
								'default_value' => 'fab fa-pinterest',
								'placeholder' => 'fab fa-pinterest',
								'prepend' => '',
								'append' => '',
								'maxlength' => '',
							),
							array(
								'key' => 'admin_components_social_pinterest_include',
								'label' => 'Include in Widgets',
								'name' => 'widgets',
								'type' => 'checkbox',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array(
									'width' => '30',
									'class' => '',
									'id' => '',
								),
								'choices' => array(
									'follow' => 'Follow',
									'share' => 'Share',
								),
								'allow_custom' => 0,
								'default_value' => array(
								),
								'layout' => 'horizontal',
								'toggle' => 0,
								'return_format' => 'value',
								'save_custom' => 0,
							),
						),
					),
					array(
						'key' => 'admin_components_social_youtube',
						'label' => 'YouTube',
						'name' => 'youtube',
						'type' => 'group',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'layout' => 'block',
						'sub_fields' => array(
							array(
								'key' => 'admin_components_social_youtube_account',
								'label' => 'Account',
								'name' => 'account',
								'type' => 'text',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array(
									'width' => '40',
									'class' => '',
									'id' => '',
								),
								'default_value' => '',
								'placeholder' => '',
								'prepend' => 'youtube.com/',
								'append' => '',
								'maxlength' => '',
							),
							array(
								'key' => 'admin_components_social_youtube_icon',
								'label' => 'Icon',
								'name' => 'icon',
								'type' => 'text',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array(
									'width' => '30',
									'class' => '',
									'id' => '',
								),
								'default_value' => 'fab fa-youtube',
								'placeholder' => 'fab fa-youtube',
								'prepend' => '',
								'append' => '',
								'maxlength' => '',
							),
							array(
								'key' => 'admin_components_social_youtube_include',
								'label' => 'Include in Widgets',
								'name' => 'widgets',
								'type' => 'checkbox',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array(
									'width' => '30',
									'class' => '',
									'id' => '',
								),
								'choices' => array(
									'follow' => 'Follow',
								),
								'allow_custom' => 0,
								'default_value' => array(
								),
								'layout' => 'horizontal',
								'toggle' => 0,
								'return_format' => 'value',
								'save_custom' => 0,
							),
						),
					),
					array(
						'key' => 'admin_components_social_vimeo',
						'label' => 'Vimeo',
						'name' => 'vimeo',
						'type' => 'group',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'layout' => 'block',
						'sub_fields' => array(
							array(
								'key' => 'admin_components_social_vimeo_account',
								'label' => 'Account',
								'name' => 'account',
								'type' => 'text',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array(
									'width' => '40',
									'class' => '',
									'id' => '',
								),
								'default_value' => '',
								'placeholder' => '',
								'prepend' => 'vimeo.com/',
								'append' => '',
								'maxlength' => '',
							),
							array(
								'key' => 'admin_components_social_vimeo_icon',
								'label' => 'Icon',
								'name' => 'icon',
								'type' => 'text',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array(
									'width' => '30',
									'class' => '',
									'id' => '',
								),
								'default_value' => 'fab fa-vimeo',
								'placeholder' => 'fab fa-vimeo',
								'prepend' => '',
								'append' => '',
								'maxlength' => '',
							),
							array(
								'key' => 'admin_components_social_vimeo_include',
								'label' => 'Include in Widgets',
								'name' => 'widgets',
								'type' => 'checkbox',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array(
									'width' => '30',
									'class' => '',
									'id' => '',
								),
								'choices' => array(
									'follow' => 'Follow',
								),
								'allow_custom' => 0,
								'default_value' => array(
								),
								'layout' => 'horizontal',
								'toggle' => 0,
								'return_format' => 'value',
								'save_custom' => 0,
							),
						),
					),
					array(
						'key' => 'admin_components_social_follow',
						'label' => 'Follow Widget',
						'name' => 'follow',
						'type' => 'group',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'layout' => 'block',
						'sub_fields' => array(
							array(
								'key' => 'admin_components_social_follow_icon',
								'label' => 'Widget Icon',
								'name' => 'icon',
								'type' => 'text',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array(
									'width' => '33',
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
								'key' => 'admin_components_social_follow_text',
								'label' => 'Widget Text',
								'name' => 'text',
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
					),
					array(
						'key' => 'admin_components_social_share',
						'label' => 'Share Widget',
						'name' => 'share',
						'type' => 'group',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'layout' => 'block',
						'sub_fields' => array(
							array(
								'key' => 'admin_components_social_share_icon',
								'label' => 'Widget Icon',
								'name' => 'icon',
								'type' => 'text',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array(
									'width' => '33',
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
								'key' => 'admin_components_social_share_text',
								'label' => 'Widget Text',
								'name' => 'text',
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
					),
				),
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'acf-options-component-settings',
				),
			),
		),
		'menu_order' => 5,
		'position' => 'normal',
		'style' => 'seamless',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
		'show_in_rest' => 0,
	)
);

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

// get all of the template posts to populate the select menu

$layout_builder_templates = get_posts ( array (
	'post_type' => 'template',
	'posts_per_page' => -1,
	'orderby' => 'menu_order',
	'order' => 'asc',
	'tax_query' => array (
		'taxonomy' => 'template_tag',
		'field' => 'slug',
		'terms' => array ( 'layout' )
	)
) );

if ( !isset ( $layout_builder_template_IDs ) ) {
	$layout_builder_template_IDs = array();
}

if ( !empty ( $layout_builder_templates ) ) {

	foreach ( $layout_builder_templates as $template ) {
		$layout_builder_template_IDs[$template->ID] = get_the_title ( $template->ID );
	}

}

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
					'admin_layout_builder_template' => array(
						'key' => 'admin_layout_builder_template',
						'name' => 'template',
						'label' => 'Template',
						'display' => 'block',
						'sub_fields' => array(
							array(
								'key' => 'admin_layout_builder_template_post',
								'label' => 'Template',
								'name' => 'template',
								'type' => 'select',
								'instructions' => 'Post templates tagged as \'Layout\' are shown here.',
								'wrapper' => array(
									'width' => '',
									'class' => '',
									'id' => '',
								),
								'choices' => $layout_builder_template_IDs,
								'default_value' => '',
								'allow_null' => 0,
								'multiple' => 0,
								'ui' => 0,
								'return_format' => 'value',
							),

						),
						'min' => '',
						'max' => '',
					),
					'admin_layout_builder_include' => array(
						'key' => 'admin_layout_builder_include',
						'name' => 'include',
						'label' => 'File Include',
						'display' => 'block',
						'sub_fields' => array(
							array(
								'key' => 'admin_layout_builder_include_filename',
								'label' => 'Filename',
								'name' => 'filename',
								'type' => 'text',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array(
									'width' => '75',
									'class' => '',
									'id' => '',
								),
								'default_value' => '',
								'placeholder' => '',
								'prepend' => '[child theme]/template/',
								'append' => '.php',
								'maxlength' => '',
							),
							array(
								'key' => 'admin_layout_builder_include_resolve',
								'label' => 'Resolve elements',
								'name' => 'resolve',
								'type' => 'true_false',
								'instructions' => 'Close current elements before including this file',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array(
									'width' => '25',
									'class' => '',
									'id' => '',
								),
								'message' => '',
								'default_value' => 0,
								'ui' => 0,
								'ui_on_text' => '',
								'ui_off_text' => '',
							),
						),
						'min' => '',
						'max' => '',
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
