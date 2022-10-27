<?php

	if ( !empty ( $block['terms'] ) ) {
		foreach ( $block['terms'] as $term ) {
			
			// each row

      $term_label = $term_list = '';

      switch ( $term['type'] ) {
				
				// taxonomy or meta

        case 'taxonomy' :

          $list_tax = get_taxonomy ( $term['taxonomy_list'] );

          $term_label = $list_tax->label;

          $term_list = get_the_term_list ( $GLOBALS['vars']['current_query']->ID, $term['taxonomy_list'], '<li>', '</li><li>', '</li>' );

          break;

        case 'meta' :

          $field_obj = get_field_object ( $term['field'] );
          $field_value = get_field ( $term['field'], $GLOBALS['vars']['current_query']->ID );
					
					if ( $field_obj ) {

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
						
					} else {
						
?>

<p class="alert alert-warning">Field object <code><?php echo $term['field']; ?></code> not found.</p>

<?php

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
