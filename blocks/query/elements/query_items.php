<?php

	// item

	$item_classes = explode ( ' ', $element['classes']['item'] );

	switch ( $query_block[$current_block_ID]['display']['type'] ) {

		case 'carousel' :

			$item_classes[] = 'swiper-slide';

			break;

		case 'list' :

			$element_classes[] = 'list-group';
			$item_classes[] = 'list-group-item';

			break;

	}

?>

<div class="query-element <?php echo implode ( ' ', $element_classes ); ?>">

	<?php

		//
		// PRE-LOOP
		//

		switch ( $query_block[$current_block_ID]['display']['type'] ) {

			case 'carousel' :

				$GLOBALS['elements']['types']['block']['carousel']->open ( $GLOBALS['elements']['current'] );

		    // open carousel element
		    //carousel_output ( 'open', 'block', false );

				break;

			case 'list' :

				// echo '<ul id=' . $current_block_ID . '-items" class="post-list-wrap list-group">';

				break;

		}

		//
		// LOOP
		//

		if (
			isset ( $query_block[$current_block_ID]['items'] ) &&
			!empty ( $query_block[$current_block_ID]['items'] )
		) {

			foreach ( $query_block[$current_block_ID]['items'] as $item ) {

	?>

	<div id="<?php echo $current_block_ID; ?>-item-<?php echo $item['id']; ?>" class="query-item <?php echo implode ( ' ', $item_classes ); ?> <?php //echo implode ( ' ',  $query_block[$current_block_ID]['display']['classes']['item'] ); ?>">

		<?php

			if (
				isset ( $query_block[$current_block_ID]['display']['template'] ) &&
				$query_block[$current_block_ID]['display']['template'] != '' &&
				locate_template ( 'previews/' . $query_block[$current_block_ID]['display']['template'] . '.php' ) != '' ) {

				echo '<!-- ' . $query_block[$current_block_ID]['display']['template'] . '.php -->';

				// card template set by field

				include ( locate_template ( 'previews/' . $query_block[$current_block_ID]['display']['template'] . '.php' ) );

			} elseif ( locate_template ( 'previews/' . $item['post_type'] . '-card.php'  ) != '' ) {

				echo '<!-- ' . $item['post_type'] . '-card.php -->';

				// card template by post type

				include ( locate_template ( 'previews/' . $item['post_type'] . '-card.php' ) );

			} else {

				echo '<!-- card.php -->';

				// default card

				include ( locate_template ( 'previews/card.php' ) );

			}

		?>

	</div>

	<?php

			} // foreach items

			unset ( $new_query );

		} else {

	?>

	<div class="alert alert-warning query-empty">No items found.</div>

	<?php

		}

		//
		// POST-LOOP
		//

		switch ( $query_block[$current_block_ID]['display']['type'] ) {

			case 'carousel' :

				$GLOBALS['elements']['types']['block']['carousel']->close ( 'block' );

				// close carousel element
		    //carousel_output ( 'close', 'block' );

				break;

			case 'list' :

				// echo '</ul>';

				break;

		}

	?>

</div>
