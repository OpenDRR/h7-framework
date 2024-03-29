<?php

add_action ( 'switch_theme', 'fw_deactivate' );
 
function fw_deactivate () {
	
	delete_option ( 'fw_init_message' );

}

//
// OPTIONS
// check options on load
//

add_action ( 'admin_head', function() {
	
	if ( get_option ( 'fw_init_message' ) == false ) {
		
		add_action ( 'admin_notices', function() {
			
			echo '<div class="notice notice-success"><h3>Framework Theme Activated</h3><p>Visit <a href="' . admin_url ( 'admin.php?page=acf-options-setup' ) . '">Theme Setup</a> to complete installation</div>';
			
			update_option ( 'fw_init_message', true );
			
		} );
		
	}
	
	$theme_ready = true;
	
	$notice_pre = '<div class="notice notice-error"><h3>Theme Errors</h3><ul style="list-style: square; padding-left: 24px;">';
	$notice_post = '</ul></div>';
	
	if ( !isset ( $GLOBALS['admin_notice'] ) ) {
		$GLOBALS['admin_notice'] = '';
	}
	
	if ( !class_exists('ACF') ) {
	
		$GLOBALS['admin_notice'] .= '<li>ACF Pro not installed.</li>';
		
		$theme_ready = false;
		
	} else {
		
		// default posts (header/footer/layout/home page)
		$create_objects = fw_create_default_objects();
		
		if ( $create_objects !== true ) {
			$GLOBALS['admin_notice'] .= '<li>Failed to create default post objects.</li>';
			$theme_ready = false;
		}
		
		// populate theme setting fields (default padding/colours etc)
		$set_options = fw_set_theme_options();
		
		if ( $set_options !== true ) {
			$GLOBALS['admin_notice'] .= '<li>Failed to update theme options.</li>';
			$theme_ready = false;
		}
		
	}
	
	$GLOBALS['admin_notice'] = $notice_pre . $GLOBALS['admin_notice'] . $notice_post;
		
	if ( $theme_ready == true ) {
		
		// update_option ( 'fw_theme_ready', true );
		
	} else {
		
		add_action ( 'admin_notices', function() {
			
			echo $GLOBALS['admin_notice'];
			
		} );
		
	}
	
	update_option ( 'fw_theme_ready', $theme_ready );
	
	// fw_delete_options();
	
} );

//
// SET OPTIONS
//

function fw_set_theme_options() {
	
	$success = true;
	
	if ( !class_exists('ACF') ) {
		
		$success = false;
		
	} else {
		
		//
		// DEFAULT SETTINGS
		//
		
		if ( get_option ( 'fw_section_defaults' ) == false ) {
		
			// add py-5 as default spacing setting for sections
			
			$section_defaults = array (
				'classes' => '',
				'spacing' => array (
					array (
						'property' => 'p',
						'sides' => 'y',
						'breakpoint' => '',
						'value' => 5
					)
				)
			);
			
			update_field ( 'section_defaults', $section_defaults, 'option' );
			
			update_option ( 'fw_section_defaults', true );
			
		}
		
		if ( get_option ( 'fw_theme_colours' ) == false ) {
		
			// add colour options to the 'theme colours' field
			
			$theme_colours = '';
			$i = 0;
			
			foreach ( $GLOBALS['defaults']['theme_colours'] as $key => $colour ) {
				
				if ( $i != 0) $theme_colours .= "\n";
					
				$theme_colours .= $key . ' : ' . $colour;
				
				$i++;
				
			}
			
			update_field ( 'theme_colours', $theme_colours, 'option' );
			
			update_option ( 'fw_theme_colours', true );
			
		}
		
	}
	
	return $success;
	
}

//
// CREATE DEFAULT POSTS
//

function fw_create_default_objects() {
	
	$success = true;
	
	// TEMPLATES
	
	if ( get_option ( 'fw_default_header' ) == false ) {
		$obj = fw_create_default_obj ( 'header' );
		if ( $obj !== true ) $success = false;
	}
	
	if ( get_option ( 'fw_default_footer' ) == false ) {
		$obj = fw_create_default_obj ( 'footer' );
		if ( $obj !== true ) $success = false;
	}
	
	// LAYOUT
	
	if ( get_option ( 'fw_default_layout' ) == false ) {
		$obj = fw_create_default_obj ( 'layout' );
		if ( $obj !== true ) $success = false;
	}
	
	// HOME PAGE
	
	if ( get_option ( 'fw_default_home' ) == false ) {
		$obj = fw_create_default_obj ( 'home' );
		if ( $obj !== true ) $success = false;
	}
	
	return $success;
	
}

