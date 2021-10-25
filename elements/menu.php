<?php

  //
  // FUNCTIONS
  //

  // BUILD MENU FROM ARRAY

  if ( !function_exists ( 'fw_build_menu' ) ) {

    function fw_build_menu ( array &$elements, $parent_id = 0, $level ) {

      $branch = array();

      $i = 0;

      foreach ( $elements as &$element ) {

        if ( $element['parent'] == $parent_id ) {

          $children = fw_build_menu ( $elements, $element['id'], $level + 1 );

          if ( $children ) {
            $element['children'] = $children;
          }

          $branch[] = $element;

          unset ( $elements[$i] );

        }

        $i++;

      }

      return $branch;

    }

  }

  //
  // OUTPUT
  //

  if ( !function_exists ( 'fw_menu_output' ) ) {

    function fw_menu_output ( $menu, $level ) {

      global $element_class;
      global $item_class;
      global $link_class;

      echo '<ul class="menu-level-' . $level . ' ';

      if ( $level == 1 ) echo implode ( ' ' , $element_class );

      echo '">';

      foreach ( $menu as $item ) {

        echo '<li class="';

        if ( $item['url'] == $GLOBALS['vars']['current_url'] ) {
          echo 'current-nav-item ';
        }

        // if the page is an ancestor of the current ID

        if ( in_array ( $item['id'], $GLOBALS['vars']['current_ancestors'] ) ) {
          echo 'ancestor-nav-item ';
        }

        // other classes

        if ( isset ( $item_class ) && is_array ( $item_class ) ) {
          echo implode ( ' ', $item_class );
        }

        if ( isset ( $item['class'] ) ) echo ' ' . $item['class'];

        echo '">';

          echo '<a href="' . $item['url'] . '"';

          if ( isset ( $item['target'] ) && $item['target'] == 'blank' ) {
            echo ' target="_blank"';
          }

          echo ' class="';

          if ( $item['url'] == $GLOBALS['vars']['current_url'] ) {
            echo 'current-nav-link ';
          }

          if ( isset ( $item['classes'] ) && is_array ( $item['classes'] ) ) {

            echo implode ( ' ' , $item['classes'] ) . ' ';

          }

          if ( isset ( $link_class ) && is_array ( $link_class ) ) {

            echo implode ( ' ' , $link_class );

          }

          echo '">';

          if ( isset ( $item['icon'] ) && $item['icon'] != '' ) {
            echo '<i class="icon ' . $item['icon'] . ' mr-3"></i>';
          }

          echo $item['title'] . '</a>';

          if ( isset ( $item['children'] ) ) {

            fw_menu_output ( $item['children'], $level + 1 );

          }

        echo '</li>';

      }

      echo '</ul>';

    }

  }

  //
  // MENU TYPES
  //

  // WP MENU

  if ( !function_exists ( 'fw_wp_menu' ) ) {

    function fw_wp_menu ( $menu_ID ) {

      // get menu items

      $nav_items = wp_get_nav_menu_items ( $menu_ID );

			// if ( current_user_can( 'administrator' ) ) {
			// 	echo '<pre>';
			// 	print_r(wp_get_nav_menu_items($menu_ID));
			// 	echo '</pre>';
			// }

      // convert to array

      $menu_items = array();

      foreach ( $nav_items as &$item ) {

        $menu_items[] = array (
          // 'id' => $item->object_id,
					'id' => $item->ID,
          'url' => $item->url,
          'title' => $item->title,
          'classes' => $item->classes,
          // 'parent' => $item->post_parent,
					'parent' => $item->menu_item_parent,
          'icon' => get_field ( 'icon', $item->ID )
        );

      }

			// if ( current_user_can( 'administrator' ) ) {
	    //   echo '<pre>';
			//
	    //   print_r($menu_items);
	    //   echo '<hr>';
			//
	    //   echo '</pre>';
			// }

      return fw_build_menu ( $menu_items, 0, 1 );

    }

  }

  // CHILD MENU

  if ( !function_exists ( 'fw_child_menu' ) ) {

    function fw_child_menu() {

      // get all children of the top parent

      $top_parent = get_top_parent();

      $all_children = get_pages ( array (
        'posts_per_page' => -1,
        'sort_column' => 'menu_order',
        'sort_order' => 'asc',
        'child_of' => $top_parent
      ) );

      // convert to array

      $menu_items = array();

      foreach ( $all_children as $item ) {

        $menu_items[] = array (
          'id' => $item->ID,
          'url' => get_permalink ( $item->ID ),
          'title' => get_the_title ( $item->ID ),
          'parent' => $item->post_parent,
          'icon' => ''
        );

      }

      $menu = fw_build_menu ( $menu_items, get_top_parent(), 1 );

      array_unshift ( $menu, array (
        'id' => $top_parent,
        'url' => get_permalink ( $top_parent ),
        'title' => get_the_title ( $top_parent ),
        'parent' => (int) $top_parent,
        'icon' => ''
      ) );

      return $menu;

    }

  }

  // ACF MENU

  if ( !function_exists ( 'fw_acf_menu' ) ) {

    function fw_acf_menu ( $items ) {

      $current_parent = 0;

      // convert the items field to array

      $menu_items = array();

      $item_ID = 1;

      $i = 0;

      foreach ( $items as $item ) {

        $new_item = array();

        if ( $item['type'] == 'post' ) {

          $this_ID = $item['post'];

          $new_item['url'] = get_permalink ( $this_ID );
          $new_item['title'] = get_the_title ( $this_ID );

        } else {

          $this_ID = $item_ID;

          $new_item['url'] = $item['url'];
          $new_item['title'] = $item['text'];

          $item_ID++;

        }


        if ( $item['text'] != '' ) {
          $new_item['title'] = $item['text'];
        }

        $new_item['class'] = $item['class'];

        $new_item['id'] = $this_ID;

        if ( $item['hierarchy'] == 'parent' ) {

          $current_parent = $new_item['id'];
          $new_item['parent'] = 0;

        } else {

          $new_item['parent'] = $current_parent;

        }

        $new_item['target'] = $item['target'];

        $menu_items[$i] = $new_item;

        $i++;

      }

      // echo '<pre>';
      //
      // print_r($menu_items);
      // echo '<hr>';
      //
      // echo '</pre>';

      // print_r(fw_build_menu ( $menu_items, 0, 1 ));
      // echo '<hr>';

      return fw_build_menu ( $menu_items, 0, 1 );

    }

  }

  //
  // VARIABLES
  //

  // global menu/item/link classes

  global $element_class;
  global $item_class;
  global $link_class;

  // the menu

  $menu = array();


  //
  // GET MENU BY TYPE
  //

  switch ( get_sub_field ( 'type' ) ) {

    case 'wp' :

      // WP MENU

      $menu = fw_wp_menu ( get_sub_field ( 'menu' ) );

      break;

    case 'manual' :

      // ACF MENU

      $menu = fw_acf_menu ( get_sub_field ( 'items' ) );

      break;

    case 'hierarchy' :

      // CURRENT PAGE HIERARCHY

      $menu = fw_child_menu();

      break;

  }

  //
  // OUTPUT
  //

  fw_menu_output ( $menu, 1 );
