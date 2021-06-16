<?php

  if ( have_rows ( 'terms' ) ) {
    while ( have_rows ( 'terms' ) ) {
      the_row();

      $term_label = $term_list = '';

      switch ( get_sub_field ( 'type' ) ) {

        case 'taxonomy' :

          $list_tax = get_taxonomy ( get_sub_field ( 'taxonomy_list' ) );

          $term_label = $list_tax->label;

          // $post_terms = get_the_terms ( get_the_ID(), get_sub_field ( 'taxonomy_list' ) );

          $term_list = get_the_term_list ( get_the_ID(), get_sub_field ( 'taxonomy_list' ), '<li>', '</li><li>', '</li>' );

          break;

        case 'meta' :

          $field_key = get_sub_field ( 'field' );
          $field_obj = get_field_object ( $field_key );
          $field_value = get_field ( $field_key, get_the_ID() );

          $term_label = $field_obj['label'];

          if ( is_array ( $field_value ) ) {
            $term_list = $field_value['label'];
          } else {
            $term_list = $field_value;
          }

          break;

      }

      if ( $term_list != '' ) {

?>

<div class="term-list">
  <div class="term-label"><?php echo $term_label; ?></div>

  <ul class="term-items"><?php echo $term_list; ?></ul>
</div>

<?php

      }

    }
  }

?>