//
// CREATE DEFAULT OBJECT
//

function fw_create_default_obj ( $slug ) {
	
	$success = true;
	$default_obj_ID = null;
	
	if ( !class_exists('ACF') ) {
		
		$success = false;
		
	} else {
	
		switch ( $slug ) {
			
			case 'header' :
				
				// create the post
				
				$default_obj_ID = wp_insert_post ( array (
					'post_type' => 'template',
					'post_status' => 'publish',
					'post_title' => 'Default Header',
					'menu_order' => 0
				), true );
				
				// set the 'default' tag
				
				wp_set_post_terms ( $default_obj_ID, 'Default', 'template_tag', true );
				
				// insert content
				
				update_field ( 'elements', array (
					array (
						'acf_fc_layout' => 'section',
						'section_id' => 'main-header',
						'classes' => 'default-template-element',
						'settings' => false
					),
					array (
						'acf_fc_layout' => 'column',
						'column_id' => '',
						'classes' => 'default-template-element',
						'settings' => false,
						'breakpoints' => array (
							'xs' => array ( 'hide' => false, 'col' => '', 'offset' => '', 'order' => '' ),
							'sm' => array ( 'hide' => false, 'col' => '', 'offset' => '', 'order' => '' ),
							'md' => array ( 'hide' => false, 'col' => '', 'offset' => '', 'order' => '' ),
							'lg' => array ( 'hide' => false, 'col' => '', 'offset' => '', 'order' => '' ),
							'xl' => array ( 'hide' => false, 'col' => '', 'offset' => '', 'order' => '' )
						)
					),
					array (
						'acf_fc_layout' => 'block_content',
						'block_id' => '',
						'classes' => 'default-template-element',
						'blocks' => array (
							array (
								'acf_fc_layout' => 'heading',
								'text' => 'header',
								'level' => 'h6',
								'classes' => ''
							)
						),
						'settings' => false,
					)
				), $default_obj_ID );
				
				break;
				
			case 'footer' : 
				
				// create the post
				
				$default_obj_ID = wp_insert_post ( array (
					'post_type' => 'template',
					'post_status' => 'publish',
					'post_title' => 'Default Footer',
					'menu_order' => 1
				), true );
				
				// set the 'default' tag
				
				wp_set_post_terms ( $default_obj_ID, 'Default', 'template_tag', true );
				
				// insert content
				
				update_field ( 'elements', array (
					array (
						'acf_fc_layout' => 'section',
						'section_id' => 'main-footer',
						'classes' => 'default-template-element',
						'settings' => false
					),
					array (
						'acf_fc_layout' => 'column',
						'column_id' => '',
						'classes' => 'default-template-element',
						'settings' => false,
						'breakpoints' => array (
							'xs' => array ( 'hide' => false, 'col' => '', 'offset' => '', 'order' => '' ),
							'sm' => array ( 'hide' => false, 'col' => '', 'offset' => '', 'order' => '' ),
							'md' => array ( 'hide' => false, 'col' => '', 'offset' => '', 'order' => '' ),
							'lg' => array ( 'hide' => false, 'col' => '', 'offset' => '', 'order' => '' ),
							'xl' => array ( 'hide' => false, 'col' => '', 'offset' => '', 'order' => '' )
						)
					),
					array (
						'acf_fc_layout' => 'block_content',
						'block_id' => '',
						'classes' => 'default-template-element',
						'blocks' => array (
							array (
								'acf_fc_layout' => 'heading',
								'text' => 'footer',
								'level' => 'h6',
								'classes' => ''
							)
						),
						'settings' => false,
					)
				), $default_obj_ID );
				
				break;
				
			case 'layout' : 
				
				// create the post
				
				$default_obj_ID = wp_insert_post ( array (
					'post_type' => 'layout',
					'post_status' => 'publish',
					'post_title' => 'Default Layout',
					'menu_order' => 0
				), true );
				
				// set the layout_file field to have all of the boxes checked
				
				update_field ( 'layout_file', array ( 'index', 'page', 'front-page', 'single', 'archive', 'search', '404' ), $default_obj_ID );
				
				// build the default layout
				
				update_field ( 'layout_builder', array (
					array (
						'acf_fc_layout' => 'template',
						'template' => get_option ( 'fw_default_header' )
					),
					array (
						'acf_fc_layout' => 'content'
					),
					array (
						'acf_fc_layout' => 'template',
						'template' => get_option ( 'fw_default_footer' )
					)
				), $default_obj_ID );
				
				break;
				
			case 'home' :
				
				$default_obj_ID = wp_insert_post ( array (
					'post_type' => 'page',
					'post_status' => 'publish',
					'post_title' => 'Home Page',
					'menu_order' => 0
				), true );
				
				update_option ( 'show_on_front', 'page' );
				update_option ( 'page_on_front', $default_obj_ID );
				
				break;
				
			default : 
				
				echo 'slug not set';
				
		}
		
		// if successful, set the option
		
		update_option ( 'fw_default_' . $slug, $default_obj_ID );
		
	}
	
	return $success;
	
}

