<?php

	wp_enqueue_script ( 'renderer' );

	global $post;

	if ( !isset ( $current_block_ID ) ) {
		$current_block_ID = get_current_element_ID();
	}

	//
	// OBJECT SETTINGS
	//

	// display (grid/carousel/list)
	if ( !isset ( $new_query['display']['type'] ) )
		$new_query['display']['type'] = get_sub_field ( 'display' );

	if ( !isset ( $new_query['display']['classes']['object'] ) )
		$new_query['display']['classes']['object'] = explode ( ' ', get_sub_field ( 'object_classes' ) );

	array_push ( $new_query['display']['classes']['object'], 'renderable', 'query-type-' . $new_query['display']['type'] );

	//
	// ELEMENT SETTINGS
	// run through the loop once to grab any settings that are inside flex subfields
	//

	if ( !isset ( $new_query['display']['containers'] ) )
	$new_query['display']['containers'] = get_sub_field ( 'containers' );

	// defaults

	$new_query['display']['pagination'] = 'append';

	if ( !empty ( $new_query['display']['containers'] ) ) {
		foreach ( $new_query['display']['containers'] as $container ) {

			foreach ( $container['elements'] as $element ) {

				switch ( $element['acf_fc_layout'] ) {

					case 'pagination' :

						$new_query['paginate'] = true;

						$new_query['display']['pagination'] = $element['type'];
						$new_query['display']['atts']['pagination'] = $element['type'];

						break;
				}

			}

		}
	}

	// DISPLAY

	switch ( $new_query['display']['type'] ) {

		case 'grid' :

			wp_enqueue_script ( 'post-grid' );

			break;

		case 'carousel' :

			wp_enqueue_script ( 'swiper' );
			wp_enqueue_script ( 'post-carousel' );

			$new_query['paginate'] = true;

			if ( have_rows ( 'swiper' ) ) {
				while ( have_rows ( 'swiper' ) ) {
					the_row();

					$GLOBALS['elements']['types']['block']['carousel'] = new Carousel ( get_current_element_ID() );
					$GLOBALS['elements']['types']['block']['carousel']->init();

					//$GLOBALS['elements']['types']['block']['carousel'] = carousel_setup ( get_current_element_ID() );

				}
			}

			break;

		case 'list' :

			wp_enqueue_script ( 'post-grid' );

			break;

	}

	// ITEM

	// item template

	if ( !isset ( $new_query['display']['template'] ) )
		$new_query['display']['template'] = get_sub_field ( 'template' );

	//
	// BEGIN SETTING UP ACF QUERY
	//

	// array to hold the grid items

	if ( !isset ( $query_block[$current_block_ID] ) ) $query_block[$current_block_ID] = array();

	// add selected filter args

	if ( isset ( $_GET[$current_block_ID . '_filters'] ) ) {
	  $new_query['filter_args'] = explode ( ',', $_GET[$current_block_ID . '_filters'] );
	}

	// set pagination (page 1 by default)

	$new_query['args']['paged'] = 1;

	if ( isset ( $_GET[$current_block_ID . '-page'] ) ) {
	  $new_query['args']['paged'] = $_GET[$current_block_ID . '-page'];
	}

	// include acf-query component

	include ( locate_template ( 'resources/bower_components/pe-acf-query/acf-query.php' ) );

	//
	// MERGE
	// populate the array with the returned $new_query items
	//

	$query_block[$current_block_ID] = array_merge ( $query_block[$current_block_ID], $new_query );

	//
	// PREPARE FOR OUTPUT
	//

	// data attributes

	$query_block[$current_block_ID]['display']['atts'] = array (
		'renderable-type' => 'query_' . $query_block[$current_block_ID]['display']['type'],
		'filters' => '',
		'page' => 1
	);

	if ( isset ( $query_block[$current_block_ID]['results']->post_count ) ) {
		$query_block[$current_block_ID]['display']['atts']['post-count'] = $query_block[$current_block_ID]['results']->post_count;
	}

	if ( isset ( $query_block[$current_block_ID]['results']->max_num_pages ) ) {
		$query_block[$current_block_ID]['display']['atts']['max-pages'] = $query_block[$current_block_ID]['results']->max_num_pages;
	} else {
		$query_block[$current_block_ID]['display']['atts']['max-pages'] = 1;
	}

	if ( isset ( $query_block[$current_block_ID]['results']->posts_per_page ) ) {
		$query_block[$current_block_ID]['display']['atts']['per-page'] = $query_block[$current_block_ID]['results']->posts_per_page;
	} else {
		$query_block[$current_block_ID]['display']['atts']['per-page'] = -1;
	}

	if (
		isset ( $query_block[$current_block_ID]['filter_args'] ) &&
		!empty ( $query_block[$current_block_ID]['filter_args'] )
	) {
		$query_block[$current_block_ID]['display']['atts']['filters'] = json_encode ( $query_block[$current_block_ID]['filter_args'] );
	}

	// echo '<pre>';
	// print_r($query_block[$current_block_ID]);
	// echo '</pre>';

	//
	// OUTPUT
	//

?>

<div
  id="<?php echo $current_block_ID; ?>-object"
  class="<?php echo implode ( ' ', $query_block[$current_block_ID]['display']['classes']['object'] ); ?>"
	<?php

		foreach ( $query_block[$current_block_ID]['display']['atts'] as $att => $val ) {

			echo ' data-' . $att . "='" . $val . "'";

		}

	?>
>

	<?php

		//
		// LOOP
		//

		if ( !empty ( $query_block[$current_block_ID]['display']['containers'] ) ) {
			foreach ( $query_block[$current_block_ID]['display']['containers'] as $container ) {

				// container classes

				if ( gettype ( $container['container_classes'] ) == 'array' ) {
					$container['container_classes'] = explode ( ' ', $container['container_classes']['element'] );
				} else {
					$container['container_classes'] = explode ( ' ', $container['container_classes'] );
				}

	?>

	<div class="query-container <?php echo implode ( ' ', $container['container_classes'] ); ?>">

		<?php

			if ( !empty ( $container['elements'] ) ) {
				foreach ( $container['elements'] as $element ) {

					// element classes

					if ( is_array ( $element['classes'] ) ) {
						$element_classes = explode ( ' ', $element['classes']['element'] );
					} else {
						$element_classes = explode ( ' ', $element['classes'] );
					}

					// display type

					$element_classes[] = 'type-' . $element['acf_fc_layout'];

					// output

					if ( locate_template ( 'blocks/query/elements/' . $element['acf_fc_layout'] . '.php' ) != '' ) {
						include ( locate_template ( 'blocks/query/elements/' . $element['acf_fc_layout'] . '.php' ) );
					} else {
						echo 'template ' . $element['acf_fc_layout'] . '.php not found';
					}

				} // each elements
			} // if elements

		?>

	</div>

	<?php

			} // each containers

		} // if containers

	?>

</div>

<?php

if ( get_sub_field ( 'debug' ) == 1 ) {
	echo '<pre class="p-3 bg-light font-size-smaller">';
	print_r ( $query_block[$current_block_ID] );
	echo '</pre>';
}
