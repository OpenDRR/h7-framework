<?php

  $next_link = fw_get_next_page_link();

  if ( !empty ( $next_link ) ) {

?>

<div class="row">

  <div class="col-7 offset-1 ">
    <h6>Next</h6>
    <h3><a href="<?php echo get_permalink ( $next_link['id'] ); ?>"><?php echo $next_link['title']; ?></a></h3>
  </div>

  <div class="col-2">
    <a href="<?php echo get_permalink ( $next_link['id'] ); ?>" class="btn btn-primary"><?php echo $next_link['btn']; ?></a>
  </div>

</div>

<?php

  }
