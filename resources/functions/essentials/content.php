<?php

/*

	1. custom_excerpt
	2. fw_get_event_date
	3. fw_event_date

*/

//
// 1.
// OUTPUT CUSTOM EXCERPT LENGTH
//

function custom_excerpt ( $limit = null, $thepost = null, $link = null ) {

  global $post;

	// defaults

	if ($thepost === null) $thepost = get_the_ID();
	if ($limit === null) $limit = 20;
	if ($link === null) $link = false;

	$post_object = get_post ( $thepost );

	$excerpt_more = ' … ';

  if ($link === null || $link === false) {
    $excerpt_more .= '';
  } else {
    $excerpt_more .= '<a href="'. esc_url ( get_permalink ( $thepost ) ) . '">Read more</a>';
  }

  $excerpt_text = apply_filters( 'the_content', $post_object->post_excerpt );

  if ( $excerpt_text == '' ) {
    $excerpt_text = apply_filters( 'the_content', $post_object->post_content );
  }

  $excerpt = wp_trim_words ( $excerpt_text, $limit, $excerpt_more );

  return $excerpt;
}

//
// 2.
// FORMAT DATE FIELD VALUE
//

function fw_get_event_date ( $all_dates ) {

	$event_date = array();

	// for each date

	$found_date = false;

	foreach ( $all_dates as $date ) {

		if ( $found_date == false ) {

			if ( $date['event_start'] <= $GLOBALS['vars']['date'] ) {

				// the start date in the past

				if ( $date['event_end'] <= $GLOBALS['vars']['date'] ) {

					// the end date is in the past

				} else {

					// event is happening right now

					$found_date = true;

				}
			} else {

				// the start date is in the future

				$found_date = true;

			}

			if ( $found_date == true ) {
				$event_date = $date;
			}

		} else {

			// break;

		}

	}

	// if no future date was found, get the first date entry from the field

	$event_date = $all_dates[0];

	return $event_date;

}

function fw_event_date ( $date ) {

	$output = '';

	$has_date_range = false;
	$has_time_range = false;

	$event_date = fw_get_event_date ( $date );

	if ( !empty ( $event_date ) ) {

		$start_date = strtotime ( $event_date['event_start'] );
		$start_month = date ( 'F', $start_date );
		$start_year = date ( 'Y', $start_date );

		if (
			$event_date['event_end'] != '' &&
			$event_date['event_end'] != $event_date['event_start']
		) {

			$has_date_range = true;

			$end_date = strtotime ( $event_date['event_end'] );
			$end_month = date ( 'F', $end_date );
			$end_day = date ( 'j', $end_date );
			$end_year = date ( 'Y', $end_date );

		}

		if (
			$event_date['event_start_time'] != '' &&
			$event_date['event_end_time'] != ''
		) {
			$has_time_range = true;
		}

		if ( $has_date_range == false ) {

			// NO RANGE

			// without time
			// January 1, 2020

			$output .= date ( 'F j, Y', $start_date );

			// with time
			// January 1, 2020, 9:00 am

			if ( $event_date['event_start_time'] != '' ) {
				$output .= ', ' . $event_date['event_start_time'];
			}

			// with time range
			// January 1, 2020, 9:00 am – 5:00 pm

			if ( $event_date['event_end_time'] != '' ) {
				$output .= ' – ' . $event_date['event_end_time'];
			}

		} elseif ( $has_date_range == true ) {

			// RANGE

			// January 1, 2020, 9:00 am – January 2, 2020, 5:00 pm
			// January 1, 2020, 9:00 am – January 2, 2020
			// January 1 – 2, 2020

			if ( $start_year != $end_year ) {

				// range crossing years

				// December 31, 2019, 9:00 am – January 1, 2020, 5:00 pm
				// December 31, 2019, 9:00 am – January 1, 2020
				// December 31, 2019 – January 1, 2020

				$output .= date ( 'F j, Y', $start_date );

				// if there's a start time

				if ( $event_date['event_start_time'] != '' ) {
					$output .= ', ' . $event_date['event_start_time'];
				}

				$output .= ' – ';

				$output .= date ( 'F j, Y', $end_date );

				// if there's an end time

				if ( $event_date['event_end_time'] != '' ) {
					$output .= ', ' . $event_date['event_end_time'];
				}

			} elseif ( $start_month != $end_month ) {

				// range crossing months

				// January 1, 2020, 9:00 am – February 1, 2020, 5:00 pm
				// January 1, 2020, 9:00 am – February 1, 2020
				// January 1 – February 1, 2020

				$output .= date ( 'F j', $start_date );

				// if there's a start time

				if ( $event_date['event_start_time'] != '' ) {
					$output .= ', ' . $start_year . ', ' . $event_date['event_start_time'];
				}

				$output .= ' – ';

				$output .= date ( 'F j, Y', $end_date );

				// if there's an end time

				if ( $event_date['event_end_time'] != '' ) {
					$output .= ', ' . $event_date['event_end_time'];
				}

			} else {

				//

				$output .= date ( 'F j', $start_date );

				// if there's a start time

				if ( $event_date['event_start_time'] != '' ) {

					$output .= ', ';
					$output .= $start_year . ', ' . $event_date['event_start_time'];
					$output .= ' – ';
					$output .= date ( 'F j, Y', $end_date );

				} else {

					$output .= ' – ';

					$output .= date ( 'j, Y', $end_date );

				}

				// if there's an end time

				if ( $event_date['event_end_time'] != '' ) {
					$output .= ', ' . $event_date['event_end_time'];
				}

			}

		}

		echo $output;

	}
}
