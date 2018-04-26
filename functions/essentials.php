<?php

//
// GENERAL
//

// HIDE ADMIN BAR

//show_admin_bar(false);

// GET CURRENT PAGE URL

function current_URL() {
	$pageURL = 'http';
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}

//
// POST / PAGE
//

// GET SLUG BY POST ID

function get_the_slug($thepost = null) {
  global $post;
	if ($thepost === null) $thepost = $post->ID;
	$post_data = get_post($thepost, ARRAY_A);
	$slug = $post_data['post_name'];
	return $slug;
}

// GET TOP-MOST PARENT
// returns title, slug or ID

function get_top_parent($returnwhat = null) {
	if ($returnwhat === null) $returnwhat = 'id';
	
	global $post;
	$parent_id = $post->post_parent;
	
	if (!empty($parent_id)) {
		while ($parent_id) {
			$hasparents = true;
			$parent = get_post($parent_id);
			$parent_id = $parent->post_parent;
			
			if ($parent_id == '0') {
				$top_parent = $parent->ID;
			}
		}
	} else {
		$top_parent = $post->ID;
	}
	
	if ($returnwhat == 'title') {
		$output = get_the_title($top_parent);
	} elseif ($returnwhat == 'slug') {
		$output = get_the_slug($top_parent);
	} else {
		$output = $top_parent;
	}
	
	return $output;
}

// GET PAGE ID BY TITLE

function get_ID_by_title($title) {
  $the_page = get_page_by_title($title);
  return $the_page->ID;
}

//
// CATEGORY / TAXONOMY
//

// GET CURRENT CATEGORY HIERARCHY LEVEL

function category_level($term_id) {
  $level = count(get_ancestors($term_id, 'category'));
  return $level;
}

// GET TOP-MOST CATEGORY
// returns name, slug, object or ID

function get_top_category($term_id, $returnwhat) {
  
  $ancestors = get_ancestors($term_id, 'category');
  $top_ancestor = get_category($ancestors[count($ancestors) - 1]);
  
  if ($returnwhat == 'name') {
		$output = $top_ancestor->name;
	} elseif ($returnwhat == 'slug') {
		$output = $top_ancestor->slug;
	} elseif ($returnwhat == 'object') {
  	$output = $top_ancestor;
  } else {
		$output = $top_ancestor->term_id;
	}
  
  return $output;
}

// GET CUSTOM TAXONOMY FOR GIVEN POST ID
// returns an array of post taxonomies ['name', 'slug', 'id']

function get_custom_taxonomy($taxonomy, $thepost = null) {
  global $post;
	if ($thepost === null) $thepost = $post->ID;
	
	$get_terms = get_the_terms($thepost, $taxonomy);
	
	$i = 0;
	
	if ($get_terms != '') {
		foreach ($get_terms as $term) {
			$post_output[$i]['name'] = $term->name;
			$post_output[$i]['slug'] = $term->slug;
			$post_output[$i]['id'] = $term->term_id;
			$i++;
		}
	}
	
	return $post_output;
}

//
// CONTENT
//

// OUTPUT CUSTOM EXCERPT LENGTH

function custom_excerpt($thepost = null, $limit = null, $link = null) {
  
  global $post;
	
	// defaults
	
	if ($thepost === null) $thepost = $post->ID;
	if ($limit === null) $limit = 20;
	if ($link === null) $link = false;
  
  $excerpt_more = ' … ';
  
  if ($link === null || $link === false) {
    $excerpt_more .= '';
  } else {
    $excerpt_more .= '<a href="'. esc_url(get_permalink($thepost)) . '">Read more</a>';
  }
  
  $excerpt = wp_trim_words(get_the_excerpt($thepost), $limit, $excerpt_more);
  
  return $excerpt;
}

// DISABLE WP EMOJI

function disable_wp_emojicons() {
  remove_action( 'admin_print_styles', 'print_emoji_styles' );
  remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
  remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
  remove_action( 'wp_print_styles', 'print_emoji_styles' );
  remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
  remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
  remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
  
  add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce' );
}

add_action( 'init', 'disable_wp_emojicons' );

function disable_emojicons_tinymce( $plugins ) {
  if ( is_array( $plugins ) ) {
    return array_diff( $plugins, array( 'wpemoji' ) );
  } else {
    return array();
  }
}

// OUTPUT OBFUSCATED EMAIL ADDRESS

function obfuscate($email) {
  for ($i = 0; $i < strlen($email); $i++) {
    $email_obfuscated .= "&#" . ord($email[$i]) . ";";
  }
  
  return $email_obfuscated;
}

// GET IMAGE URL BY ACF FIELD (ID)

function acf_bg($bg_field, $thepost = null) {
	if ($thepost === null) $thepost = $post->ID;
	
	$bg_URL = '';
	
  $bg_ID = get_field($bg_field, $thepost);
  $bg_getURL = wp_get_attachment_image_src($bg_ID, 'full');
  
  if ($bg_getURL) {
    $bg_URL = $bg_getURL[0];
  }
  
  return $bg_URL;
}

// GET FILE INFO BY ATTACHMENT ID

function file_info($file_ID) {
  
  $file['id'] = $file_ID;
  $file['url'] = wp_get_attachment_url($file['id']);
  
  $file['filename'] = explode('/', $file['url']);
  $file['filename'] = $file['filename'][count($file['filename']) - 1];
  
  $file_info = wp_check_filetype_and_ext($file['url'], $file['filename']);
  $file['extension'] = $file_info['ext'];
  $file['mime'] = $file_info['type'];
  
  $file['size'] = size_format(filesize(get_attached_file($file['id'])));
  
  return $file;
  
}