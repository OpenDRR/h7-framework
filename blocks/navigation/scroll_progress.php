<?php

  // wp_enqueue_script ( 'sticky-kit' );
  wp_enqueue_script ( 'scroll-progress' );
  wp_enqueue_script ( 'renderer' );

  $section_selector = get_sub_field ( 'section_selector' );
  $head_selector = get_sub_field ( 'head_selector' );

?>

<ul id="<?php echo get_current_element_ID(); ?>-progress"
  class="renderable scroll-progress"
  data-renderable-type="scroll-progress"
  data-scroll-progress-style="<?php echo get_sub_field ( 'style' ); ?>"
  data-progress-section="<?php echo $section_selector; ?>"
  data-progress-heading="<?php echo $head_selector; ?>"
>

</ul>
