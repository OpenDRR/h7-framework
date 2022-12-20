<?php

  //
  // ENQUEUE
  //

  wp_enqueue_script ( 'post-carousel', get_bloginfo ( 'template_directory' ) . '/resources/js/post-carousel.js', array ( 'jquery' ), NULL, true );
  wp_enqueue_script ( 'slick' );

  wp_enqueue_script ( 'renderer' );

  if ( have_rows ( 'carousel') ) {
    while ( have_rows ( 'carousel') ) {
      the_row();

      $settings = carousel_setup ( $current_block_ID );

    }
  }

  $query_block[$current_block_ID] = array_merge ( $settings, $query_block[$current_block_ID] );

  // SLICK SETTINGS

  // $query_block[$current_block_ID]['classes'] = array(
  //   'container' => array(),
  //   'content' => array(),
  //   'controls' => array()
  // );
  //
  // if ( have_rows ( 'carousel_classes' ) ) {
  //   while ( have_rows ( 'carousel_classes' ) ) {
  //     the_row();
  //
  //     $query_block[$current_block_ID]['classes']['container'][] = get_sub_field ( 'container' );
  //     $query_block[$current_block_ID]['classes']['content'][] = get_sub_field ( 'content' );
  //     $query_block[$current_block_ID]['classes']['controls'][] = get_sub_field ( 'controls' );
  //
  //   }
  // }

  //

  // if set to infinite, there needs to be the exact number of posts to fill the slides

  $query_block[$current_block_ID]['settings']['infinite'] = true;

  if ( get_sub_field ( 'paginate' ) == 1 ) {

    $query_block[$current_block_ID]['settings']['infinite'] = false;

    $new_query['args']['posts_per_page'] = $query_block[$current_block_ID]['settings']['slidesToShow'];

    $query_block[$current_block_ID]['settings']['slidesToScroll'] = $query_block[$current_block_ID]['settings']['slidesToShow'];

    // $query_block[$current_block_ID]['slick']['slidesToScroll'] = 2;

  }

  /*echo '<pre>';

  print_r($_GET);

  echo '<hr>';

  print_r($new_query['args']);

  echo '<hr>';

  while ($new_query['results']->have_posts()) :
    $new_query['results']->the_post();

    the_title();
    echo '<br>';
  endwhile;
  echo '</pre>';
wp_reset_postdata();*/

  //
  // DISPLAY SETTINGS
  //

  // template

  $query_block[$current_block_ID]['display']['template'] = '';

  if ( locate_template ( 'previews/' . get_sub_field ( 'template' ) . '.php' ) != '' ) {
    $query_block[$current_block_ID]['display']['template'] = 'previews/' . get_sub_field ( 'template' ) . '.php';
  }

  //
  // LAYOUT
  //

  if ( isset ( $query_block[$current_block_ID]['items'] ) ) {

?>

<div
  id="<?php echo $current_block_ID; ?>-carousel"
  class="query-items-wrap query-type-carousel renderable post-carousel-wrap <?php echo implode ( ' ', $query_block[$current_block_ID]['classes']['container'] ); ?>"
  data-renderable-type="carousel"
>

  <div class="row">
    <div
      class="post-carousel"
      data-slick='<?php echo json_encode ( $query_block[$current_block_ID]['settings'] ); ?>'

      <?php

        if ( $query_block[$current_block_ID]['settings']['infinite'] == false ) {

          echo 'data-page="' . $query_block[$current_block_ID]['args']['paged'] . '"';

        }

      ?>

    >

      <?php

        $item_num = 1;

        // $old_post = $post;

        foreach ( $query_block[$current_block_ID]['items'] as $item ) {

          // $post = $item;

          // setup_postdata ( $post );

      ?>


      <div id="<?php echo $current_block_ID; ?>-item-<?php echo $item['id']; ?>" class="query-item post-preview type-<?php echo $item['post_type']; ?> <?php echo implode ( ' ', $query_block[$current_block_ID]['classes']['content'] ); ?>">

        <?php

          if ( $query_block[$current_block_ID]['display']['template'] != '' ) {

            include ( locate_template ( $query_block[$current_block_ID]['display']['template'] ) );

          } elseif ( locate_template ( 'previews/' . get_post_type() . '.php'  ) != '' ) {

            include ( locate_template ( 'previews/' . get_post_type() . '.php' ) );

          } else {

        ?>

        <div class="card h-100">
          <?php

            if ( has_post_thumbnail() ) {

          ?>
          <img src="<?php the_post_thumbnail_url ( 'medium' ); ?>" class="card-img-top" alt="">
          <?php

            }

          ?>

          <div class="card-body">
            <h5 class="cart-title"><a href="<?php echo $item['permalink']; ?>"><?php echo $item['title']; ?></a></h5>

            <p class="card-text"><?php echo custom_excerpt ( 10, $item['id'] ); ?></p>

            <a href="<?php echo $item['permalink']; ?>" class="card-link"><?php _e ( 'Learn More', 'fw' ); ?></a>
          </div>
        </div>

        <?php

          }

        ?>

      </div>

      <?php

          $item_num++;

        }

        // wp_reset_postdata();

        // $post = $old_post;

        // setup_postdata ( $post );

      ?>

    </div>
  </div>
</div>

<?php

  if (
    $query_block[$current_block_ID]['settings']['arrows'] == true ||
    $query_block[$current_block_ID]['settings']['dots'] == true
  ) {

?>

<div class="carousel-controls <?php echo implode ( ' ', $query_block[$current_block_ID]['classes']['controls'] ); ?>">
  <?php

    if ( $query_block[$current_block_ID]['settings']['arrows'] == true ) {

  ?>

  <div id="<?php echo $current_block_ID; ?>-prev" class="carousel-arrow post-carousel-prev order-1"><i class="fas fa-arrow-left"></i></div>
  <div id="<?php echo $current_block_ID; ?>-next" class="carousel-arrow post-carousel-next order-3"><i class="fas fa-arrow-right"></i></div>

  <?php

    }

    if ( $query_block[$current_block_ID]['settings']['dots'] == true ) {

  ?>

  <div id="<?php echo $current_block_ID; ?>-dots" class="carousel-dots order-2 flex-grow-1"></div>

  <?php

    }

  ?>

</div>

<?php

  }

?>

<?php

  } else {

    echo '<div class="bg-warning">';
    _e ( 'No items found.', 'fw' );
    echo '</div>';

  }

?>
