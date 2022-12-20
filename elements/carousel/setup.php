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

	public function init ( $swiper ) {

		//
		// CLASSES
		//
		
		// dumpit ( $swiper );
		
		if ( !empty ( $swiper['carousel_classes'] ) ) {

			$this->classes['container'] = explode ( ' ' , $swiper['carousel_classes']['container'] );
			$this->classes['content'] = explode ( ' ', $swiper['carousel_classes']['item'] );
			$this->classes['controls'] = explode ( ' ', $swiper['carousel_classes']['controls'] );

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
		
		if ( !empty ( $swiper['slidesPerView'] ) ) {
	    the_row();

	    if ( $swiper['slidesPerView']['xs'] != '' )
				$this->settings['slidesPerView'] = (int) $swiper['slidesPerView']['xs'];

			if ( $swiper['slidesPerView']['sm'] != '' )
				$breakpoints[576]['slidesPerView'] = (int) $swiper['slidesPerView']['sm'];

	    if ( $swiper['slidesPerView']['md'] != '' )
	      $breakpoints[768]['slidesPerView'] = (int) $swiper['slidesPerView']['md'];

	    if ( $swiper['slidesPerView']['lg'] != '' )
	      $breakpoints[992]['slidesPerView'] = (int) $swiper['slidesPerView']['lg'];

	    if ( $swiper['slidesPerView']['xl'] != '' )
	      $breakpoints[1200]['slidesPerView'] = (int) $swiper['slidesPerView']['xl'];

	  }
		
		if ( !empty ( $swiper['slidesPerGroup'] ) ) {
			
			if ( $swiper['slidesPerGroup']['xs'] != '' ) {
				$this->settings['slidesPerGroup'] = (int) $swiper['slidesPerGroup']['xs'];
			}
			
			if ( $swiper['slidesPerGroup']['sm'] != '' )
				$breakpoints[576]['slidesPerGroup'] = (int) $swiper['slidesPerGroup']['sm'];
			
			if ( $swiper['slidesPerGroup']['md'] != '' )
				$breakpoints[768]['slidesPerGroup'] = (int) $swiper['slidesPerGroup']['md'];
			
			if ( $swiper['slidesPerGroup']['lg'] != '' )
				$breakpoints[992]['slidesPerGroup'] = (int) $swiper['slidesPerGroup']['lg'];
			
			if ( $swiper['slidesPerGroup']['xl'] != '' )
				$breakpoints[1200]['slidesPerGroup'] = (int) $swiper['slidesPerGroup']['xl'];

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
		
		if ( $swiper['effect'] != '' ) {
	  	$this->settings['effect'] = $swiper['effect'];
		}

	  // loop

	  if ( $swiper['loop'] == 1 ) {
	    $this->settings['loop'] = true;
	  }

	  // adaptive height

	  if ( $swiper['autoHeight'] == 1 ) {
	    $this->settings['autoHeight'] = true;
	  }

	  // arrows

	  if ( $swiper['arrows'] == 1 ) {

	  	$this->classes['controls'][] = 'has-navigation';

	    $this->settings['navigation'] = array (
	      'prevEl' => '#' . $this->carousel['id'] . '-prev',
	      'nextEl' => '#' . $this->carousel['id'] . '-next'
	    );
			
	  }

		// centered

		if ( $swiper['centeredSlides'] == 1 ) {
			$this->settings['centeredSlides'] = true;
		}

	  // pagination

	  if ( $swiper['pagination'] != 'none' ) {

	    $this->classes['controls'][] = 'has-pagination';
	    $this->classes['controls'][] = 'pagination-type-' . $swiper['pagination'];

	    $this->settings['pagination'] = array (
	      'el' => '#' . $this->carousel['id'] . '-pagination',
	      'type' => $swiper['pagination'],
	      'clickable' => true
	    );

	    if ( $swiper['pagination'] == 'dynamic' ) {
	      $this->settings['pagination']['type'] = 'bullets';
	      $this->settings['pagination']['dynamicBullets'] = true;
	    }
	  }

	  if (
			$swiper['autoplaySpeed'] != '' &&
			(int) $swiper['autoplaySpeed'] != 0
		) {
			
	    $this->settings['autoplay'] = array (
				'delay' => (int) $swiper['autoplaySpeed'] * 1000
			);
			
	  }

	  // other
		
		if ( !empty ( $swiper['other'] ) ) {
			
			foreach ( $swiper['other'] as $other ) {

      	$setting_name = $other['setting'];
      	$setting_val = $other['value'];
				$setting_type = $other['type'];
	
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
			
			// use the current active carousel element
			
			// $this_carousel = $GLOBALS['elements']['types'][$type]['carousel'];

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
			
			// unset global carousel flag
			
			$GLOBALS['vars']['current_carousel'] = '';
			
			// unset carousel

      unset ( $GLOBALS['elements']['types'][$type]['carousel'] );
			
			// stop trying to close this carousel in the future
			
			remove_action ( 'before_element_wrapper_close', array ( $this, 'close' ), 20, 2 );

		}

	}
}
