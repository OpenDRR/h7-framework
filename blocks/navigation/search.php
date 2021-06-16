<?php

	$placeholder = get_sub_field ( 'text_placeholder' );

	if ( $placeholder == '' ) {
		$placeholder = 'Search';
	}

  $btn_icon = '<i class="';

  if ( get_sub_field ( 'btn_icon' ) != '' ) {
    $btn_icon .= get_sub_field ( 'btn_icon' );
  } else {
    $btn_icon .= 'fas fa-search';
  }

  $btn_icon .= '"></i>';

?>

<form role="search" method="get" id="header-search" class="searchform" action="<?php echo $GLOBALS['vars']['site_url']; ?>">

  <label class="sr-only" for="s">Search for:</label>

  <div class="input-group">
    <input type="text" value="" name="s" id="s" class="form-control <?php the_sub_field ( 'text_class' ); ?>" placeholder="<?php echo $placeholder; ?>" aria-label="<?php echo $placeholder; ?>">

    <div class="input-group-append">
      <button class="btn <?php the_sub_field ( 'btn_class' ); ?>" type="submit" id="searchsubmit"><?php echo $btn_icon; ?></button>
    </div>
  </div>

</form>
