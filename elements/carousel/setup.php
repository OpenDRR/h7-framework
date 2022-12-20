<?php

class Carousel {

	public $carousel;
	public $settings;

	public function __construct ( $id ) {

		$this->carousel = array (
	    'id' => $id,
	    'slide_counter' => 0
	  );

		$this->classes = array (
			'container' => array(),
			'content' => array(),
			'controls' => array()
		);

		$this->settings = array (
			'slidesPerView' => 1,
			'slidesPerGroup' => 1,
			'simulateTouch' => false
		);

		wp_enqueue_script ( 'swiper' );

		// CLASSES

		// print_r($carousel);

	}

	public function init() {

		//
		// CLASSES
		//

		if ( have_rows ( 'carousel_classes' ) ) {
			while ( have_rows ( 'carousel_classes' ) ) {
				the_row();

				$this->classes['container'][] = get_sub_field ( 'container' );
				$this->classes['content'][] = get_sub_field ( 'item' );
				$this->classes['controls'][] = get_sub_field ( 'controls' );

			}
		}

		//
	  // SETTINGS
	  //

	  // BREAKPOINTS

		$breakpoints = array (
			576 => array(),		// sm
			768 => array(),		// md
			992 => array(),		// lg
			1200 => array()		// xl
		);

	  while ( have_rows ( 'slidesPerView' ) ) {
	    the_row();

	    if ( get_sub_field ( 'xs' ) != '' ) {
	      $this->settings['slidesPerView'] = (int) get_sub_field ( 'xs' );
	    }

			if ( get_sub_field ( 'sm' ) != '' )
				$breakpoints[576]['slidesPerView'] = (int) get_sub_field ( 'sm' );

	    if ( get_sub_field ( 'md' ) != '' )
	      $breakpoints[768]['slidesPerView'] = (int) get_sub_field ( 'md' );

	    if ( get_sub_field ( 'lg' ) != '' )
	      $breakpoints[992]['slidesPerView'] = (int) get_sub_field ( 'lg' );

	    if ( get_sub_field ( 'xl' ) != '' )
	      $breakpoints[1200]['slidesPerView'] = (int) get_sub_field ( 'xl' );

	  }

	  while ( have_rows ( 'slidesPerGroup' ) ) {
	    the_row();

	    if ( get_sub_field ( 'xs' ) != '' )
	      $this->settings['slidesPerGroup'] = (int) get_sub_field ( 'xs' );

	    if ( get_sub_field ( 'sm' ) != '' )
	      $breakpoints[576]['slidesPerGroup'] = (int) get_sub_field ( 'sm' );

	    if ( get_sub_field ( 'md' ) != '' )
	      $breakpoints[768]['slidesPerGroup'] = (int) get_sub_field ( 'md' );

	    if ( get_sub_field ( 'lg' ) != '' )
	      $breakpoints[992]['slidesPerGroup'] = (int) get_sub_field ( 'lg' );

	    if ( get_sub_field ( 'xl' ) != '' )
	      $breakpoints[1200]['slidesPerGroup'] = (int) get_sub_field ( 'xl' );

	  }

		// remove empty breakpoints

		foreach ( $breakpoints as $key => $breakpoint ) {
			if ( empty ( $breakpoint ) ) {
				unset ( $breakpoints[$key] );
			}
		}

		// add to settings

		$this->settings['breakpoints'] = $breakpoints;

	  // query pagination

	  // if ( get_sub_field ( 'results_paged' ) == 1 ) {
	  //   $this->carousel['results_paged'] = true;
	  // }

	  // transition effect
		if ( get_sub_field ( 'effect' ) != '' ) {
	  	$this->settings['effect'] = get_sub_field ( 'effect' );
		}

	  // loop

	  if ( get_sub_field ( 'loop' ) == 1 ) {
	    $this->settings['loop'] = true;
	  }

	  // adaptive height

	  if ( get_sub_field ( 'autoHeight' ) == 1 ) {
	    $this->settings['autoHeight'] = true;
	  }

	  // arrows

	  if ( get_sub_field ( 'arrows' ) == 1 ) {

	  $this->classes['controls'][] = 'has-navigation';

	    $this->settings['navigation'] = array (
	      'prevEl' => '#' . $this->carousel['id'] . '-prev',
	      'nextEl' => '#' . $this->carousel['id'] . '-next'
	    );

	  }

		// centered

		if ( get_sub_field ( 'centeredSlides' ) == 1 ) {
			$this->settings['centeredSlides'] = true;
		}

	  // pagination

	  if ( get_sub_field ( 'pagination' ) != 'none' ) {

	    $this->classes['controls'][] = 'has-pagination';
	    $this->classes['controls'][] = 'pagination-type-' . get_sub_field ( 'pagination' );

	    $this->settings['pagination'] = array (
	      'el' => '#' . $this->carousel['id'] . '-pagination',
	      'type' => get_sub_field ( 'pagination' ),
	      'clickable' => true
	    );

	    if ( get_sub_field ( 'pagination' ) == 'dynamic' ) {
	      $this->settings['pagination']['type'] = 'bullets';
	      $this->settings['pagination']['dynamicBullets'] = true;
	    }
	  }

	  if ( (int) get_sub_field ( 'autoplaySpeed' ) != 0 && (int) get_sub_field ( 'autoplaySpeed' ) != '' ) {
	    $this->settings['autoplay'] = array ( 'delay' => (int) get_sub_field ( 'autoplaySpeed' ) * 1000 );
	  }

	  // other

	  if ( have_rows ( 'other' ) ) {
	    while ( have_rows ( 'other' ) ) {
	      the_row();

	      $setting_name = get_sub_field ( 'setting' );
	      $setting_val = get_sub_field ( 'value' );
				$setting_type = get_sub_field ( 'type' );

				switch ( $setting_type ) {
					case 'boolean' :

						if ( $setting_val == 'true' ) {
			        $setting_val = true;
			      } else {
			        $setting_val = false;
			      }

						break;

					case 'int' :

						$setting_val = (int) $setting_val;
						break;

				}

	      $this->settings[$setting_name] = $setting_val;

	    }
	  }

		// dumpit($this);

		add_action ( 'after_element_wrapper_open', array ( $this, 'open' ), 20, 2 );
		add_action ( 'before_element_wrapper_close', array ( $this, 'close' ), 20, 2 );

	}

