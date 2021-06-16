<?php

  //
  // ALWAYS RESET THE SECTION COUNTER
  // since this is the start of a new loop
  // i.e. going from the header loop to the main body loop
  //

  //$GLOBALS['elements']['counters']['section'] = 1;

  //
  // LOOP
  //

  content_loop ( 'elements', get_the_ID() );

  //
  // AFTER THE LOOP
  // close any elements that are still open
  //

  // foreach ( array_reverse ( $GLOBALS['elements']['types'] ) as $type => $data ) {
  //
  //   if ( $data['is_open'] == true ) {
  //     close_element ( $type );
  //   }
  //
  // }
