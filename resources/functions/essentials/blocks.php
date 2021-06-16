<?php

/*

  1. get_next_page - NEXT PAGE

*/

function recursive_post_list ( $current_parent, $current_post_type, $level, $exclude ) {

  global $all_posts;

  $all_post_query = new WP_Query ( array (
    'post_type' => $current_post_type,
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'post_parent' => $current_parent,
    'post__not_in' => $exclude,
    'orderby' => 'menu_order',
    'order' => 'asc'
  ) );

  if ( $all_post_query->have_posts() ) :
    while ( $all_post_query->have_posts() ) :
      $all_post_query->the_post();

      // echo 'ID: ' . get_the_ID() . ', ' . get_the_title() . '<br>';

      $all_posts[] = get_the_ID();

      // see if a child exists

      $get_children = get_posts ( array (
        'post_type' => $current_post_type,
        'post_parent' => get_the_ID(),
        'post_status' => 'publish',
        'exclude' => $exclude
      ) );

      if ( !empty ( $get_children ) ) {
        recursive_post_list ( get_the_ID(), $current_post_type, $level + 1, $exclude );
      } else {
        // echo get_the_ID() . ' doesn\'t have children<br>';
      }

    endwhile;
  endif;

  wp_reset_postdata();

  return $all_posts;

}

function fw_get_next_page_link ( $include_children = true ) {

  global $post;

  if ( !isset ( $include_children ) ) {
    $include_children = true;
  }

  $current_ID = get_the_ID();

  $next_link = array();

  $get_next = false;
  $is_next_set = false;

  // if the next page is manually selected

  if ( get_sub_field ( 'select' ) == 'manual' ) {

    if ( get_sub_field ( 'link' ) != '' ) {

      $next_link['id'] = get_sub_field ( 'link' );
      $is_next_set = true;

    }

  } else {

    // recursively get all posts of this type
    // and create a 1-dimensional list

    $current_post_type = get_post_type();

    $all_posts = recursive_post_list ( 0, $current_post_type, 1, get_sub_field ( 'exclude' ) );

    // find this post in the list array
    // and get the one after it

    $next_flag = false;

    foreach ( $all_posts as $item ) {

      if ( $next_flag == true ) {

        $is_next_set = true;
        $next_link['id'] = $item;
        $next_link['title'] = get_the_title ( $item );
        $next_link['btn'] = __ ( 'Next page', 'fw' );

        break;

      }

      if ( $item == get_the_ID() ) {
        $next_flag = true;
      }
    }


    /*

    // find next page in menu order

    // if including children

    if ( $include_children == true ) {

      // see if a child exists

      $get_children = get_posts ( array (
        'post_type' => get_post_type(),
        'post_parent' => get_the_ID(),
        'post_status' => 'publish'
      ) );

      if ( !empty ( $get_children ) ) {
        $is_next_set = true;

        $next_link['id'] = $get_children[0]->ID;
        $next_link['title'] = get_the_title ( $get_children[0]->ID );
        $next_link['btn'] = __ ( 'Next page', 'fw' );

      }

    }

    // if next_link['id'] is not set,
    // we're either NOT including children,
    // or we ARE including children but didn't find one

    if ( !isset ( $next_link['id'] ) ) {

      $next_query = new WP_Query ( array (
        'post_type' => get_post_type(),
        'posts_per_page' => -1,
        'orderby' => 'menu_order',
        'order' => 'asc',
        'post_status' => 'publish',
        'post_parent' => $post->post_parent
      ) );

      if ( $next_query->have_posts() ) :

        while ($next_query->have_posts()) : $next_query->the_post();

          // if the next ID was set during the last iteration

          if ( $get_next == true ) {

            $next_link['id'] = $post->ID;
            $next_link['title'] = get_the_title ( $post->ID );
            $next_link['btn'] = __ ( 'Next page', 'fw' );

            $is_next_set = true;
            $get_next = false;
            break;

          }

          // if the iteration's ID is the current page ID
          // set the flag to get the next one

          if ( $post->ID == $current_ID ) {
            $get_next = true;
          } else {
            $get_next = false;
          }

        endwhile;

      endif;

      wp_reset_postdata();

    }*/

  }

  // fallback

  if ( $is_next_set == false ) {

    $next_link['id'] = get_option ( 'page_on_front' );
    $next_link['title'] = __ ( 'Return to home page', 'fw' );
    $next_link['btn'] = __ ( 'Home Page', 'fw' );

    $is_next_set = true;

  }

  return $next_link;

}
