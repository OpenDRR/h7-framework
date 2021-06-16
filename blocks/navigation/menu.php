<?php

  global $element_class;
  global $item_class;
  global $link_class;

  $element_class = array ( 'fw-menu', get_sub_field ( 'list_class' ) );

	if ( get_sub_field ( 'dropdowns' ) == 1 ) {
		$element_class[] = 'fw-dropdown-menu';
	}
	
  $item_class = array ( 'fw-menu-item', get_sub_field ( 'item_class' ) );
  $link_class = array ( 'fw-menu-link', get_sub_field ( 'link_class' ) );

  // $element_class = array_merge ( $element_class, array ( 'hero-menu' ) );
  // $item_class = array_merge ( $item_class, array ( 'hero-menu-item' ) );
  // $link_class = array_merge ( $link_class, array ( 'hero-menu-link' ) );

  include ( locate_template ( 'elements/menu.php' ) );

?>
