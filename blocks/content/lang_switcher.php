<?php

  $element_class = array ( 'lang-switcher', 'd-flex' );

  if ( get_sub_field ( 'class' ) != '' ) {
    $element_class = array_merge ( $element_class, explode ( ' ', get_sub_field ( 'class' ) ) );
  }

  $languages = icl_get_languages ( 'skip_missing=0' );

?>

<div class="<?php echo implode ( ' ', $element_class ); ?>">
  <span class="lang-switcher-label mr-2">
    <?php _e ( 'Language', 'fw' ); ?>
  </span>

  <?php

    if ( !empty ( $languages ) ) {

  ?>

  <div class="lang-switcher-menu dropdown">
    <a class="dropdown-toggle text-secondary" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo ICL_LANGUAGE_CODE; ?></a>

    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
      <?php

        foreach ( $languages as $l ) {

          echo '<a href="' . $l['url'] . '" class="dropdown-item ';

          if ( $l['active'] ) echo 'current-lang';

          echo '">' . $l['language_code'] . '</a>';

        }

      ?>
    </div>
  </div>

  <?php

    }

  ?>
</div>
