<?php

	//
	// SETTINGS
	//

	$sort_heading_classes = explode ( ' ', $element['classes']['headings'] );
	$sort_list_classes = explode ( ' ', $element['classes']['lists'] );

	$sort_default = array ( 'date', 'desc' );

	if ( isset ( $query_block[$current_block_ID]['args']['orderby'] ) ) {
		$sort_default[0] = $query_block[$current_block_ID]['args']['orderby'];
	}

	if ( isset ( $query_block[$current_block_ID]['args']['order'] ) ) {
		$sort_default[1] = $query_block[$current_block_ID]['args']['order'];
	}

?>

<div id="<?php echo $current_block_ID; ?>-sort" class="query-element <?php echo implode ( ' ', $element_classes ); ?>">

	<h5 class="query-filter-menu-head d-flex justify-content-between align-items-end">
		<i class="fas fa-sort fa-sm mr-1"></i>
		<span class="flex-grow-1">Sort</span>
		<span class="query-filter-clear">Clear</span>
	</h5>

  <div class="query-filters">
    <div class="query-filter filter-type-sort">
			<ul
				class="query-filter-list"
				data-multi="false"
				data-icons="far fa-circle|far fa-dot-circle"
			>

				<?php

					foreach ( $element['options'] as $option ) {

						$icon_class = 'far fa-circle';

				?>

				<li
					class="query-filter-item d-flex align-items-center <?php

						if ( $option['value'] == strtolower ( implode ( '_', $sort_default ) ) ) {
							$icon_class = 'far fa-dot-circle';
							echo 'sort-default selected';
						}

					?>"
					data-filter-type="sort"
					data-filter-value="<?php echo $option['value']; ?>"
				>
					<i class="icon <?php echo $icon_class; ?> fa-sm fa-fw mr-2"></i>
					<span class="label"><?php echo $option['label']; ?></span>
				</li>

				<?php

					}

				?>

			</ul>
    </div>
  </div>

</div>
