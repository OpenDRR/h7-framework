<?php

  //
  // ENQUEUE
  //

  wp_enqueue_script ( 'renderer' );

  $widget_type = get_sub_field ( 'type' );

  if ( $widget_type == 'share' ) {
    wp_enqueue_script ( 'share-widget' );
  } elseif ( $widget_type == 'follow' ) {
    wp_enqueue_script ( 'follow-widget' );
  }

  //
  // DISPLAY SETTINGS
  //

  // global CSS

  // bg

  if (
    get_sub_field ( 'social_menu_bg' ) != '' &&
    get_sub_field ( 'social_menu_bg' ) != 'inherit'
  ) {

    $GLOBALS['css'] .= "\n\n" . '#' . $GLOBALS['elements']['current']['id'] . '-' . $widget_type . ' .widget-menu,';
    $GLOBALS['css'] .= "\n" . '#' . $GLOBALS['elements']['current']['id'] . '-' . $widget_type . ' .open .widget-trigger { background-color: var(--' . get_sub_field ( 'social_menu_bg' ) . '); }';

  }

  // text

  if (
    get_sub_field ( 'social_menu_text' ) != '' &&
    get_sub_field ( 'social_menu_text' ) != 'inherit'
  ) {

    $GLOBALS['css'] .= "\n" . '#' . $GLOBALS['elements']['current']['id'] . '-' . $widget_type . ' .social-widget li { color: var(--' . get_sub_field ( 'social_menu_text' ) . '); }';

  }

  // link

  if (
    get_sub_field ( 'social_menu_link' ) != '' &&
    get_sub_field ( 'social_menu_link' ) != 'inherit'
  ) {

    $GLOBALS['css'] .= "\n\n" . '#' . $GLOBALS['elements']['current']['id'] . '-' . $widget_type . ' .social-widget li a,';
    $GLOBALS['css'] .= "\n" . '#' . $GLOBALS['elements']['current']['id'] . '-' . $widget_type . ' .open .social-trigger { color: var(--' . get_sub_field ( 'social_menu_link' ) . '); }';

  }

  // icon

  if (
    get_sub_field ( 'social_menu_icon' ) != '' &&
    get_sub_field ( 'social_menu_icon' ) != 'inherit'
  ) {

    $GLOBALS['css'] .= "\n\n" . '#' . $GLOBALS['elements']['current']['id'] . '-' . $widget_type . ' .social-widget .icon { color: var(--' . get_sub_field ( 'social_menu_icon' ) . '); }';

  }

  //
  // VARS
  //

  $trigger_text = '';
  $widget_atts = array();
  $widget_items = array();

  //
  // WIDGET TYPE
  // share or follow
  //

  if ( get_sub_field ( 'type' ) == 'follow' ) {

    if ( isset ( $GLOBALS['vars']['social'] ) ) {

      // labels

      if ( $GLOBALS['vars']['social']['follow']['widget_icon'] != '' ) {
        $trigger_text .= '<span class="' . $GLOBALS['vars']['social']['follow']['widget_icon'] . '"></span>';
      }

      if ( $GLOBALS['vars']['social']['follow']['widget_text'] != '' ) {
        $trigger_text .= $GLOBALS['vars']['social']['follow']['widget_text'];
      }

      // items

      if (
        isset ( $GLOBALS['vars']['social']['follow']['items'] ) &&
        is_array ( $GLOBALS['vars']['social']['follow']['items'] ) &&
        !empty ( $GLOBALS['vars']['social']['follow']['items'] )
      ) {

        foreach ( $GLOBALS['vars']['social']['follow']['items'] as $item ) {
          $new_item = $GLOBALS['vars']['social']['sites'][$item];
          $new_item['url'] = '//' . $item . '.com/' . $new_item['account'];
          $widget_items[] = $new_item;
        }

      } else {

        echo '<span class="alert alert-warning">No social accounts configured.</span>';

      }

    } else {

      echo '<span class="alert alert-warning">Social component settings not found.</span>';

    }

  } else if ( get_sub_field ( 'type' ) == 'share' ) {

    // labels

    if ( $GLOBALS['vars']['social']['share']['widget_icon'] != '' ) {
      $trigger_text .= '<span class="' . $GLOBALS['vars']['social']['share']['widget_icon'] . '"></span>';
    }

    if ( $GLOBALS['vars']['social']['share']['widget_text'] != '' ) {
      $trigger_text .= $GLOBALS['vars']['social']['share']['widget_text'];
    }

    // attributes

    switch ( get_sub_field ( 'url' ) ) {
      case 'site' :
        $share_URL = $GLOBALS['vars']['site_url'];
        break;

      case 'page' :
				if ( is_home() ) {
					$share_URL = get_permalink ( get_option ( 'page_for_posts' ) );
				} elseif ( is_author() ) {
					$share_URL = get_author_posts_url ( $GLOBALS['vars']['current_query']->ID );
				} elseif ( is_archive() ) {
					$share_URL = get_term_link ( $GLOBALS['vars']['current_query']->term_id );
				} else {
        	$share_URL = get_permalink();
				}

        break;

      case 'section' :
        $share_URL = $GLOBALS['vars']['social']['redirect_URL'] . '?path=' . get_permalink() . '&hash=' . $section_ID;
        break;

    }

    $widget_atts['url'] = $share_URL;

    // items

    if (
      isset ( $GLOBALS['vars']['social']['share']['items'] ) &&
      !empty ( $GLOBALS['vars']['social']['share']['items'] )
    ) {
      foreach ( $GLOBALS['vars']['social']['share']['items'] as $item ) {

        $new_item = $GLOBALS['vars']['social']['sites'][$item];

        $new_item['url'] = '#';
        $new_item['atts'] = array();

        // tweet text

        if ( $item == 'twitter' ) {
          $new_item['data-tweet-text'] = get_the_title() . 'via @';
        }

        $widget_items[] = $new_item;

      }

    } else {
      echo 'Social accounts not set.';
    }

  }

?>

<div
  id="<?php echo get_current_element_ID(); ?>-<?php echo get_sub_field ( 'type' ); ?>"
  class="<?php echo get_sub_field ( 'classes' ); ?>"
>

  <div
    class="renderable social-widget type-<?php the_sub_field ( 'type' ); ?> display-<?php the_sub_field ( 'display' ); ?>"
    data-renderable-type="<?php echo get_sub_field ( 'type' ); ?>"

    <?php

      foreach ( $widget_atts as $att => $val ) {
        echo 'data-social-' . $att . '="' . $val . '" ';
      }

    ?>
  >

    <div class="widget-trigger">
      <?php echo $trigger_text; ?>
    </div>

    <ul class="widget-menu">
      <?php

        foreach ( $widget_items as $item ) {

      ?>

      <li
        class="widget-menu-item"
        data-social-site="<?php echo $item['site']; ?>"
        data-social-account="<?php echo $item['account']; ?>"
      >
        <a href="<?php echo $item['url']; ?>" target="_blank" class="widget-menu-link site-<?php echo $item['site']; ?>">
          <i class="icon <?php echo $item['icon']; ?>"></i>
          <span class="site-name"><?php echo $item['name']; ?></span>
        </a>
      </li>

      <?php

        }

      ?>

    </ul>

  </div>

</div>
