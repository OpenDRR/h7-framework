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

					switch ( $field_obj['type'] ) {
						case 'post_object' :

							// post object field

							if ( is_array ( $field_value ) ) {

								// multiple selection

								foreach ( $field_value as $post_obj ) {
									$term_list .= '<li>' . get_the_title ( $post_obj ) . '</li>';
								}

							} elseif ( $field_value != '' ) {

								// single selection

								$term_list = get_the_title ( $field_value );

							}

							break;

						default :

							if ( is_array ( $field_value ) ) {

								foreach ( $field_value as $item ) {
									$term_list .= '<li>' . $item . '</li>';
								}

		          } else {
		            $term_list = $field_value;
		          }

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
