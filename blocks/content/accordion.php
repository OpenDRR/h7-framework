<?php

	$accordion_classes = $block['accordion_classes'];

	if ( !empty ( $block['accordion'] ) ) {

?>

<div class="accordion <?php echo $accordion_classes['element']; ?>" id="<?php echo $GLOBALS['elements']['current']['id']; ?>-accordion">

	<?php

		$accordion_section = 1;

		foreach ( $block['accordion'] as $accordion ) {

	?>

	<div class="card <?php echo $accordion_classes['card']; ?>">
    <div
			class="card-header
				<?php echo ( $accordion_section == 1 ) ? 'open' : ''; ?>
				<?php echo $accordion_classes['heading']; ?>"
			id="<?php echo $GLOBALS['elements']['current']['id']; ?>-accordion-head-<?php echo $accordion_section; ?>">
      <h4 class="mb-0" data-toggle="collapse" data-target="#<?php echo $GLOBALS['elements']['current']['id']; ?>-accordion-collapse-<?php echo $accordion_section; ?>" aria-expanded="true" aria-controls="<?php echo $GLOBALS['elements']['current']['id']; ?>-accordion-collapse-<?php echo $accordion_section; ?>">
        <?php echo $accordion['heading']; ?>
      </h4>
    </div>

    <div
			id="<?php echo $GLOBALS['elements']['current']['id']; ?>-accordion-collapse-<?php echo $accordion_section; ?>"
			class="collapse
				<?php echo ( $accordion_section == 1 ) ? 'show' : ''; ?>
				<?php echo $accordion_classes['content']; ?>"
			aria-labelledby="<?php echo $GLOBALS['elements']['current']['id']; ?>-accordion-head-<?php echo $accordion_section; ?>"
			data-parent="#<?php echo $GLOBALS['elements']['current']['id']; ?>-accordion">
      <div class="card-body">
				<?php

					echo $accordion['content'];

				?>
      </div>
    </div>
  </div>

	<?php

			$accordion_section++;

		}

	?>

</div>

<?php

	}
