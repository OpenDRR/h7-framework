<?php

	if ( $query_block[$current_block_ID]['display']['pagination'] == 'replace' ) {

		// if replacing items use format 'page X of Y'

		$post_count_output = sprintf (
			__ ( 'Page %s of %s', 'fw' ),
			'<span class="post-count-num">' . $query_block[$current_block_ID]['args']['paged'] . '</span>',
			'<span class="post-count-total">' . $query_block[$current_block_ID]['results']->max_num_pages . '</span>'
		);


	} else {

		// if appending use format 'X posts of Z'

		if ( $query_block[$current_block_ID]['args']['paged'] == $query_block[$current_block_ID]['results']->max_num_pages ) {

			$post_count = $query_block[$current_block_ID]['results']->found_posts;

		} else {

			$post_count = $query_block[$current_block_ID]['args']['posts_per_page'] * $query_block[$current_block_ID]['args']['paged'];

		}

		$post_count_output = sprintf (
			__ ( 'Showing %s of %s', 'fw' ),
			'<span class="post-count-num">' . $post_count . '</span>',
			'<span class="post-count-total">' . $query_block[$current_block_ID]['results']->found_posts . '</span>'
		);

	}

?>

<div class="query-element type-post-count <?php echo implode ( ' ', $element_classes ); ?>">
	<h6 class="post-count"><?php echo $post_count_output; ?></h6>
</div>
