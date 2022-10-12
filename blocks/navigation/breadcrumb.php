<?php

	global $post;
	
	$old_post = $post;
	
	$post = get_post ( $GLOBALS['vars']['current_query']->ID );
	
	setup_postdata ( $post );

  $breadcrumb_items = array();

  $include_home = false;

  if ( get_sub_field ( 'include_home' ) == 1 ) {
    $include_home = true;

    $breadcrumb_items[] = array (
      'id' => get_option( 'page_on_front' ),
      'url' => get_permalink ( get_option ( 'page_on_front' ) ),
      'title' => 'Home',
      'current' => false
    );
  }

  if ( is_single() ) {

    $current_post_type = get_post_type_object ( get_post_type() );

    $breadcrumb_items[] = array (
      'id' => get_the_ID(),
      'url' => get_permalink(),
      'title' => $current_post_type->labels->singular_name,
      'current' => true
    );

  } elseif ( is_page() ) {

    $page_ancestors = get_ancestors ( get_the_ID(), 'page' );

    foreach ( $page_ancestors as $ancestor ) {

      $breadcrumb_items[] = array (
        'id' => $ancestor,
        'url' => get_permalink ( $ancestor ),
        'title' => get_the_title ( $ancestor ),
        'current' => false
      );

    }

    $breadcrumb_items[] = array (
      'id' => get_the_ID(),
      'url' => get_permalink(),
      'title' => get_the_title(),
      'current' => true
    );

  } elseif ( is_archive() ) {

    if ( !isset ( $current_query ) ) $current_query = get_queried_object();

    // $breadcrumb_items[] = array (
    //   'id' => $current_query->term_id,
    //   'url' => get_term_link ( $current_query->term_id, $current_query->taxonomy ),
    //   'title' => $current_query->name,
    //   'current' => true
    // );

    $taxonomy_object = get_taxonomy ( $current_query->taxonomy );
    $taxonomy_labels = get_taxonomy_labels ( $taxonomy_object );

    $breadcrumb_items[] = array (
      'title' => $taxonomy_labels->name,
      'current' => true
    );

  }

?>

<nav id="<?php echo get_current_element_ID(); ?>-breadcrumb" aria-label="breadcrumb" class="">
  <ol class="breadcrumb">

    <?php

      foreach ( $breadcrumb_items as $item ) {

    ?>

    <li class="breadcrumb-item <?php echo ( $item['current'] == true ) ? 'active' : ''; ?>">
      <?php

        if ( $item['current'] == false ) {
          echo '<a href="' . $item['url'] . '">';
        }

        echo $item['title'];

        if ( $item['current'] == false ) {
          echo '</a>';
        }

      ?>
    </li>

    <?php

      }

    ?>
  </ol>
</nav>

<?php

	wp_reset_postdata();
	
	$post = $old_post;
	
	setup_postdata ( $post );