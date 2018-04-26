<?php
  
  include('vars.php');
  
?>
<!doctype html>
<html>
  <head>
     <title><?php
    	wp_title( '—', true, 'right' );
    
    	bloginfo('title');
    
    	if (is_front_page()) echo ' — ' . get_bloginfo('description');
    
    ?></title>
    
    <?php
      
      wp_head();
      
    ?>

  </head>
  
  <?php
    
    $body_ID = 'page';
    $body_class = '';
    
    if (is_front_page()) {
      $body_ID = 'page-home';
    } else {
      $body_ID = 'page-' . get_the_slug();
    }
    
    if (!is_front_page()) {
      $body_class .= ' sub-page';
    }
    
  ?>
  
  <body id="<?php echo $body_ID; ?>" <?php body_class($body_class); ?>>