<?php

  wp_enqueue_script ( 'slick' );

  //
  // SETTINGS
  //

  $carousel = carousel_settings ( get_current_element_ID(), 'carousel_classes', 'carousel_settings' );

?>

<div
  id="<?php echo get_current_element_ID(); ?>-carousel"
  class="carousel <?php echo implode ( ' ', $carousel['classes']['container'] ); ?>"
  data-slick='<?php echo json_encode ( $carousel['settings'] ); ?>'
>

  <?php

    $slide_num = 1;

    if ( have_rows ( 'slides' ) ) {
      while ( have_rows ( 'slides' ) ) {
        the_row();

        $slide_ID = get_current_element_ID() . '-slide-' . $slide_num;
        $slide_class = array ( $slide_ID );

        // colours

        if ( have_rows ( 'colours' ) ) {
          while ( have_rows ( 'colours' ) ) {
            the_row();

            if ( get_sub_field ( 'text_colour' ) != '' ) {

              $GLOBALS['css'] .= "\n" . '#' . get_current_element_ID() . ' .' . $slide_ID . ' { color: var(--' . get_sub_field ( 'text_colour' ) . '); }';

            }

            if ( get_sub_field ( 'heading_colour' ) != '' ) {

              $GLOBALS['css'] .= "\n" . '#' . get_current_element_ID() . ' .' . $slide_ID . ' h1, #' . get_current_element_ID() . ' .' . $slide_ID . ' h2, #' . get_current_element_ID() . ' .' . $slide_ID . ' h3, #' . get_current_element_ID() . ' .' . $slide_ID . ' h4, #' . get_current_element_ID() . ' .' . $slide_ID . ' h5, #' . get_current_element_ID() . ' .' . $slide_ID . ' h6 { color: var(--' . get_sub_field ( 'heading_colour' ) . '); }';

            }

            if ( get_sub_field ( 'link_colour' ) != '' ) {

              $GLOBALS['css'] .= "\n" . '#' . get_current_element_ID() . ' .' . $slide_ID . ' a { color: var(--' . get_sub_field ( 'link_colour' ) . '); }';

            }

          }
        }

        // background

        if ( have_rows ( 'background' ) ) {
          while ( have_rows ( 'background' ) ) {
            the_row();

            if ( get_sub_field ( 'colour' ) != '' ) {
              $slide_class[] = 'bg-' . get_sub_field ( 'colour' );
            }

          }
        }
  ?>

  <div class="slide <?php echo implode ( ' ', $slide_class ); ?>">
    <?php

      if ( have_rows ( 'background' ) ) {
        while ( have_rows ( 'background' ) ) {
          the_row();

          include ( locate_template ( 'elements/bg.php' ) );

        }
      }

    ?>

    <div class="slide-content <?php echo implode ( ' ', $carousel['classes']['content'] ); ?>">
      <?php

        include ( locate_template ( 'elements/heading.php' ) );

        the_sub_field ( 'content' );

      ?>
    </div>
  </div>

  <?php

        $slide_num++;

      }
    }

  ?>

</div>

<?php

  if (
    $carousel['settings']['arrows'] == true ||
    $carousel['settings']['dots'] == true
  ) {

?>

<div class="carousel-controls <?php echo implode ( ' ', $carousel['classes']['controls'] ); ?>">
  <?php

    if ( $carousel['settings']['arrows'] == true ) {

  ?>

  <div id="<?php echo get_current_element_ID(); ?>-prev" class="carousel-arrow order-1"><i class="fas fa-arrow-left"></i></div>
  <div id="<?php echo get_current_element_ID(); ?>-next" class="carousel-arrow order-3"><i class="fas fa-arrow-right"></i></div>

  <?php

    }

    if ( $carousel['settings']['dots'] == true ) {

  ?>

  <div id="<?php echo get_current_element_ID(); ?>-dots" class="carousel-dots order-2 flex-grow-1"></div>

  <?php

    }

  ?>

</div>

<?php

  }

?>
