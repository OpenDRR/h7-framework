<div id="<?php echo get_current_element_ID(); ?>-video" class="embed-wrap">
  <?php

    if ( get_sub_field ( 'source' ) == 'embed' ) {

      $embed_URL = get_sub_field ( 'url' );

      if ( $embed_URL != '' ) {

        if ( strpos ( $embed_URL, 'cloudflare' ) !== false ) {

          $embed_code = '<stream src="' . substr ( strrchr ( $embed_URL, '/' ), 1 ) . '" controls preload></stream>';

        } else {

          // $embed_code = wp_oembed_get (
          //   get_sub_field ( 'url' ),
          //   array(
          //     'width' => 600,
          //     'height' => 337
          //   )
          // );

					$embed_code = wp_oembed_get ( get_sub_field ( 'url' ) );

        }

        echo $embed_code;

      }

  } else {

    $video_atts = array();

    if ( !empty ( get_sub_field ( 'attributes' ) ) ) {

      foreach ( get_sub_field ( 'attributes' ) as $attribute ) {

        $video_atts[] = $attribute;

        if ( $attribute == 'autoplay' ) {
          $video_atts[] = 'muted';
        }

      }

    }

  ?>

  <video <?php echo implode ( ' ', $video_atts ); ?>>
    <?php

      if ( have_rows ( 'files' ) ) {
        while ( have_rows ( 'files' ) ) {
          the_row();

    ?>

    <source src="<?php echo wp_get_attachment_url ( get_sub_field ( 'file' ) ); ?>" type="video/<?php echo get_sub_field ( 'type' ); ?>">

    <?php

        }
      }

    ?>
    Your browser does not support the video tag.
  </video>

  <?php

  }

  ?>

</div>
