<?php

  if ( have_rows ( 'interactives' ) ) {
    while ( have_rows ( 'interactives' ) ) {
      the_row();

      if ( locate_template ( 'blocks/interactive/' . get_row_layout() . '.php' ) != '' ) {

        include ( locate_template ( 'blocks/interactive/' . get_row_layout() . '.php' ) );

      } else {

        echo 'interactive';

      }

    }
  }
