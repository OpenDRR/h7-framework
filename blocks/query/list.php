<?php

  //
  // ENQUEUE
  //

  wp_enqueue_script ( 'post-grid', get_bloginfo ( 'template_directory' ) . '/resources/js/post-grid.js', array ( 'jquery' ), NULL, true );

  wp_enqueue_script ( 'renderer' );

?>

<div
  id="<?php echo $current_block_ID; ?>-list"
  class="query-items-wrap query-type-list renderable"
  data-renderable-type="post_list"
>

  <?php

    if ( isset ( $query_block[$current_block_ID]['items'] ) ) {

  ?>

  <ul id="<?php echo $current_block_ID; ?>-items" class="post-list-wrap query-item-wrap list-group">

    <?php

      // LOOP

      $col_num = 1;

      // $old_post = $post;

      foreach ( $query_block[$current_block_ID]['items'] as $item ) {

        // $post = $item;

        // setup_postdata ( $post );

    ?>

    <li id="<?php echo $current_block_ID; ?>-item-<?php echo $item['id']; ?>" class="query-item list-group-item post-preview type-<?php echo $item['post_type']; ?>">

      <?php

        if ( $query_block[$current_block_ID]['display']['template'] != '' ) {

          include ( locate_template ( $query_block[$current_block_ID]['display']['template'] ) );

        } elseif ( locate_template ( 'previews/' . $item['post_type'] . '.php'  ) != '' ) {

          include ( locate_template ( 'previews/' . $item['post_type'] . '.php' ) );

        } else {

      ?>

      <a href="<?php echo $item['permalink']; ?>"><?php echo $item['title']; ?></a>

      <?php

        }

      ?>

    </li>

    <?php

      }

    ?>

  </ul>

  <?php

      if ( get_sub_field ( 'paginate' ) == 1 ) {

        if ( $query_block[$current_block_ID]['args']['paged'] < $query_block[$current_block_ID]['results']->max_num_pages ) {

          $next_btn_URL = $GLOBALS['vars']['current_url'];

          if ( strpos ( $next_btn_URL, '?' ) !== false ) {
            $next_btn_URL = explode ( '?', $next_btn_URL )[0];
          }

          if ( isset ( $query_block[$current_block_ID]['args']['paged'] ) ) {
            $next_btn_URL .= '?' . $current_block_ID . '-page=';
            $next_btn_URL .= ( ( (int) $query_block[$current_block_ID]['args']['paged'] ) + 1 );
          }

  ?>

  <div class="post-grid-next acf-query-next text-center">
    <a href="<?php echo $next_btn_URL; ?>" class="post-grid-next-btn acf-query-next-btn prevent-default btn btn-primary"><?php _e ( 'Load more', 'fw' ); ?></a>
  </div>

  <?php

        }

      }

      unset ( $new_query );

    } else {

      echo '<div class="bg-warning col-6 offset-3">No items found.</div>';

    }

  ?>

</div>