	public function open ( $element ) {

		$type = $element['type'];

		if ( isset ( $GLOBALS['elements']['types'][$type]['carousel'] ) ) {

			// set global carousel flag

			$GLOBALS['vars']['current_carousel'] = $type;

			echo '<div id="' . get_current_element_ID() . '-swiper-container" class="swiper carousel ';

			echo implode ( ' ', $this->classes['container'] );

			// if ( $render == true ) {
				echo ' render-me';
			// }

			echo '"';

			echo ' data-swiper-settings=\'' . json_encode ( $this->settings ) . '\'';

			echo '>';

			echo '<div class="swiper-wrapper">';

			remove_action ( 'after_element_wrapper_open', array ( $this, 'open' ), 20, 2 );

		}

	}

	public function close ( $type ) {

		if ( isset ( $GLOBALS['elements']['types'][$type]['carousel'] ) ) {

			// unset global carousel flag

      $GLOBALS['vars']['current_carousel'] = '';

      // close the .carousel container
      echo '</div><!-- .swiper-wrapper -->';

      // if progress bar pagination

      if (
        isset ( $this->settings['pagination'] ) &&
        $this->settings['pagination']['type'] == 'progressbar'
      ) {

    ?>

    <div id="<?php echo $this->carousel['id']; ?>-pagination" class="swiper-pagination "></div>

    <?php

      }

      echo '</div><!-- .swiper -->';

      // output dots/arrows

      if (
        isset ( $this->settings['navigation'] ) ||
        isset ( $this->settings['pagination'] )
      ) {

				include ( locate_template ( 'elements/carousel/controls.php' ) );

			}

      unset ( $GLOBALS['elements']['types'][$type]['carousel'] );

		}

	}
}
