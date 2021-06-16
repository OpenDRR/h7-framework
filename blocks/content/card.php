<?php

	if ( get_sub_field ( 'image') != '' ) {

?>

<img src="<?php echo wp_get_attachment_image_url ( get_sub_field ( 'image' ), 'card-img' ); ?>" class="card-img-top">

<?php

	}

?>

<div class="card-body">
	<?php

		if (
			get_sub_field ( 'title' ) != '' ||
			get_sub_field ( 'subtitle' ) != ''
		) {

			if ( get_sub_field ( 'title' ) != '' ) {

	?>

	<h5 class="card-title"><?php the_sub_field ( 'title' ); ?></h5>

	<?php

			}

			if ( get_sub_field ( 'subtitle' ) != '' ) {

	?>

	<h6 class="card-subtitle"><?php the_sub_field ( 'subtitle' ); ?></h6>

	<?php

			}
		}

		if ( get_sub_field ( 'body' ) != '') {

	?>

	<div class="card-text">

		<?php

	  	the_sub_field ( 'body' );

		?>

	</div>

	<?php

		}

	?>

</div>

<?php

	if ( have_rows ( 'footer' ) ) {
		while ( have_rows ( 'footer' ) ) {
			the_row();

			if ( get_sub_field ( 'content' ) != 'none') {



?>

<div class="card-footer footer-type-<?php echo get_sub_field ( 'content' ); ?>">

	<?php

		if ( get_sub_field ( 'content' ) == 'text') {

			echo wptexturize ( get_sub_field ( 'text' ) );

		} elseif ( get_sub_field ( 'content' ) == 'button') {

			if ( have_rows ( 'button' ) ) {
				while ( have_rows ( 'button' ) ) {
					the_row();

					include ( locate_template ( 'elements/button.php' ) );

				}
			}

		}

	?>

</div>

<?php

			}

		}
	}

?>
