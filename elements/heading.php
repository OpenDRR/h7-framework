<?php

  if ( have_rows ( 'heading' ) ) {
    while ( have_rows ( 'heading' ) ) {
      the_row();

      $head_tag = 'h' . get_sub_field ( 'level' );

      $head_class = array();

      if ( get_sub_field ( 'style' ) != '' ) {
        $head_color = get_sub_field ( 'style' );
        $head_class[] = 'text-' . get_sub_field ( 'style' );
      } elseif ( isset ( $section_accent ) && $section_accent != '' ) {
        $head_color = $section_accent;
        $head_class[] = 'text-' . $section_accent;
      }

      if ( get_sub_field ( 'text' ) ) {

        echo '<' . $head_tag . ' class="' . implode ( ' ', $head_class ) . '">' . get_sub_field ( 'text' ) . '</' . $head_tag . '>';

      }
    }
  }

?>
