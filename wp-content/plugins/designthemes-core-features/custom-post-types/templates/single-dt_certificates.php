<?php get_header(); ?>

	<!-- ** Primary Section ** -->
	<section id="primary" class="content-full-width">
    
		<?php 
		if( have_posts() ): while( have_posts() ): the_post();
		$the_id = get_the_ID(); 
		?>
        
        <article id="post-<?php the_ID(); ?>" <?php post_class('dt-sc-certificate-single'); ?>>
            
            <?php
			$background_image = get_post_meta ( $post->ID, 'background-image', TRUE );
			$custom_class = get_post_meta ( $post->ID, 'custom-class', TRUE );
			$enable_print = get_post_meta ( $post->ID, 'enable-print', TRUE );
			
			if(is_user_logged_in() && isset($enable_print) && $enable_print != '')
				echo '<a href="#" class="dt_print_certificate"><span class="fa fa-print"></span>'. __('Print', 'dt_themes').'</a>';
				
			echo '<div class="dt-sc-certificate-container '.$custom_class.'" style="background-image:'.$background_image.'">';
			echo do_shortcode(get_the_content());
			echo '</div>';
			?>
                            
                    
        </article>
            
		<?php
        endwhile; endif;
        ?>       

	</section><!-- ** Primary Section End ** -->
    
<?php get_footer(); ?>