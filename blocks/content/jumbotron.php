
  <?php


  ?>

  <div class="jumbotron-content container-fluid">
    <div class="row">
      <div class="col-10 offset-1">
        <h2><?php echo wptexturize ( get_sub_field ( 'head' ) ); ?></h2>
      </div>
    </div>

    <?php

      if ( get_sub_field ( 'lead' ) != '' ) {

    ?>

    <div class="row">
      <div class="col-7 offset-1">
        <p class="lead"><?php echo wptexturize ( get_sub_field ( 'lead' ) ); ?></p>

        <?php

          if ( have_rows ( 'jumbotron_buttons' ) ) {
            while ( have_rows ( 'jumbotron_buttons' ) ) {
              the_row();

              include ( locate_template( 'blocks/navigation/buttons.php' ) );

            }
          }

        ?>
      </div>
    </div>

    <?php

      }

    ?>

  </div><!-- row -->
