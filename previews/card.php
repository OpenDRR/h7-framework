<div class="post-preview card type-<?php echo $item['post_type']; ?> h-100">

	<?php

		if ( !empty ( $item['thumbnail'] ) ) {

			$thumb_URL = wp_get_attachment_image_url ( $item['thumbnail'], 'card-img' );

	?>
	<img src="<?php echo $thumb_URL; ?>" class="card-img-top" alt="">
	<?php

		}

	?>

	<div class="card-body">
		<h4 class="card-title"><a href="<?php echo $item['permalink']; ?>"><?php echo $item['title']; ?></a></h4>

		<p class="card-text"><?php echo custom_excerpt ( 10, $item['id'] ); ?></p>

		<a href="<?php echo $item['permalink']; ?>" class="card-link"><?php _e ( 'Learn More', 'fw' ); ?></a>
	</div>
</div>
