<?php

/*

  1. get_the_slug() - get slug by post ID
  2. get_post_by_slug() - get post ID by slug
  3. get_post_by_title() - get post ID by title
  4. get_top_parent() - get top-most parent of given ID

*/

//
// 1.
// GET SLUG BY POST ID
//

function get_the_slug ( $thepost = null ) {
  global $post;

	$slug = '';

	if ( $thepost === null ) {
  	$thepost = get_the_ID();
  }

	$post_data = get_post ( $thepost, ARRAY_A );

	if ( $post_data ) {
	  $slug = $post_data['post_name'];
  }

  return $slug;

}

//
// 2.
// GET POST BY SLUG
//

function get_post_by_slug ( $slug ) {

  $post_ID = '';

  $posts = get_posts ( array (
    'name' => $slug,
    'posts_per_page' => 1,
    'post_type' => 'any',
    'post_status' => 'publish'
  ) );

  if ( $posts ) {
    $post_ID = $post[0]->ID;
  }

  return $post_ID;

}

//
// 3.
// GET PAGE ID BY TITLE
//

function get_ID_by_title ( $title ) {
  $the_page = get_page_by_title ( $title );

  if ( $the_page != null ) {
    return $the_page->ID;
  } else {
    return '';
  }
}


//
// 4.
// GET TOP-MOST PARENT
// returns title, slug or ID
//

function get_top_parent ( $this_id = null ) {

	if ( $this_id === null ) {
  	global $post;
  	$this_id = $post->post_parent;
	}

	if ( !empty ( $this_id ) ) {

    // while this_id exists
    
		while ( $this_id ) {

      // get the post
			$parent = get_post ( $this_id );
      
      // this ID = the retrieved post's parent
			$this_id = $parent->post_parent;

      // if the ID is 0 we got to the top,
      // so return the retrieved post's own ID
      
			if ( $this_id == '0' || $this_id == 0 ) {
				$top_parent = $parent->ID;
			}

		}

	} else {

		$top_parent = $post->ID;
    
	}

	return $top_parent;
  
}
