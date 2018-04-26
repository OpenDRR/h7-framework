<?php
  
//
// INCLUDES
//

// essentials
include_once('functions/essentials.php');

// post types
//include_once('functions/posttypes.php');

// taxonomies
//include_once('functions/taxonomies.php');

// shortcodes
//include_once('functions/shortcodes.php');

//
// THEME FEATURES
//

// thumbnails

add_theme_support('post-thumbnails');

// nav

/*
add_action('after_setup_theme', 'register_theme_menu');

function register_theme_menu() {
  register_nav_menus(array(
  	'primary' => 'Main Navigation',
  	'secondary' => 'Secondary Navigation',
  	'footer' => 'Footer Navigation',
  ));
}
*/

// 
// ENQUEUE
//

function theme_enqueue() {
  $theme_dir = get_bloginfo('template_directory') . '/';
  $bower_dir = $theme_dir . 'resources/bower_components/';
  $js_dir = $theme_dir . 'resources/js/';
  
  //
  // STYLES
  //
  
  wp_register_style('page-style', $theme_dir . 'style.css', NULL, NULL, 'all');
  wp_enqueue_style('page-style');
  
  //
  // SCRIPTS
  //
  
  // REGISTER
  
  // components
  
  //wp_register_script('smooth-scroll', $theme_dir . 'resources/components/smooth-scroll/smooth-scroll.js', array('jquery'), NULL, true);
  
  // page functions
  
  wp_register_script('global-functions', $js_dir . 'global-functions.js', array('jquery'), NULL, true);
  
  // ENQUEUE
  
  // global
  
  wp_enqueue_script('jquery');
  wp_enqueue_script('global-functions');
  
  // page conditionals
  
  if (is_front_page()) {
    
  }
  
}

add_action('wp_enqueue_scripts', 'theme_enqueue');  