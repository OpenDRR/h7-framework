<div class="text">
  <?php

    $hero_text = '';

    switch ( get_sub_field ( 'content' ) ) {

      case 'field' :
        if ( have_rows ( 'hero_content' ) ) {
          while ( have_rows ( 'hero_content' ) ) {
            the_row();

            $hero_text = get_sub_field ( 'text' );

          }
        }
        break;

      case 'excerpt' :
        $hero_text = get_the_excerpt ( get_the_ID() );
        break;

      case 'custom' :
        $hero_text = get_sub_field ( 'text' );
        break;

    }

  ?>

  <?php echo apply_filters ( 'the_content', $hero_text ); ?>
</div>
