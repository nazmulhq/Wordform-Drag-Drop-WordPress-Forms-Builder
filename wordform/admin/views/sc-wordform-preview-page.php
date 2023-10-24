<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
 <html>
  <head>

    <?php wp_head(); ?>
 
  </head>
  <body <?php body_class(); ?> >
 
    <?php wp_body_open(); ?>
     
     	<?php block_template_part('header'); ?>
     	
        <main>                    
           <div class="entry-content wp-block-post-content has-global-padding is-layout-constrained">
           
				<?php 			   			    			   
				foreach( $created_form as $form_element ) {
					echo $form_element;
				} 
				?>
           </div>
        </main> 
        
 
      	<?php //block_template_part('footer'); ?>
      	
    <?php wp_footer();
	      
	      
	  ?>    
 
  </body>
</html>