//
// DELETE OPTIONS
//

function fw_delete_options ( $key ) {
	
	if ( !isset ( $key ) ) {
	
		delete_option ( 'fw_theme_ready' );
		delete_option ( 'fw_default_header' );
		delete_option ( 'fw_default_footer' );
		delete_option ( 'fw_default_layout' );
		delete_option ( 'fw_default_home' );
		delete_option ( 'fw_section_defaults' );
		delete_option ( 'fw_theme_colours' );
	
	} else {
		
		delete_option ( $key );
		
	}	
	
}

//
// STATUS
// populate 'theme status' message field on the 'theme setup' options page
//

function fw_render_status_message ( $field ) {
	
	$field['message'] = '<h3>Default Objects</h3>';
	
	$field['message'] .= '<ul>';
	
		$field['message'] .= '<li>';
		
			if ( ( get_option ( 'fw_default_header' ) == true ) ) {
				$field['message'] .= '✅ Created <a href="' . admin_url ( 'post.php?post=' . get_option ( 'fw_default_header' ) . '&action=edit' ) . '">default header</a>';
			} else {
				$field['message'] .=  '❌ Created default header';
			}
		
		$field['message'] .= '</li>';
		
		$field['message'] .= '<li>';
		
			if ( ( get_option ( 'fw_default_footer' ) == true ) ) {
				$field['message'] .= '✅ Created <a href="' . admin_url ( 'post.php?post=' . get_option ( 'fw_default_footer' ) . '&action=edit' ) . '">default footer</a>';
			} else {
				$field['message'] .=  '❌ Created default footer';
			}
		
		$field['message'] .= '</li>';
		
		$field['message'] .= '<li>';
		
			if ( ( get_option ( 'fw_default_layout' ) == true ) ) {
				$field['message'] .= '✅ Created <a href="' . admin_url ( 'post.php?post=' . get_option ( 'fw_default_layout' ) . '&action=edit' ) . '">default layout</a>';
			} else {
				$field['message'] .=  '❌ Created default layout';
			}
		
		$field['message'] .= '</li>';
		
		$field['message'] .= '<li>';
		
			if ( ( get_option ( 'fw_default_home' ) == true ) ) {
				$field['message'] .= '✅ Created <a href="' . admin_url ( 'post.php?post=' . get_option ( 'fw_default_home' ) . '&action=edit' ) . '">default home page</a>';
			} else {
				$field['message'] .=  '❌ Created default home page';
			}
		
		$field['message'] .= '</li>';
	
	$field['message'] .= '</ul>';
	
	$field['message'] .= '<h3>Settings</h3>';
	
	$field['message'] .= '<ul>';
	
	$field['message'] .= '<li>' . ( ( get_option ( 'fw_section_defaults' ) == true ) ? '✅' : '❌' ) . ' Set section defaults' . '</li>';
	
	$field['message'] .= '<li>' . ( ( get_option ( 'fw_theme_colours' ) == true ) ? '✅' : '❌' ) . ' Set theme colours' . '</li>';
	
	get_option ( 'fw_theme_ready' );
	
	// dumpit($field);
	
	$field['message'] .= '<div class="button-group">';
	
	$field['message'] .= '<button class="button">Another button</button>';
	
	$field['message'] .= '<button class="button">Reset and regenerate</button></div>';
	
	return $field;

}

add_filter('acf/prepare_field/key=admin_setup_status', 'fw_render_status_message');