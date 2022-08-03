<?php

add_action ( 'admin_head', function() {
  
  $theme_ready = true;
  
  $notice_pre = '<div class="notice notice-error"><h3>Theme Errors</h3><ul style="list-style: square; padding-left: 24px;">';
  $notice_post = '</ul></div>';
  $GLOBALS['admin_notice'] = '';
   
  // $GLOBALS['admin_notice'] = ;
  
  // make sure ACF is installed
  
  if ( function_exists ( 'acf_add_local_field_group' ) ) {
    
    //
    // CREATE DEFAULT HEADER/FOOTER TEMPLATES
    //
    
    // HEADER
    
    if ( get_option ( 'fw_default_header' ) == false ) {
    
      $default_header_ID = wp_insert_post ( array (
        'post_type' => 'template',
        'post_status' => 'publish',
        'post_title' => 'Default Header',
        'menu_order' => 0
      ), true );
      
      update_option ( 'fw_default_header', $default_header_ID );
      
    }
    
    // FOOTER
    
    if ( get_option ( 'fw_default_footer' ) == false ) {
    
      $default_footer_ID = wp_insert_post ( array (
        'post_type' => 'template',
        'post_status' => 'publish',
        'post_title' => 'Default Footer',
        'menu_order' => 1
      ), true );
      
      update_option ( 'fw_default_footer', $default_footer_ID );
      
    }
    
    //
    // DEFAULT LAYOUT
    //
    
    if ( get_option ( 'fw_default_layout' ) == false ) {
      
      // create the post
    
      $default_layout_ID = wp_insert_post ( array (
        'post_type' => 'layout',
        'post_status' => 'publish',
        'post_title' => 'Default Layout',
        'menu_order' => 0
      ), true );
  
      // set the layout_file field to have all of the boxes checked
      
      update_field ( 'layout_file', array ( 'index', 'page', 'front-page', 'single', 'archive', 'search', '404' ), $default_layout_ID );
      
      // build the default layout
      
      $default_layout_items = array (
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
      );
      
      update_field ( 'layout_builder', $default_layout_items, $default_layout_ID );
      
      // set option
      
      update_option ( 'fw_default_layout', $default_layout_ID );
      
    }
    
  } else {
  
    $GLOBALS['admin_notice'] .= '<li>ACF Pro not installed.</li>';
    
    $theme_ready = false;
    
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

  // delete_option ( 'fw_theme_ready' );
  // delete_option ( 'fw_default_header' );
  // delete_option ( 'fw_default_footer' );
  // delete_option ( 'fw_default_layout' );
  
});

add_action ( 'after_switch_theme', function() {
  
  // echo 'switched theme';
  
} );

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
			'secondary' => 'Secondary'
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

//
// INIT
//

function fw_register_session() {
  if ( !session_id() )
		session_start();
}

add_action ( 'init', 'fw_register_session' );

//
// GLOBAL VARS
//

function var_template_include ( $t ) {
  $GLOBALS['vars']['current_template'] = basename ( $t );
  return $t;
}

add_filter ( 'template_include', 'var_template_include', 1000 );

function theme_global_vars() {

	global $vars;
  global $classes;
  global $css;
  global $ids;
  global $elements;
  global $defaults;

	global $acf_fields;

	$acf_fields = array (
		'section' => array(),
		'container' => array(),
		'column' => array(),
		'block' => array()
	);

  //
  // INLINE STYLES
  //

  $css = '';

  //
  // PAGE ID & CLASSES
  //

  $ids['body'] = 'page';

  $classes['body'] = array ( 'spinner-on' );

  if (is_front_page()) {

    $ids['body'] = 'page-home';

    if ( get_field ( 'home_progress' ) == 1 ) {
      $classes['body'][] = 'has-progress';
    }

  } elseif ( is_author() ) {

		$ids['body'] = 'page-author';

	} elseif ( is_archive () ) {

    $ids['body'] = 'page-archive';

  } elseif ( is_home() ) {

		$ids['body'] = 'page-posts';

	} else {

    $ids['body'] = get_post_type() . '-' . get_the_slug();

  }

  //
  // ELEMENTS
  //

  $elements = array (
    'current' => null,
    'types' => array (
      'section' => array (
        'type' => 'section',
        'current_id' => '',
        'classes' => array ( 'fw-section', 'fw-page-section' ),
        'is_open' => false
      ),

      'container' => array (
        'type' => 'container',
        'current_id' => '',
        'classes' => array ( 'fw-container', 'container-fluid' ),
        'is_open' => false
      ),

      'column' => array (
        'type' => 'column',
        'current_id' => '',
        'classes' => array ( 'fw-column' ),
        'is_open' => false
      ),

      'block' => array (
        'type' => 'block',
        'current_id' => '',
        'classes' => array ( 'fw-block' ),
        'is_open' => false
      )
    ),
    'counters' => array (
      'section' => 1,
      'container' => 1,
      'column' => 1,
      'block' => 1
    ),
    'id' => array(),
    'defaults' => array()
  );

  // $page_elements = array();

  //
  // DEFAULTS
  //

	$defaults = array (
		'section' => array (
	    'classes' => '',
	    'margin_top' => 0,
	    'margin_bottom' => 0,
	    'padding_top' => 5,
	    'padding_bottom' => 5
	  ),

	  'container' => array (
	    'classes' => '',
	    'row_classes' => '',
	    'width' => 'full',
	    'margin_top' => 0,
	    'margin_bottom' => 0,
	    'padding_top' => 0,
	    'padding_bottom' => 0
	  ),

	  'column' => array (
	    'classes' => '',
	    'margin_top' => 0,
	    'margin_bottom' => 0,
	    'padding_top' => 0,
	    'padding_bottom' => 0
	  ),

	  'block' => array (
	    'margin_top' => 0,
	    'margin_bottom' => 0,
	    'padding_top' => 0,
	    'padding_bottom' => 0,
	  )

	);

  if ( have_rows ( 'section_defaults', 'option' ) ) {
    $defaults['section'] = get_field ( 'section_defaults', 'option' );
  }

  if ( have_rows ( 'container_defaults', 'option' ) ) {
    $defaults['container'] = get_field ( 'container_defaults', 'option' );
  }

  if ( have_rows ( 'column_defaults', 'option' ) ) {
    $defaults['column'] = get_field ( 'column_defaults', 'option' );
  }

  if ( have_rows ( 'block_defaults', 'option' ) ) {
    $defaults['block'] = get_field ( 'block_defaults', 'option' );
  }

  //
  // HEADER SETTINGS
  //

  $classes['header'] = array();

  if ( have_rows ( 'header_settings', 'option' ) ) {
    while ( have_rows ( 'header_settings', 'option' ) ) {
      the_row();

      $classes['body'][] = 'header-position-' . get_sub_field ( 'header_position' );
      $vars['header']['position'] = get_sub_field ( 'header_position' );

      switch ( get_sub_field ( 'header_position' ) ) {

        case 'default' :
          $classes['header'][] = 'position-relative';
          break;

        case 'absolute' :
          $classes['header'][] = 'position-absolute';
          break;

        case 'fixed' :
          $classes['header'][] = 'fixed-top';
          break;

        case 'sticky' :

          $classes['header'][] = 'sticky';

          function enqueue_sticky() {
            wp_enqueue_script ( 'sticky-kit' );
          }

          add_action ( 'wp_enqueue_scripts', 'enqueue_sticky' );

          break;

      }

      if ( have_rows ( 'background' ) ) {
        while ( have_rows ( 'background' ) ) {
          the_row();

          if ( get_sub_field ( 'colour' ) != '' ) {
            $classes['header'][] = 'bg-' . get_sub_field ( 'colour' );
          }

          if ( get_sub_field ( 'opacity' ) != '' ) {
            $opacity = get_sub_field ( 'opacity' );
            if ( get_sub_field ( 'opacity' ) < 1 ) $opacity = $opacity * 100;
            $classes['header'][] = 'bg-opacity-' . $opacity;
          }

        }
      }

      if ( get_sub_field ( 'header_position' ) == 'sticky' && have_rows ( 'background_sticky' ) ) {
        while ( have_rows ( 'background_sticky' ) ) {
          the_row();

          if ( get_sub_field ( 'colour' ) != '' ) {
            $classes['header'][] = 'bg-sticky-' . get_sub_field ( 'colour' );
          }

          if ( get_sub_field ( 'opacity' ) != '' ) {
            $opacity = get_sub_field ( 'opacity' );
            if ( get_sub_field ( 'opacity' ) < 1 ) $opacity = $opacity * 100;
            $classes['header'][] = 'bg-sticky-opacity-' . $opacity;
          }

        }
      }


    }
  }

  //
  // FOOTER SETTINGS
  //

  $classes['footer'] = array();

  if ( have_rows ( 'footer_settings', 'option' ) ) {
    while ( have_rows ( 'footer_settings', 'option' ) ) {
      the_row();

      // colours

      if ( have_rows ( 'colours' ) ) {
        while ( have_rows ( 'colours' ) ) {
          the_row();

          if (
            get_sub_field ( 'text_colour' ) != '' &&
            get_sub_field ( 'text_colour' ) != 'inherit'
          ) {

            $GLOBALS['css'] .= "\n" . '#page-footer { color: var(--' . get_sub_field ( 'text_colour' ) . '); }';

          }

          if (
            get_sub_field ( 'heading_colour' ) != '' &&
            get_sub_field ( 'heading_colour' ) != 'inherit'
          ) {

            $GLOBALS['css'] .= "\n" . '#page-footer h1, #page-footer h2, #page-footer h3, #page-footer h4, #page-footer h5, #page-footer h6 { color: var(--' . get_sub_field ( 'heading_colour' ) . '); }';

          }

          if (
            get_sub_field ( 'link_colour' ) != ''  &&
            get_sub_field ( 'link_colour' ) != 'inherit'
          ) {

            $GLOBALS['css'] .= "\n" . '#page-footer a { color: var(--' . get_sub_field ( 'link_colour' ) . '); }';

          }

        }
      }

      // background

      if ( have_rows ( 'background' ) ) {
        while ( have_rows ( 'background' ) ) {
          the_row();

          if ( get_sub_field ( 'colour' ) != '' ) {
            $classes['footer'][] = 'bg-' . get_sub_field ( 'colour' );
          }

        }
      }

    }
  }

	$vars['indent'] = 0;

	//
	// DATE / TIME
	//

	$vars['timestamp'] = current_time ( 'timestamp' );
  $vars['date'] = date ( 'Ymd', $vars['timestamp'] );

  //
  // URLS
  //

  // CURRENT SITE URL

  $vars['site_url'] = get_bloginfo('url');

  if ( substr ( $vars['site_url'], -1) != '/' ) $vars['site_url'] .= '/';

  // NETWORK SITE URL

  $vars['network_site_url'] = $vars['site_url']; // default (not multisite)

  if ( is_multisite() ) {
    $vars['network_site_url'] = network_site_url();
  }

  // CURRENT LOCATION URL AND SLUG

  $vars['current_url'] = current_URL();
  $vars['current_slug'] = get_the_slug();
  $vars['current_ancestors'] = get_ancestors ( get_the_ID(), get_post_type() );

  // LANGUAGE

  $current_lang = 'en';

  // $vars['current_lang'] = ICL_LANGUAGE_CODE;
  // $classes['body'][] = 'lang-' . $vars['current_lang'];

  //
  // HOME PAGE ID
  //

  $vars['homepage'] = get_option ( 'page_on_front' );

	//
	// CURRENT QUERY
	//

	// global $current_query;
	$vars['current_query'] = get_queried_object();

	//
	// DIRECTORIES
	//

	// PARENT

	$vars['theme_dir'] = get_bloginfo ( 'template_directory' ) . '/';

  // CHILD

  $vars['child_theme_dir'] = $vars['theme_dir']; // default (not a child theme)

  if ( is_child_theme() ) {
    $vars['child_theme_dir'] = get_stylesheet_directory_uri() . '/';
  }

  //
  // USER
  //

  $vars['user_id'] = '';

  if ( is_user_logged_in() ) {

    $vars['user_id'] = get_current_user_id();

    if ( current_user_can ( 'administrator' ) ) {
      $classes['body'][] = 'logged-in-admin';
    }
  }

}

add_action ( 'wp', 'theme_global_vars', 10 );

//
// THEME OPTIONS
//

function theme_options() {

	global $vars;

  // SOCIAL ACCOUNTS

  if ( have_rows ( 'social_options', 'option' ) ) {
    while ( have_rows ( 'social_options', 'option' ) ) {
      the_row();

      // redirect URL for sharing inline content with hash

      $vars['social']['redirect_URL'] = get_permalink ( get_sub_field ( 'redirect' ) );

      // SITES

      $social_sites = array ( 'facebook', 'twitter', 'instagram', 'linkedin', 'pinterest', 'youtube', 'vimeo' );

      foreach ( $social_sites as $site ) {
        if ( have_rows ( $site ) ) {
          while ( have_rows ( $site ) ) {
            the_row();

            switch ( $site ) {
              case 'facebook' :
                $site_name = __ ( 'Facebook', 'fw-social-widget' );
                break;

              case 'twitter' :
                $site_name = __ ( 'Twitter', 'fw-social-widget' );
                break;

              case 'linkedin' :
                $site_name = __ ( 'LinkedIn', 'fw-social-widget' );
                break;

              case 'instagram' :
                $site_name = __ ( 'Instagram', 'fw-social-widget' );
                break;

              case 'pinterest' :
                $site_name = __ ( 'Pinterest', 'fw-social-widget' );
                break;

              case 'youtube' :
                $site_name = __ ( 'YouTube', 'fw-social-widget' );
                break;

              case 'vimeo' :
                $site_name = __ ( 'Vimeo', 'fw-social-widget' );
                break;

              default :
                $site_name = '';

            }

            $vars['social']['sites'][$site] = array (
              'site' => $site,
              'name' => $site_name,
              'account' => get_sub_field ( 'account' ),
              'icon' => get_sub_field ( 'icon' )
            );

            $widgets = get_sub_field ( 'widgets' );

            if ( is_array ( $widgets ) && in_array ( 'follow', $widgets ) ) {
              $vars['social']['follow']['items'][] = $site;
            }

            if ( is_array ( $widgets ) && in_array ( 'share', $widgets ) ) {
              $vars['social']['share']['items'][] = $site;
            }

          }
        }
      }

      // WIDGET OPTIONS

      // follow

      while ( have_rows ( 'follow' ) ) {
        the_row();

        $vars['social']['follow']['widget_icon'] = get_sub_field ( 'icon' );
        $vars['social']['follow']['widget_text'] = get_sub_field ( 'text' );

      }

      // share

      while ( have_rows ( 'share' ) ) {
        the_row();

        $vars['social']['share']['widget_icon'] = get_sub_field ( 'icon' );
        $vars['social']['share']['widget_text'] = get_sub_field ( 'text' );
        $vars['social']['share']['sites'] = get_sub_field ( 'sites' );

      }

    }
  }

  // BACKGROUND GALLERY

  if ( have_rows ( 'hero_default', 'option' ) ) {
    while ( have_rows ( 'hero_default', 'option' ) ) {
      the_row();

      $vars['bgs'] = get_sub_field ( 'backgrounds' );

    }
  }

}

add_action ( 'wp', 'theme_options' );

//
// INCLUDES
//

$includes = array (
  'resources/functions/essentials/overrides.php',
  'resources/functions/essentials/blocks.php',
  'resources/functions/essentials/post.php',
  'resources/functions/essentials/taxonomy.php',
  'resources/functions/essentials/content.php',
  'resources/functions/essentials/layout.php',
  'resources/functions/essentials/media.php',
  'resources/functions/essentials/misc.php',
  'resources/functions/essentials/post-types.php',
  'resources/functions/essentials/taxonomies.php',
  'resources/functions/essentials/shortcodes.php',
  'resources/functions/essentials/acf.php',
  'resources/functions/loop/elements.php',
  'resources/functions/loop/extras.php',
  'resources/functions/loop/utilities.php',
  'resources/functions/template/hero.php',
  'resources/functions/template/menu.php',
  'resources/functions/extensions/taxonomies.php',
  'resources/functions/extensions/post-types.php',
  'resources/functions/extensions/shortcodes.php',
	'elements/carousel/setup.php'
);

foreach ( $includes as $include ) {

  if ( locate_template ( $include ) != '' ) {

    include_once ( locate_template ( $include ) );
  }

}

//
// THEME FEATURES
//

function theme_features() {

  // menus

  add_theme_support ( 'menus' );

  register_nav_menus ( array (
    'primary' => 'Primary Navigation',
    'secondary' => 'Secondary Navigation',
    'footer' => 'Footer Menu'
  ) );

  // image sizes

  add_theme_support('post-thumbnails');

  add_image_size ( 'bg', '2400', '1800', true );
  add_image_size ( 'bg-no-crop', '2400', '1800', false );
  add_image_size ( 'card-img', '600', '380', true );

  // ACF options page

  if ( function_exists ( 'acf_add_options_page' ) && current_user_can ( 'administrator' ) ) {

  	acf_add_options_page ( array (
  		'page_title' 	=> 'Theme Settings',
  		'menu_title'	=> 'Theme Settings',
  		'menu_slug' 	=> 'theme-settings',
  		'capability'	=> 'edit_posts'
  	) );

  	acf_add_options_sub_page ( array (
  		'page_title'  => 'Page Builder',
  		'menu_title'  => 'Page Builder',
  		'parent_slug' => 'theme-settings',
  	) );

  	acf_add_options_sub_page ( array (
  		'page_title'  => 'Default Settings',
  		'menu_title'  => 'Defaults',
  		'parent_slug' => 'theme-settings',
  	) );

  	acf_add_options_sub_page ( array (
  		'page_title'  => 'Header',
  		'menu_title'  => 'Header',
  		'parent_slug' => 'theme-settings',
  	) );

  	acf_add_options_sub_page ( array (
  		'page_title'  => 'Footer',
  		'menu_title'  => 'Footer',
  		'parent_slug' => 'theme-settings',
  	) );

  	acf_add_options_sub_page ( array (
  		'page_title'  => 'Components',
  		'menu_title'  => 'Component Settings',
  		'parent_slug' => 'theme-settings',
  	) );

  }
}

add_action ( 'after_setup_theme', 'theme_features' );

//
// ENQUEUE
//

// FRONT-END

function theme_enqueue() {

  //
  // VARS
  //

  $theme_dir = get_bloginfo('template_directory') . '/';
  $vendor_dir = $theme_dir . 'resources/vendor/';
  $js_dir = $theme_dir . 'resources/js/';

  //
  // STYLES
  //

  wp_dequeue_style ( 'wp-block-library' );
  wp_dequeue_style ( 'global-styles' );

  // global

  wp_register_style ( 'global-style', $theme_dir . 'style.css', NULL, NULL, 'all' );
  wp_enqueue_style ( 'global-style' );

  // user

  if ( is_user_logged_in() ) {
    wp_register_style ( 'user-frontend', $theme_dir . 'resources/css/user/frontend.css', NULL, NULL, 'all' );
    wp_enqueue_style ( 'user-frontend' );
  }

  // VENDOR

  // magnify
  
  wp_register_script ( 'magnify', $vendor_dir . 'magnify/dist/css/magnify.css' );
  
  // font awesome

  wp_register_style ( 'font-awesome', '//use.fontawesome.com/releases/v5.9.0/css/all.css', NULL, NULL, 'all' );
  wp_enqueue_style ( 'font-awesome' );

  //
  // SCRIPTS
  //

  // REGISTER

  wp_enqueue_script ( 'jquery' );

  // vendor

  wp_register_script ( 'slick', $vendor_dir . 'slick-carousel/slick/slick.min.js', array ( 'jquery' ), NULL, true );
  wp_register_script ( 'sticky-kit', $vendor_dir . 'sticky-kit/dist/sticky-kit.js', array ( 'jquery' ), NULL, true );
  wp_register_script ( 'swiper', 'https://unpkg.com/swiper@7.2.0/swiper-bundle.min.js', NULL, NULL, true );
  wp_register_script ( 'lazy', $vendor_dir . 'jquery.lazy/jquery.lazy.min.js', array ( 'jquery' ), NULL, true );
  wp_register_script ( 'rellax', $vendor_dir . 'rellax/rellax.min.js', NULL, NULL, true );
  wp_register_script ( 'magnify', $vendor_dir . 'magnify/dist/js/jquery.magnify.js', NULL, NULL, true );
  wp_register_script ( 'in-view', $vendor_dir . 'in-view/dist/in-view.min.js', NULL, NULL, true );
	wp_register_script ( 'lottie', 'https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.5.9/lottie.min.js', NULL, NULL, true );
  wp_register_script ( 'animation', $js_dir . 'animation-functions.js', array ( 'jquery' ), NULL, true );
  wp_register_script ( 'isotope', 'https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js', array ( 'jquery' ), NULL, true );
  wp_register_script ( 'select2', $vendor_dir . 'select2/dist/js/select2.full.min.js', array ( 'jquery' ), NULL, true );

  // bootstrap

  wp_register_script ( 'bootstrap-js', $vendor_dir . 'bootstrap/dist/js/bootstrap.bundle.min.js', array( 'jquery' ), NULL, true );

  // utilities

  wp_register_script ( 'smooth-scroll', $vendor_dir . 'pe-smooth-scroll/smooth-scroll.js', array ( 'jquery' ), NULL, true );

  // components

		// query objects

		wp_register_script ( 'post-grid', $js_dir . 'post-grid.js', array ( 'jquery' ), NULL, true );
		wp_register_script ( 'post-carousel', $js_dir . 'post-carousel.js', array ( 'jquery' ), NULL, true );

    // social widgets

    wp_register_script ( 'share-widget', $vendor_dir . 'pe-social-widget/share-widget.js', array ( 'jquery' ), NULL, true );
    wp_register_script ( 'follow-widget', $vendor_dir . 'pe-social-widget/follow-widget.js', array ( 'jquery' ), NULL, true );

    // supermenu

    wp_register_script ( 'supermenu', $vendor_dir . 'pe-supermenu/supermenu.js', array ( 'jquery', 'bootstrap-js', 'slick' ), NULL, true );

    // overlay

    wp_register_script ( 'overlay', $vendor_dir . 'pe-overlay/overlay.js', array ( 'jquery' ), NULL, true );

  // renderables

    // dependencies

    wp_register_script ( 'scroll-progress', $vendor_dir . 'pe-scroll-progress/scroll-progress.js', array ( 'jquery' ), NULL, true );

    // renderer

    wp_register_script ( 'renderer', $js_dir . 'renderer.js', array ( 'jquery' ), NULL, true );

  // page functions

  wp_register_script ( 'helpers', $js_dir . 'helper-functions.js', array ( 'jquery' ), NULL, true );
  wp_register_script ( 'global-functions', $js_dir . 'global-functions.js', array ( 'jquery', 'helpers', 'smooth-scroll' ), NULL, true );

  wp_register_script ( 'home-functions', $js_dir . 'home-functions.js', array ( 'jquery' ), NULL, true );

  // ENQUEUE

  // global

  wp_enqueue_script ( 'bootstrap-js' );

  wp_enqueue_script ( 'sticky-kit' );
  wp_enqueue_script ( 'in-view' );

  //wp_enqueue_script ( 'follow-widget' );
  //wp_enqueue_script ( 'share-widget' );
  //wp_enqueue_script ( 'smooth-scroll' );

  //wp_enqueue_script ( 'supermenu' );

  //
  // REQUIRED
  //

  wp_enqueue_script ( 'overlay' );

  wp_enqueue_script ( 'global-functions' );

}

add_action ( 'wp_enqueue_scripts', 'theme_enqueue' );

// ADMIN

// enqueue

function load_custom_wp_admin_style() {

  $theme_dir = get_bloginfo ( 'template_directory' ) . '/';
  $vendor_dir = $theme_dir . 'resources/vendor/';
  $js_dir = $theme_dir . 'resources/js/';

  //
  // CSS
  //

  wp_register_style ( 'admin-style', $theme_dir . 'resources/css/user/admin.css', NULL, NULL, 'all' );
  wp_enqueue_style ( 'admin-style' );

  //
  // JS
  //

  wp_register_script ( 'admin', $js_dir . 'admin-functions.js', array ( 'jquery' ), NULL, true );

	wp_localize_script ( 'admin', 'admin_ajax_data',
    array (
			'url' => admin_url ( 'admin-ajax.php' )
		)
	);

  wp_enqueue_script ( 'admin' );

  // $admin_screen = get_current_screen();

}

add_action ( 'admin_enqueue_scripts', 'load_custom_wp_admin_style' );

//
// WPML
//

// get translated ID by path

function filtered_ID_by_path ( $path, $lang = null ) {

  if ( $lang == null ) {
    if ( isset ( $GLOBALS['vars']['current_lang'] ) ) {
      $lang = $GLOBALS['vars']['current_lang'];
    } else {
      $lang = 'en';
    }
  }

  $get_page = get_page_by_path ( $path );

  if ( !empty ( $get_page ) ) {

    return apply_filters ( 'wpml_object_id', $get_page->ID, 'page', true, $lang );

  }

}

//
//
//
// HOMELESS
//
//
//


//
// allow json uploads
//

function fw_custom_mime_types ( $mime_types ) {
  $mime_types['json'] = 'application/json';
  return $mime_types;
}

add_filter ( 'upload_mimes', 'fw_custom_mime_types' );

function my_correct_filetypes ( $data, $file, $filename, $mimes, $real_mime ) {

  if ( !empty ( $data['ext'] ) && !empty ( $data['type'] ) ) {
    return $data;
  }

  $wp_file_type = wp_check_filetype ( $filename, $mimes );

  if ( 'json' == $wp_file_type['ext'] ) {
    $data['ext'] = 'json';
    $data['type'] = 'application/json';
  }

  return $data;

}

add_filter ( 'wp_check_filetype_and_ext', 'my_correct_filetypes', 10, 5 );

//
// LAYOUT UTILITIES
//

// query object - default elements

function query_object_builder_default ( $field ) {

	// find the 'containers' repeater sub-field

	if ( isset ( $field['sub_fields'] ) ) {

		foreach ( $field['sub_fields'] as $key => $sub_field ) {
			if ( $sub_field['name'] == 'containers' ) {
				$containers_i = $key;
			}
		}

		if ( isset ( $containers_i ) ) {

			if ( $field['sub_fields'][$containers_i]['value'] === NULL ) {

				$field['sub_fields'][$containers_i]['value'] = array (
					array (
						'field_5f7735107c87d' => array (
							array (
								'acf_fc_layout' => 'query_items'
							)
						)
					)
				);

			}

		}

	}

	return $field;
}

/*

add_filter ( 'acf/load_field/key=field_5f77381992ca2', 'query_object_builder_default', 10, 3 );

// populate 'post terms' taxonomy select with all registered taxonomies

function populate_terms_select ( $field ) {

	$all_tax = get_taxonomies ( array (
		'public'   => true,
  	'_builtin' => false
	), 'objects' );

	foreach ($all_tax as $tax => $tax_obj ) {

		$field['choices'][ $tax ] = $tax_obj->labels->singular_name;

	}

  return $field;

}

add_filter ( 'acf/load_field/key=field_5d77ab8a8fffc', 'populate_terms_select' );


// 'settings' clones from the blocks flex field

$block_names = array (
  'heading' => '5d07f0390631a',
  'text' => '5d5d87e0ac22d',
  'jumbotron' => '5d261b80f4347',
  'buttons' => '5d5d4bcdcc84a'
);

// flipped array

$block_names_by_key = array_flip ( $block_names );

// default value sub-fields & keys

// fields

$field_names = array (
  'colours' => array (
    'btn_bg'          => '5d5d4d7deed1f',
    'btn_text'        => '5d5d4da2eed20',
    'btn_border'      => '5d5d4deeeed21'
  )
);

// clones

$clones = array (
  'colours' => array (
    'heading_colour'  => '5d26220eeb77c',
    'text_colour'     => '5d4d83a09c0f6',
    'link_colour'     => '5d4d86110e243',
  ),
  'background' => array (
    'colour' => '5ccb2eaa6a64d'
  )
);

function block_defaults_init() {

  global $block_names;
  global $block_names_by_key;
  global $field_names;
  global $clones;

  $admin_screen = get_current_screen();

  if ( $admin_screen->base == 'post' ) {

    // fields

    foreach ( $field_names as $field => $sub_fields ) {
      foreach ( $sub_fields as $sub_field => $field_key ) {

        add_filter ( 'acf/prepare_field/key=field_' . $field_key, 'get_field_defaults' );

      }
    }

    // clones

    foreach ( $block_names as $block => $block_key ) {
      foreach ( $clones as $field => $sub_fields ) {
        foreach ( $sub_fields as $sub_field => $field_key ) {

          // echo "\n" . $block . '/' . $field . '/' . $sub_field . ': field_' . $block_key . '_field_' . $field_key;

          add_filter ( 'acf/prepare_field/key=field_' . $block_key . '_field_' . $field_key, 'get_field_defaults' );

        }
      }
    }

  }

}

add_action ( 'admin_head', 'block_defaults_init' );

function get_container_default_width ( $field ) {

  $width_default = '';

  if ( have_rows ( 'container_defaults', 'option' ) ) {
    while ( have_rows ( 'container_defaults', 'option' ) ) {
      the_row();

      if ( get_sub_field ( 'width' ) != '' ) {
        $width_default = get_sub_field ( 'width' );
      }
    }
  }

  $this_value = $field['value'];

  if ( $width_default != '' && ( $this_value == '' || empty ( $this_value ) ) ) {
    $this_value = $width_default;
    $field['value'] = $this_value;
  }

  return $field;

}

add_filter ( 'acf/prepare_field/key=field_5dc0533eec190', 'get_container_default_width' );

*/
// add_filter ( 'acf/prepare_field/key=field_5d5d4d7deed1f', 'get_field_defaults' );

/*
function field_defaults_js() {

  $theme_dir = get_bloginfo ( 'template_directory' ) . '/';
  $js_dir = $theme_dir . 'resources/js/';

  // wp_register_script ( 'block-defaults', $js_dir . 'block-defaults.js', array ( 'jquery' ), NULL, true );

  $admin_screen = get_current_screen();

  if ( $admin_screen->base == 'post' ) {
    // wp_enqueue_script ( 'hotspots-admin' );

    $defaults_array = array();

    $default_fields = array ( 'text', 'jumbotron', 'buttons' );

    foreach ( $default_fields as $field_name ) {

      $defaults_array[$field_name] = get_field ( $field_name . '_defaults', 'option' );

    }

?>

<script>

(function($) {
  $(function() {

    $(document).data('block_defaults', <?php echo json_encode ( $defaults_array ); ?>)

    console.log($(document).data('block_defaults'))

  });
})(jQuery);

</script>

<?php

  }

}

add_action ( 'admin_footer', 'field_defaults_js' );
*/

//
// FLEX FIELD 'NO VALUE' MESSAGES
//
/*
add_filter ( 'acf/fields/flexible_content/no_value_message', 'acf_flex_message', 10, 3 );

function acf_flex_message ( $string = null, $field = null ) {

  switch ( $field['key'] ) {

    // block - content

    case 'field_5dbc5d79be45e' :
      $string = 'Click "Add Block" to select an element.';
      break;

    // block - interactive

    case 'field_5d2f237183591' :
      $string = 'Click "Add Interactive" to select an element.';
      break;

    // block - navigation

    case 'field_5dc4423f87c9b' :
      $string = 'Click "Add Block" to select an element.';
      break;

    // settings

    case 'field_5dc1d75b83c2c' :
      $string = 'Click "Add Setting" to select which option you want to override for this element.';
      break;

  }

  return $string;

}
*/

//
//

function get_page_builder_toolbar() {

?>

<div id="page-builder-toolbar">
	<a href="#" id="toolbar-item-insert" class="toolbar-icon"><i class="fas fa-paste"></i><span>Insert from Template</span></a>

	<a href="#" id="toolbar-item-toggle" class="toolbar-icon collapse-all"><i class="fas fa-compress"></i><span>Collapse all</span></a>
</div>

<div id="page-builder-modal-overlay">
	<div id="template-modal" class="page-builder-modal">
		<div class="page-builder-modal-body">
			<h3>Insert fields from a template</h3>
			<p>Select a template to copy its fields into this page.</p>
			<p><strong>Save your page before using this tool.</strong> Unsaved changes will be lost when you click 'Insert and Reload.'</strong></p>

			<?php

				$templates = new WP_Query ( array (
					'post_type' => 'template',
					'posts_per_page' => -1,
					'meta_key' => 'template_insertable',
					'meta_value' => 1
				));

				if ( $templates->have_posts() ) :

			?>

			<select id="template-menu">
				<option selected disabled>- Select a template -</option>

				<?php

					while ( $templates->have_posts() ) :
						$templates->the_post();

				?>

				<option value="<?php echo get_the_ID(); ?>"><?php the_title(); ?></option>

				<?php

					endwhile;

				?>

			</select>

			<?php

				endif;

				wp_reset_postdata();

			?>
		</div>

		<div id="template-modal-footer" class="page-builder-modal-footer">
			<button id="template-submit" class="button button-primary button-large" disabled>Insert and Reload</button>
			<a id="template-cancel">Cancel</a>
		</div>
	</div>

	<div id="some-other-modal" class="page-builder-modal">
		modal
	</div>
</div>

<?php

	die();

}

add_action ( 'wp_ajax_get_page_builder_toolbar', 'get_page_builder_toolbar' );

// function get_template_posts() {
//
// 	$templates = new WP_Query ( array (
// 		'post_type' => 'template',
// 		'posts_per_page' => -1
// 	));
//
// 	$template_list = array();
//
// 	if ( $templates->have_posts() ) :
//
// 		$template_list = array();
//
// 		while ( $templates->have_posts() ) :
// 			$templates->the_post();
//
// 			$template_list[] = array (
// 				'id' => get_the_ID(),
// 				'title' => get_the_title()
// 			);
//
// 		endwhile;
// 	endif;
//
// 	wp_reset_postdata();
//
// 	if ( !empty ( $template_list ) ) {
//
// 		print_r ( json_encode ( $template_list ) );
//
// 	} else {
//
// 		echo 'none';
//
// 	}
//
// 	die();
//
// }
//
// add_action ( 'wp_ajax_get_template_posts', 'get_template_posts' );

function get_template_fields() {

	$source_fields = get_field ( 'elements', $_POST['source'] );

	echo "\n\nSOURCE:\n\n";

	print_r($source_fields);

	$current_fields = get_field ( 'elements', $_POST['target'] );

	if ( !is_array ( $current_fields ) ) {
		$current_fields = array();
	}

	echo "\n\nCURRENT:\n\n";

	print_r($current_fields);


	$merged = array_merge ( $current_fields, $source_fields );


	echo "\n\nMERGED:\n\n";

	print_r($merged);

	update_field ( 'elements', $merged, $_POST['target'] );

	// print_r ( json_encode ( $merged ) );

	die();

}

add_action ( 'wp_ajax_get_template_fields', 'get_template_fields' );
// add_action ( 'wp_ajax_nopriv_get_template_fields', 'get_template_fields' );

function dumpit ( $var ) {

	echo '<pre style="font-size: 9px;">';
	print_r($var);
	echo '</pre>';
}
