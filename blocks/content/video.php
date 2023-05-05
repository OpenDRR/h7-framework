<div id="<?php echo get_current_element_ID(); ?>-video" class="embed-wrap">
  <?php

    if ( $block['source'] == 'embed' ) {

      $embed_URL = $block['url'];

      if ( $embed_URL != '' ) {

        if ( strpos ( $embed_URL, 'cloudflare' ) !== false ) {

          $embed_code = '<stream src="' . substr ( strrchr ( $embed_URL, '/' ), 1 ) . '" controls preload></stream>';

        } else {

          // $embed_code = wp_oembed_get (
          //   $block['url'],
          //   array(
          //     'width' => 600,
          //     'height' => 337
          //   )
          // );

					$embed_code = wp_oembed_get ( $block['url'] );

        }

        echo $embed_code;

      }

  } else {

    $video_atts = array();

    if ( !empty ( $block['attributes'] ) ) {

      foreach ( $block['attributes'] as $attribute ) {

        $video_atts[] = $attribute;

        if ( $attribute == 'autoplay' ) {
          $video_atts[] = 'muted';
        }

      }

    }

  ?>

  <video <?php echo implode ( ' ', $video_atts ); ?>>
    <?php
		
			if ( !empty ( $block['files'] ) ) {
				foreach ( $block['files'] as $file ) {

    ?>

    <source src="<?php echo wp_get_attachment_url ( $file['file'] ); ?>" type="video/<?php echo $file['type']; ?>">

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
