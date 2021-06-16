<?php

  wp_enqueue_script ( 'post-grid', get_bloginfo ( 'template_directory' ) . '/resources/js/post-grid.js', array ( 'jquery' ), NULL, true );

  wp_enqueue_script ( 'sticky-kit' );
  wp_enqueue_script ( 'renderer' );

  $grid_class = 'query-items-wrap query-type-grid renderable';

  if (
    isset ( $query_block[$current_block_ID]['filters'] ) &&
    !empty ( $query_block[$current_block_ID]['filters'] )
  ) {

    $grid_class .= ' has-filter';

  }

?>

<div
  id="<?php echo $current_block_ID; ?>-grid"
  class="<?php echo $grid_class; ?>"
  data-renderable-type="post_grid"
  data-filters='<?php echo ( isset ( $query_block[$current_block_ID]['filter_args'] ) ) ? json_encode ( $query_block[$current_block_ID]['filter_args'] ) : ''; ?>'
>

  <?php

    //
    // FILTER
    //

    if (
      isset ( $query_block[$current_block_ID]['filters'] ) &&
      !empty ( $query_block[$current_block_ID]['filters'] )
    ) {

			include ( locate_template ( 'blocks/query/filter-toggle.php' ) );
			include ( locate_template ( 'blocks/query/filters.php' ) );

    }

    //
    // ITEMS
    //

    if (
      isset ( $query_block[$current_block_ID]['items'] ) &&
      !empty ( $query_block[$current_block_ID]['items'] )
    ) {

  ?>

  <div id="<?php echo $current_block_ID; ?>-items" class="post-grid-wrap query-item-wrap row">

    <?php

      // LOOP

      $col_num = 1;

      foreach ( $query_block[$current_block_ID]['items'] as $item ) {

    ?>

    <div id="<?php echo $current_block_ID; ?>-item-<?php echo $item['id']; ?>" class="query-item <?php echo $query_block[$current_block_ID]['display']['columns']; ?>">

      <?php

        if ( isset ( $query_block[$current_block_ID]['display']['template'] ) && $query_block[$current_block_ID]['display']['template'] != '' ) {

					echo '<!-- ' . $query_block[$current_block_ID]['display']['template'] . '.php -->';

					// card template set by field

          include ( locate_template ( $query_block[$current_block_ID]['display']['template'] ) );

        } elseif ( locate_template ( 'previews/' . $item['post_type'] . '-card.php'  ) != '' ) {

					echo '<!-- ' . $item['post_type'] . '-card.php -->';

					// card template by post type

          include ( locate_template ( 'previews/' . $item['post_type'] . '-card.php' ) );

        } else {

					echo '<!-- card.php -->';

					// default card

					include ( locate_template ( 'previews/card.php' ) );

        }

      ?>

    </div>

    <?php

      }

    ?>

  </div>

  <?php

      if ( !isset ( $query_block[$current_block_ID]['paginate'] ) ) {
        $query_block[$current_block_ID]['paginate'] = false;
      }

      if ( get_sub_field ( 'paginate' ) == 1 ) {
        $query_block[$current_block_ID]['paginate'] = true;
      }

      if ( $query_block[$current_block_ID]['paginate'] == true ) {

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

      echo '<div class="bg-white col-12 mb-5 p-3">No items found.</div>';

    }

  ?>

</div>
