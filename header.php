<?php

  if ( current_user_can ( 'administrator' ) ) {

    echo "<!-- --- VARS --- -->";
    echo "\n\n<!--\n\n";
    print_r($GLOBALS['vars']);
    echo "\n\n-->";

    echo "\n\n<!-- --- IDs --- -->";
    echo "\n\n<!--\n\n";
    print_r($GLOBALS['ids']);
    echo "\n\n-->";

    echo "\n\n<!-- --- CLASSES --- -->";
    echo "\n\n<!--\n\n";
    print_r($GLOBALS['classes']);
    echo "\n\n-->";

    echo "\n\n<!-- --- DEFAULTS --- -->";
    echo "\n\n<!--\n\n";
    print_r($GLOBALS['defaults']);
    echo "\n\n-->";

    echo "\n\n";

  }

?>
<!doctype html>
<html class="no-js" <?php language_attributes(); ?>>
  <head>
		<meta charset="<?php bloginfo ( 'charset' ); ?>">

		<?php

			/*

			title - replaced with theme_support ( 'title-tag' )

			<title><?php

				// current object

				wp_title ( '—', true, 'right' );

				// site title

				bloginfo ( 'title' );

				// description on home page

				if ( is_front_page() && get_bloginfo ( 'description' ) != '' ) {
					echo ' — ' . get_bloginfo ( 'description' );
				}

			?></title>

			*/

		?>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php

      if ( get_field ( 'favicon', 'option' ) != '' ) {

    ?>

    <link rel="icon" type="image/png" href="<?php echo wp_get_attachment_image_url ( get_field ( 'favicon', 'option' ), 'thumbnail' ); ?>">

    <?php

      }

      wp_head();

    ?>

  </head>

  <body id="<?php echo $GLOBALS['ids']['body']; ?>" <?php body_class ( implode ( ' ', $GLOBALS['classes']['body'] ) ); ?>>
	
	<button type="button" class="btn btn-secondary" data-toggle="tooltip" data-placement="bottom" title="Tooltip on bottom">
		Tooltip on bottom
	</button>

    <?php

			wp_body_open();

    ?>
