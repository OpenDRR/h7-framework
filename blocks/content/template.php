<?php

if ( $block['filename'] != '' ) {

  if ( locate_template ( 'template/' . $block['filename'] . '.php' ) != '' ) {

    include ( locate_template ( 'template/' . $block['filename'] . '.php' ) );

  }

}
