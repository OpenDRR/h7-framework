<?php

  get_header();

  //
  // HERO
  //

  // include ( locate_template ( 'template/hero/hero.php' ) );

?>

<?php

  if ( have_posts() ) :

?>

<section id="search-intro" class="fw-section fw-page-section">
  <div class="fw-container container-fluid">
    <div class="row">
      <div class="col-10 offset-1 text-center">
        <h4>Your search for <em class="search-term"><?php echo get_search_query(); ?></em> returned <span class="search-count"><?php echo $wp_query->post_count; ?></span> result<?php if ( $wp_query->post_count > 1 || $wp_query->post_count == 0 ) echo 's'; ?>.</h4>
      </div>
    </div>
  </div>
</section>

<section id="search-results" class="fw-section fw-page-section">
  <div class="fw-container container-fluid">

  <?php

		$i = 1;

    while ( have_posts() ) : the_post();

			if ( locate_template ( 'template/search-result.php' ) != '' ) {

				include ( locate_template ( 'template/search-result.php' ) );

			}

    endwhile;

  ?>

  </div>
</section>

<?php

  endif;

  get_footer();

?>
