<?php

  // init BG class array if not already set

  if ( !isset ( $bg_class ) ) {
    $bg_class = array();
  }

  // opacity

  $bg_class[] = 'opacity-' . get_sub_field ( 'opacity' );

  if ( get_sub_field ( 'image' ) != '' ) {

    // image

    $bg_URL = wp_get_attachment_image_url ( get_sub_field ( 'image' ), 'bg' );

  }

  //
  // VIDEO OR IMAGE?
  //

  if ( get_sub_field ( 'video' ) != '' ) {

    $bg_class[] = 'video';

    $video_URL = wp_get_attachment_url ( get_sub_field ( 'video' ) );

?>

  <div class="bg <?php echo implode ( ' ', $bg_class ); ?>">
    <video loop autoplay muted>
      <source src="<?php echo $video_URL; ?>" type="video/mp4">
      Your browser does not support the video tag.
    </video>
  </div>

<?php

  } else {

    //
    // BG image settings
    // if a BG URL exists, either by a subfield or set manually in a template
    //

    if ( isset ( $bg_URL ) && $bg_URL != '' ) {

      // position

      if (
        !is_array( get_sub_field ( 'position' ) ) &&
        get_sub_field ( 'position' ) != ''
      ) {
        $bg_class[] = 'bg-position-' . str_replace( ' ', '-', get_sub_field ( 'position' ) );
      }

      // size

      if (
        !is_array( get_sub_field ( 'size' ) ) &&
        get_sub_field ( 'size' ) != ''
      ) {
        $bg_class[] = 'bg-size-' . get_sub_field ( 'size' );
      }

      // attachment

      if (
        !is_array( get_sub_field ( 'attachment' ) ) &&
        get_sub_field ( 'attachment' ) != ''
      ) {
        $bg_class[] = 'bg-attachment-' . get_sub_field ( 'attachment' );
      }

?>

  <div class="bg <?php echo implode ( ' ', $bg_class ); ?>" style="background-image: url(<?php echo $bg_URL; ?>);"></div>

<?php

    }

  }

  $bg_URL = '';
  $bg_class = array();

?>
