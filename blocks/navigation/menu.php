<?php

  global $element_class;
  global $item_class;
  global $link_class;

  $element_class = array ( 'fw-menu', $block['list_class'] );

	if ( $block['dropdowns'] == 1 ) {
		$element_class[] = 'fw-dropdown-menu';
	}
	
  $item_class = array ( 'fw-menu-item', $block['item_class'] );
  $link_class = array ( 'fw-menu-link', $block['link_class'] );

  include ( locate_template ( 'elements/menu.php' ) );

?>
