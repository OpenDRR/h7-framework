<?php

if ( get_sub_field ( 'filename' ) != '' ) {

  if ( locate_template ( 'template/' . get_sub_field ( 'filename' ) . '.php' ) != '' ) {

    include ( locate_template ( 'template/' . get_sub_field ( 'filename' ) . '.php' ) );

  }

}
