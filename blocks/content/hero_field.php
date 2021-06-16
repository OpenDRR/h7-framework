<?php

  $hero_text = '';
  $hero_field = get_sub_field ( 'field' );

  $field_ID = get_the_ID();

  if ( is_archive() ) {
    $field_ID = $current_query->taxonomy . '_' . $current_query->term_id;
  }

  // check custom field values for text

  if ( have_rows ( 'hero_content', $field_ID ) ) {
    while ( have_rows ( 'hero_content', $field_ID ) ) {
      the_row();

      $hero_text = get_sub_field ( $hero_field );

    }
  }

  // if it's still empty

  if ( $hero_text == '' ) {

    if ( is_archive() ) {

      if ( $hero_field == 'title' ) {
        $hero_text = $current_query->name;
      } elseif ( $hero_field == 'text' ) {
        $hero_text = $current_query->description;
      }

    } else {

      if ( $hero_field == 'title' ) {
        $hero_text = get_the_title();
      } elseif ( $hero_field == 'text' ) {
        $hero_text = get_the_excerpt ( get_the_ID() );
      }

    }

  }

  //
  // OUTPUT
  //

  if ( $hero_text != '' ) {

    if ( $hero_field == 'title' ) {

?>

<h1 class="<?php echo get_sub_field ( 'classes' ); ?>"><?php echo $hero_text; ?></h1>

<?php

    } elseif ( $hero_field == 'text' ) {

?>

<div class="text">
  <?php echo apply_filters ( 'the_content', $hero_text ); ?>
</div>

<?php

    }

  }

?>
