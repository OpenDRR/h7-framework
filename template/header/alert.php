<?php

  if ( have_rows ( 'header_alert', 'option' ) ) {
    while ( have_rows ( 'header_alert', 'option' ) ) {
      the_row();

      $alert_start = strtotime ( get_sub_field ( 'start' ) );
      $alert_end = strtotime ( get_sub_field ( 'end' ) );

      if (
        $alert_start <= $GLOBALS['vars']['timestamp'] &&
        $alert_end >= $GLOBALS['vars']['timestamp']
      ) {

?>

<div id="header-alert" class="fw-section fw-header-section header-alert row align-items-center py-2">
  <div class="alert-text col-8 offset-1">
    <?php

      echo get_sub_field ( 'text' );

      while ( have_rows ( 'link' ) ) {
        the_row();

        if ( get_sub_field ( 'type' ) != '' ) {

    ?>
  </div>

  <?php

          if ( get_sub_field ( 'type' ) == 'post' ) {
            $alert_link_URL = get_permalink ( get_sub_field ( 'post' ) );
          } else {
            $alert_link_URL = get_sub_field ( 'url' );
          }

  ?>

  <div class="col-1">
    <a href="<?php echo $alert_link_URL; ?>">Learn more</a>
  </div>

  <?php

        }
      }

  ?>

  <div class="alert-close col-1 text-right">
    <i class="alert-close-btn fas fa-times"></i>
  </div>

</div>

<?php

      }

    }
  }

?>
