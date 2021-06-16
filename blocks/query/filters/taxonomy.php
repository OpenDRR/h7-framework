<?php

	$filter_tax = get_taxonomy ( $filter['taxonomy'] );
	$filter_tax_labels = get_taxonomy_labels ( $filter_tax );

	if ( !empty ( $filter['terms'] ) ) {

		$filter_tax_terms = array();

		foreach ( $filter['terms'] as $term ) {
			// $filter_term = get_term_by ( 'slug', $term, $filter['taxonomy'] );
			//
			// print_r($filter_term);

			$filter_tax_terms[] = get_term_by ( 'slug', $term, $filter['taxonomy'] );
		}

	} else {

		$filter_tax_terms = get_terms ( array (
			'taxonomy' => $filter['taxonomy'],
			'hide_empty' => true
		) );

	}

?>

<h6 class="query-filter-head <?php echo implode ( ' ', $filter_heading_classes ); ?>">By <?php echo $filter_tax_labels->singular_name; ?></h6>

<ul
	class="query-filter-list <?php echo implode ( ' ', $filter_list_classes ); ?>"
	data-multi="<?php echo ( $filter['multi'] == 1 ) ? 'true' : 'false'; ?>"
	data-icons="far fa-square|far fa-times"
>

	<?php

		foreach ( $filter_tax_terms as $term ) {

	?>

	<li class="query-filter-item d-flex align-items-center" data-filter-type="tx" data-filter-key="<?php echo $filter['taxonomy']; ?>" data-filter-value="<?php echo $term->term_id; ?>">
		<i class="icon far fa-square fa-sm fa-fw mr-2"></i>
		<span class="label"><?php echo $term->name; ?></span>
	</li>

	<?php

		}

	?>

</ul>
