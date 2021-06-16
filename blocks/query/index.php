<?php

  get_header();

  //
  // HERO
  //

  $hero_ID = get_option ( 'page_for_posts' );

  if ( get_field ( 'hero_title', $hero_ID ) != '' ) {
    $hero_title = get_field ( 'hero_title', $hero_ID );
  } else {
    $hero_title = get_the_title ( $hero_ID );
  }

?>

<section class="section section--hero section--hero--title">
  <div class="wrap cf">
    <h1 class="os-animation half" data-os-animation="fadeInUp"><?php echo wptexturize ( $hero_title ); ?></h1>

    <?php

      if ( get_field ( 'hero_subtitle', $hero_ID ) != '' ) {

    ?>

    <h3 class="os-animation half" data-os-animation="fadeInUp"><?php the_field ( 'hero_subtitle', $hero_ID ); ?></h3>

    <?php

      }

    ?>
  </div>
</section>

<section class="section section--updates section--masonry section--padding" data-header-style="hidden">
  <div class="wrap cf">

    <?php

      if ( have_posts() ) :

    ?>

    <div id="blog-filter">
      <div class="filter-menu type-filter" data-filter-term="cat_id">
        <h5>Filter by category <span class="clear-btn">Clear</span></h5>

        <ul>
          <?php

            $all_cats = get_terms ( array (
              'taxonomy' => 'category',
              'hide_empty' => true,
            ) );

            foreach ( $all_cats as $cat ) {
              echo "\n\t\t\t\t" . '<li class="filter-item" data-filter-value="' . $cat->term_id . '">' . $cat->name . '</li>';
            }

          ?>
        </ul>
      </div>

      <div class="filter-menu type-sort" data-filter-term="date">
        <h5>Sort by date</h5>

        <ul>
          <li class="filter-item selected" data-filter-value="desc">Newest First</li>
          <li class="filter-item" data-filter-value="asc">Oldest First</li>
        </ul>
      </div>
    </div>

    <?php

      endif;

    ?>

    <div id="posts" class="section--masonry__grid grid--updates section--masonry__grid--all section--masonry__grid--masonry">
      <div class="gutter"></div>

      <?php

        if ( have_posts() ) :

          while ( have_posts() ) : the_post();

            include ( locate_template ( 'template-parts/post-card.php' ) );

          endwhile;

        else :

      ?>

      <article class="section--grid__grid__item section--masonry__grid__item full-width animation-delay os-animation" data-os-animation="fadeInUp">
        <div class="section--grid__grid__content" id="section--grid__grid__content">
          <header><?php _e ( 'No posts found matching the selected filters.', 'mobby-labels' ); ?></header>
        </div>
      </article>

      <?php

        endif;

      ?>

    </div>

  </div>
</section>


<div id="output" style="display: none;">
  <?php
    print_r($wp_query);
  ?>
</div>

<?php

  get_footer();

?>
