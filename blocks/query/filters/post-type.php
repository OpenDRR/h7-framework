<h6 class="query-filter-head <?php echo implode ( ' ', $filter_heading_classes ); ?>">By Content</h6>

<ul class="query-filter-list <?php echo implode ( ' ', $filter_list_classes ); ?>" data-multi="false">

	<?php

			foreach ( $query_block[$current_block_ID]['args']['post_type'] as $post_type ) {

				$filter_type = get_post_type_object ( $post_type );
				$filter_type_labels = get_post_type_labels ( $filter_type );

	?>

	<li class="query-filter-item d-flex align-items-center" data-filter-type="pt" data-filter-value="<?php echo $post_type; ?>">
		<i class="icon far fa-square fa-sm fa-fw mr-2"></i>
		<span class="label"><?php echo $filter_type_labels->singular_name; ?></span>
	</li>

	<?php

			}

	?>

</ul>
