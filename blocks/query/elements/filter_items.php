<?php

	//
	// SETTINGS
	//

	$filter_heading_classes = explode ( ' ', $element['classes']['headings'] );
	$filter_list_classes = explode ( ' ', $element['classes']['lists'] );

	if ( isset ( $element['accordions'] ) && $element['accordions'] == 1 ) {
		$element_classes[] = 'accordion';
		$filter_heading_classes[] = 'accordion-head';
		$filter_list_classes[] = 'accordion-content';
	}

?>

<div id="<?php echo $current_block_ID; ?>-filter" class="query-element <?php echo implode ( ' ', $element_classes ); ?>">

	<h5 class="query-filter-menu-head d-flex justify-content-between align-items-end">
		<i class="fas fa-filter fa-sm mr-1"></i>
		<span class="flex-grow-1">Filter</span>
		<span class="query-filter-clear">Clear All</span>
	</h5>

  <div class="query-filters">

    <?php

      foreach ( $query_block[$current_block_ID]['filters'] as $filter ) {

    ?>

    <div class="query-filter filter-type-<?php echo $filter['type']; ?>">
      <?php

				include ( locate_template ( 'blocks/query/filters/' . $filter['type'] . '.php' ) );

      ?>
    </div>

    <?php

      }

    ?>
  </div>

</div>
