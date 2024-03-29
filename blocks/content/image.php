<?php

  $img_ID = $block['image_file'];

  if ( $img_ID != '' ) {

    $wrap_class = array ( 'd-flex', 'flex-column' );
    $link_class = array ( 'd-block' );
    $link_href = '';
    $link_atts = array();
    $img_class = array ();
    $img_atts = array();

    // POST DATA

    $img_post = get_post ( $img_ID );

    $img_URL = wp_get_attachment_image_url ( $img_ID, 'large' );

    $img_title = get_the_title ( $img_ID );
    $img_caption = get_the_excerpt ( $img_ID );
    $img_content = $img_post->post_content;
		
		$img_alt = get_post_meta ( $img_ID, '_wp_attachment_image_alt', true );
		
		if ( $img_alt == '' ) $img_alt = $img_caption;

    // OPTIONS

    // element ordering

    $img_order = 1;

    $title_show = true;
    $title_order = 2;

    $caption_show = false;
    $description_show = false;
		
		if ( !empty ( $block['options'] ) ) {

      // title

      $title_show = true;

      switch ( $block['options']['title'] ) {

        case 'none' :
          $title_show = false;
          break;

        case 'above' :
          $title_order = 1;
          $img_order = 2;
          $wrap_class[] = 'has-title';
          break;

        case 'below' :
          $title_order = 2;
          $img_order = 1;
          $wrap_class[] = 'has-title';
          break;

      }

      $img_class[] = 'order-' . $img_order;

      // caption & description

      if ( $block['options']['caption'] == 1 ) {
        $caption_show = true;
        $wrap_class[] = 'has-caption';
      }

      if ( $block['options']['description'] == 1 ) {
        $description_show = true;
        $wrap_class[] = 'has-description';
      }

      // behaviour

      if ( $block['options']['link'] == 'none' ) {

        $has_link = true;
        $wrap_class[] = 'has-link';

      }

      switch ( $block['options']['link'] ) {

        case 'zoom' :

          // set link to the full image
          $link_href = $img_URL;

          // change output image to a smaller size
          $img_URL = wp_get_attachment_image_url ( $img_ID, 'large' );

          $link_atts['data-overlay-content'] = 'image';
          $link_atts['data-overlay-title'] = $img_title;
          $link_atts['data-overlay-caption'] = $img_content;
          array_push ( $link_class, 'zoom', 'overlay-toggle' );
          break;

        case 'post' :
          $link_href = get_permalink ( $block['options']['post'] );
          break;

        case 'url' :
          $link_href = $block['options']['url'];
          $link_atts['target'] = '_blank';
          break;


      }

      // magnify

      if ( $block['options']['magnify'] == 1 ) {

        wp_enqueue_style ( 'magnify' );
				wp_enqueue_script ( 'magnify' );
				
        $img_class[] = 'magnify';
        $img_atts['data-magnify-src'] = wp_get_attachment_image_url ( $img_ID, 'full' );

      }

    }

?>

<div class="image-wrap <?php echo implode ( ' ', $wrap_class ); ?>">

  <?php

    // TITLE

    if ( $img_title != '' && $title_show == true ) {

  ?>

  <div class="image-title order-<?php echo $title_order; ?>">
    <h4 class=""><?php echo $img_title; ?></h4>
  </div>

  <?php

      }

      // IMAGE

  ?>

  <div class="image <?php echo implode ( ' ', $img_class ); ?>">
    <?php

      if ( $link_href != '' ) {

    ?>

    <a href="<?php echo $link_href; ?>" class="image-link <?php echo implode ( ' ', $link_class ); ?>" <?php

      foreach ( $link_atts as $att => $val ) {
        echo ' ' . $att . '="' . $val . '"';
      }

    ?>>

    <?php

      }

    ?>

    <img src="<?php echo $img_URL; ?>" <?php

      foreach ( $img_atts as $att => $val ) {
        echo ' ' . $att . '="' . $val . '"';
      }

    ?> alt="<?php echo $img_alt; ?>">

    <?php

      if ( $link_href != '' ) {

    ?>

    </a>

    <?php

      }

    ?>
  </div>

  <?php

      if ( ( $caption_show == true || $description_show == true ) && ( $img_caption != '' || $img_content != '' ) ) {

  ?>

  <div class="image-caption order-3">
    <?php

      // CAPTION

      if ( $caption_show == true && $img_caption != '' ) {

    ?>

    <h5><?php echo $img_caption; ?></h5>

    <?php

      }

      // DESCRIPTION

      if ( $description_show == true && $img_content != '' ) {

        echo apply_filters ( 'the_content', $img_content );

      }

    ?>
  </div>

  <?php

      }

  ?>
</div>

<?php

  }

?>
