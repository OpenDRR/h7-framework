<?php
  
  include('vars.php');
  
  get_header();
  

?>



    <?php
      
      if (have_posts()) : while (have_posts()) : the_post();
      
    ?>
    
    <h1>This is the home page</h1>
    
    <?php the_title(); ?>
    
    <?php
      
      endwhile; endif;
      
    ?>
    
    

<?php
  
  get_footer();
  
?>