<?php

	if ( $query_block[$current_block_ID]['display']['type'] != 'carousel') {

		if ( $query_block[$current_block_ID]['args']['paged'] < $query_block[$current_block_ID]['results']->max_num_pages ) {

			$next_btn_URL = $GLOBALS['vars']['current_url'];

			if ( strpos ( $next_btn_URL, '?' ) !== false ) {
				$next_btn_URL = explode ( '?', $next_btn_URL )[0];
			}

			if ( isset ( $query_block[$current_block_ID]['args']['paged'] ) ) {
				$next_btn_URL .= '?' . $current_block_ID . '-page=';
				$next_btn_URL .= ( ( (int) $query_block[$current_block_ID]['args']['paged'] ) + 1 );
			}

	?>

	<div class="post-grid-next acf-query-next <?php echo implode ( ' ', $element_classes ); ?>">
		<a href="<?php echo $next_btn_URL; ?>" class="post-grid-next-btn acf-query-next-btn prevent-default btn btn-primary"><?php _e ( 'Load more', 'fw' ); ?></a>
	</div>

	<?php

		}

	}
