<article <?php post_class ( 'fw-block type-search-result' ); ?>>
	<div class="row">
		<?php

			$ancestors = get_ancestors ( get_the_ID(), get_post_type() );

			if ( !empty ( $ancestors ) ) {

				$ancestors = array_reverse ( $ancestors );

		?>

		<div class="col-8 offset-1">
			<nav aria-label="breadcrumb" class="">
				<ol class="breadcrumb">

					<?php

						foreach ( $ancestors as $ancestor ) {

					?>

					<li class="breadcrumb-item"><a href="<?php echo get_permalink ( $ancestor ); ?>"><?php echo get_the_title ( $ancestor ); ?></a></li>

					<?php

							$i++;

						}

					?>

					<li class="breadcrumb-item"><?php the_title(); ?>

				</ol>
			</nav>
		</div>

		<?php

			}

		?>

		<div class="col-8 offset-1">
			<h4><?php the_title(); ?></h4>
		</div>
	</div>

	<div class="row">
		<div class="col-6 offset-1">
			<?php

				echo apply_filters ( 'the_content', custom_excerpt ( 40, get_the_ID() ) );

			?>
		</div>

		<div class="col-2 offset-1">
			<a href="<?php the_permalink(); ?>" class="btn btn-primary">Read more</a>
		</div>
	</div>
</article>
