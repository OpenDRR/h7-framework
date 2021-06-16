<div class="carousel-controls <?php echo implode ( ' ', $carousel['classes']['controls'] ); ?>">
	<?php

		if ( isset ( $carousel['settings']['navigation'] ) ) {

	?>

	<div id="<?php echo $carousel['id']; ?>-prev" class="carousel-arrow arrow-prev order-1"><i class="fas fa-arrow-left"></i></div>
	<div id="<?php echo $carousel['id']; ?>-next" class="carousel-arrow arrow-next order-3"><i class="fas fa-arrow-right"></i></div>

	<?php

		}

		if (
			isset ( $carousel['settings']['pagination'] ) &&
			(
				$carousel['settings']['pagination']['type'] == 'bullets' ||
				$carousel['settings']['pagination']['type'] == 'dynamic' ||
				$carousel['settings']['pagination']['type'] == 'fraction'
			)
		) {

	?>

	<div class="order-2 flex-grow-1">
		<div id="<?php echo $carousel['id']; ?>-pagination" class="swiper-pagination carousel-dots"></div>
	</div>

	<?php

		}

	?>

</div>
