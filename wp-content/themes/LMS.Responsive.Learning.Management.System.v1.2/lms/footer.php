    <?php if( !is_page_template( 'tpl-fullwidth.php' ) && !is_page_template('tpl-landingpage.php') ): ?>
            </div><!-- **Container - End** -->
    <?php endif;?>
        </div><!-- **Main - End** -->


        <?php $dttheme_options = get_option(IAMD_THEME_SETTINGS); $dttheme_general = $dttheme_options['general'];?>
        <!-- **Footer** -->
        <footer id="footer">
            <div class="footer-logo">        			
                <img title="<?php echo __('Footer Logo', 'dt_themes'); ?>" alt="<?php echo __('Footer Logo', 'dt_themes'); ?>" src="<?php echo IAMD_BASE_URL."images/footer-logo.png"; ?>">
            </div>
		<?php
        	if(!empty($dttheme_general['show-footer'])): ?>
        	<div class="footer-widgets-wrapper">
        		<div class="container"><?php dttheme_show_footer_widgetarea($dttheme_general['footer-columns']);?></div>
        	</div><?php
        	endif;?>

        	<div class="copyright">
        		<div class="container"><?php
        			if( !empty($dttheme_general['show-copyrighttext']) ):
        				echo '<div class="copyright-info">';
        				echo stripslashes($dttheme_general['copyright-text']);
        				echo '</div>'; 
        			endif;?>
        			<?php echo do_shortcode('[dt_sc_social /]'); ?>
        		</div>
        	</div>
        </footer><!-- **Footer - End** -->
    </div><!-- **Inner Wrapper - End** -->
</div><!-- **Wrapper - End** -->
<?php
	if (is_singular() AND comments_open())
		wp_enqueue_script( 'comment-reply');

	if(dttheme_option('integration', 'enable-body-code') != '') 
		echo stripslashes(dttheme_option('integration', 'body-code'));
	wp_footer(); ?>
</body>
</html>