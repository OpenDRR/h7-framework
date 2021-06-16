<?php

  wp_enqueue_script ( 'lottie' );
  wp_enqueue_script ( 'animation' );

?>

<!-- <div class="animation-container"> -->
  <div
    id="<?php echo get_current_element_ID(); ?>-animation"
    class="animation"
    data-anim-loop="<?php echo ( get_sub_field ( 'loop' ) == 1 ) ? 'true' : 'false'; ?>"
    data-anim-in-view="<?php echo ( get_sub_field ( 'in_view' ) == 1 ) ? 'true' : 'false'; ?>"
    data-anim-rewind="<?php echo ( get_sub_field ( 'rewind' ) == 1 ) ? 'true' : 'false'; ?>"
    data-anim-path="<?php echo wp_get_attachment_url ( get_sub_field ( 'file' ) ); ?>"
  >
  </div>
<!-- </div> -->
