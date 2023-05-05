<?php

  wp_enqueue_script ( 'lottie' );
  wp_enqueue_script ( 'animation' );

?>

<div
  id="<?php echo get_current_element_ID(); ?>-animation"
  class="animation"
  data-anim-loop="<?php echo ( $block['loop'] == 1 ) ? 'true' : 'false'; ?>"
  data-anim-in-view="<?php echo ( $block['in_view'] == 1 ) ? 'true' : 'false'; ?>"
  data-anim-rewind="<?php echo ( $block['rewind'] == 1 ) ? 'true' : 'false'; ?>"
  data-anim-path="<?php echo wp_get_attachment_url ( $block['file'] ); ?>"
>
</div>