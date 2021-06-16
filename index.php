<?php

  get_header();

?>

<?php

  if ( have_posts() ) : while ( have_posts() ) : the_post();

?>

<article <?php post_class(); ?>>
  <h4><?php the_title(); ?></h4>

  <?php

    the_content();

  ?>
</article>

<?php

  endwhile; endif;

  get_footer();

?>
