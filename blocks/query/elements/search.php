<?php

	$search_placeholder = 'Search';

	if ( $element['placeholder'] != '' ) {
		$search_placeholder = $element['placeholder'];
	}

?>

<div class="query-filter-item <?php echo get_sub_field ( 'classes' ); ?>" data-filter-type="search" data-filter-value="">

	<input type="text" id="<?php echo $current_block_ID; ?>-search-input" class="form-control filter-search-input" placeholder="<?php echo $search_placeholder; ?>">

</div>
