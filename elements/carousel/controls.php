<div class="carousel-controls <?php echo implode ( ' ', $this->classes['controls'] ); ?>">
	<?php

		if ( isset ( $this->settings['navigation'] ) ) {

	?>

	<div id="<?php echo $this->carousel['id']; ?>-prev" class="carousel-arrow arrow-prev"><i class="fas fa-arrow-left"></i></div>

	<?php

		}

		if (
			isset ( $this->settings['pagination'] ) &&
			(
				$this->settings['pagination']['type'] == 'bullets' ||
				$this->settings['pagination']['type'] == 'dynamic' ||
				$this->settings['pagination']['type'] == 'fraction'
			)
		) {

	?>

	<div class="carousel-pagination flex-grow-1">
		<div id="<?php echo $this->carousel['id']; ?>-pagination" class="swiper-pagination carousel-dots"></div>
	</div>

	<?php

		}

		if ( isset ( $this->settings['navigation'] ) ) {

	?>

	<div id="<?php echo $this->carousel['id']; ?>-next" class="carousel-arrow arrow-next"><i class="fas fa-arrow-right"></i></div>

	<?php

		}

	?>

</div>
