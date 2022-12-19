<?php

  //
  // ENQUEUE
  //

  wp_enqueue_script ( 'renderer' );

  if ( $block['type'] == 'share' ) {
    wp_enqueue_script ( 'share-widget' );
  } elseif ( $block['type'] == 'follow' ) {
    wp_enqueue_script ( 'follow-widget' );
  }
  
  //
  // DISPLAY SETTINGS
  //
  
  // global CSS
  
  // bg
  
  if (
    $block['social_menu_bg'] != '' &&
    $block['social_menu_bg'] != 'inherit'
  ) {
  
    $GLOBALS['css'] .= "\n\n" . '#' . $GLOBALS['elements']['current']['id'] . '-' . $block['type'] . ' .widget-menu,';
    $GLOBALS['css'] .= "\n" . '#' . $GLOBALS['elements']['current']['id'] . '-' . $block['type'] . ' .open .widget-trigger { background-color: var(--' . $block['social_menu_bg'] . '); }';
  
  }
  
  // text
  
  if (
    $block['social_menu_text'] != '' &&
    $block['social_menu_text'] != 'inherit'
  ) {
  
    $GLOBALS['css'] .= "\n" . '#' . $GLOBALS['elements']['current']['id'] . '-' . $block['type'] . ' .social-widget li { color: var(--' . $block['social_menu_text'] . '); }';
  
  }
  
  // link
  
  if (
    $block['social_menu_link'] != '' &&
    $block['social_menu_link'] != 'inherit'
  ) {
  
    $GLOBALS['css'] .= "\n\n" . '#' . $GLOBALS['elements']['current']['id'] . '-' . $block['type'] . ' .social-widget li a,';
    $GLOBALS['css'] .= "\n" . '#' . $GLOBALS['elements']['current']['id'] . '-' . $block['type'] . ' .open .social-trigger { color: var(--' . $block['social_menu_link'] . '); }';
  
  }
  
  // icon
  
  if (
    $block['social_menu_icon'] != '' &&
    $block['social_menu_icon'] != 'inherit'
  ) {
  
    $GLOBALS['css'] .= "\n\n" . '#' . $GLOBALS['elements']['current']['id'] . '-' . $block['type'] . ' .social-widget .icon { color: var(--' . $block['social_menu_icon'] . '); }';
  
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

  if ( $block['type'] == 'follow' ) {

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

  } elseif ( $block['type'] == 'share' ) {

    // labels

    if ( $GLOBALS['vars']['social']['share']['widget_icon'] != '' ) {
      $trigger_text .= '<span class="' . $GLOBALS['vars']['social']['share']['widget_icon'] . '"></span>';
    }

    if ( $GLOBALS['vars']['social']['share']['widget_text'] != '' ) {
      $trigger_text .= $GLOBALS['vars']['social']['share']['widget_text'];
    }

    // attributes

    switch ( $block['url'] ) {
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
        
      case 'id' :
        $share_URL = get_permalink ( $block['url_id'] );
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
          $widget_atts['tweet-text'] = get_the_title ( $GLOBALS['vars']['current_query']->ID ) . ' via @';
          
          if ( $block['url'] == 'id' ) {
            
            $widget_atts['tweet-text'] = get_the_title ( $block['url_id'] );
            
          }
        }

        $widget_items[] = $new_item;

      }

    } else {
      echo 'Social accounts not set.';
    }

  }

?>

<div
  id="<?php echo get_current_element_ID(); ?>-<?php echo $block['type']; ?>"
  class="<?php echo get_sub_field ( 'classes' ); ?>"
>

  <div
    class="renderable social-widget type-<?php echo $block['type']; ?> display-<?php echo $block['display']; ?>"
    data-renderable-type="<?php echo $block['type']; ?>"

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
      
      // dumpit ( $widget_items );

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
