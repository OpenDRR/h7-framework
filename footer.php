
    <?php

      wp_footer();

    ?>

    <div id="spinner">
      <div class="spinner-border" role="status">
        <span class="sr-only">Loading...</span>
      </div>
    </div>

    <?php

      if ( $GLOBALS['css'] != '' ) {

    ?>

    <style type="text/css">

      <?php echo $GLOBALS['css']; ?>

    </style>

    <?php

      }
			
			do_action ( 'fw_body_close' );

    ?>

  </body>
</html>
