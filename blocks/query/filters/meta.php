<?php
	
	// get the field object
	
	$field_obj = acf_get_field ( $filter['key'] );
	
	if ( $field_obj['type'] == 'post_object' ) {
				
		// query the post type(s) to grab all of the existing values
		
		$val_query = get_posts ( array (
			'post_type' => $query_block[$current_block_ID]['args']['post_type'],
			'posts_per_page' => -1,
			'post_status' => 'publish',
			'orderby' => 'meta_value',
			'order' => 'asc',
			'meta_query' => array (
				array (
					'key' => $filter['key'],
					'value' => '',
					'compare' => '!='
				)
			)
		) );
		
		if ( !empty ( $val_query ) ) {
			
			$filter['items'] = array();
			
			foreach ( $val_query as $item ) {
				
				$this_val = get_field ( $filter['key'], $item->ID );
				
				$filter['items'][$this_val] = $this_val;
				
				if ( $field_obj['type'] == 'post_object' ) {
					$filter['items'][$this_val] = get_the_title ( $this_val );
				}
				
			}
			
		}
		
		// $filter['items'] = array_unique ( $filter['items'] );
		
	} elseif ( $field_obj['type'] == 'select' ) {
		
		$filter['items'] = $field_obj['choices'];
		
	}
	
	if ( !empty ( $filter['items'] ) ) {
		
?>

<h6 class="query-filter-head <?php echo implode ( ' ', $filter_heading_classes ); ?>">By <?php echo $field_obj['label']; ?></h6>

<ul
	class="query-filter-list <?php echo implode ( ' ', $filter_list_classes ); ?>"
	data-multi="<?php echo ( $filter['multi'] == 1 ) ? 'true' : 'false'; ?>"
	data-icons="far fa-square|far fa-times"
>

	<?php

		foreach ( $filter['items'] as $val => $label ) {

	?>

	<li class="query-filter-item d-flex align-items-center" data-filter-type="cf" data-filter-key="<?php echo $filter['key']; ?>" data-filter-value="<?php echo $val; ?>">
		<i class="icon far fa-square fa-sm fa-fw mr-2"></i>
		<span class="label"><?php echo $label; ?></span>
	</li>

	<?php

		}

	?>

</ul>

<?php
		
	}

?>