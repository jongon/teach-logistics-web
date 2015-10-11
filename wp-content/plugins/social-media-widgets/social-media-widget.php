<?php
/*
Plugin Name: Social Media Widget
Plugin URI: http://www.tatwa.com/
Description:Easily create beautiful social media link with the install of this plugin.This widget takes a simple, extendable approach for displaying your social media profiles on wordpress website.
Version: 1.0
Author: Ashirbad Ray
Author URI: http://www.tatwa.com/
Text Domain: social_media_widget

*/
wp_register_style('socialmediaStylesheet', plugins_url('assets/css/style.css', __FILE__) );
	wp_enqueue_style('socialmediaStylesheet');
	
//Class For Creating Widget
class social_media_widget extends WP_Widget {

	// constructor
	function social_media_widget() {
		/* ... */
		  parent::WP_Widget(false, $name = __('Social Media Widget', 'social_media_widget') );
	}

	// widget form creation
function form($instance) {

// Check values
if( $instance) {
	
     $title = esc_attr($instance['title']);
     $text = esc_attr($instance['text']);
	 $facebookpagelink = esc_attr($instance['facebookpagelink']);
	  $gplus = esc_attr($instance['gplus']);
	   $twitter = esc_attr($instance['twitter']);
	    $pintrest = esc_attr($instance['pintrest']);
	  
	 
} else {
     $title = '';
     $text = '';
	 $facebookpagelink ='';
	  $gplus ='';
	   $twitter ='';
	    $pintrest ='';

}
?>

<p>
<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'social_media_widget'); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
<br><br>
<b>Put Your Social Media Link Below Sections:</b>
<br><br><b><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Facebook Page Link', 'social_media_widget'); ?>
</label></b>
<input class="widefat" id="<?php echo $this->get_field_id('facebookpagelink'); ?>" 
name="<?php echo $this->get_field_name('facebookpagelink'); ?>" type="text" value="<?php echo $facebookpagelink; ?>" />
<!--Google Plus Link-->
<br><br><b><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Google Plus  Link', 'social_media_widget'); ?>
</label></b>
<input class="widefat" id="<?php echo $this->get_field_id('gplus'); ?>" 
name="<?php echo $this->get_field_name('gplus'); ?>" type="text" value="<?php echo $gplus; ?>" />
<!--Twitter Link-->
<br><br><b><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Twitter  Link', 'social_media_widget'); ?>
</label></b>
<input class="widefat" id="<?php echo $this->get_field_id('twitter'); ?>" 
name="<?php echo $this->get_field_name('twitter'); ?>" type="text" value="<?php echo $twitter; ?>" />
<!--Pinterest Link-->
<br><br><b><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Pinterest  Link', 'social_media_widget'); ?>
</label></b>
<input class="widefat" id="<?php echo $this->get_field_id('pintrest'); ?>" 
name="<?php echo $this->get_field_name('pintrest'); ?>" type="text" value="<?php echo $pintrest; ?>" />
</p>
 
<?php
}
	// widget update
	// update widget
function update($new_instance, $old_instance) {
      $instance = $old_instance;
      // Fields
      $instance['title'] = strip_tags($new_instance['title']);
	  $instance['facebookpagelink'] = strip_tags($new_instance['facebookpagelink']);
	   $instance['gplus'] = strip_tags($new_instance['gplus']);
	     $instance['twitter'] = strip_tags($new_instance['twitter']);
		    $instance['pintrest'] = strip_tags($new_instance['pintrest']);
        return $instance;
}
	// widget display
	// display widget
function widget($args, $instance) {
   extract( $args );
   // these are the widget options
   $title = apply_filters('widget_title', $instance['title']);
   $facebookpagelink = apply_filters('facebookpagelink', $instance['facebookpagelink']);
   $gplus = apply_filters('gplus', $instance['gplus']);
   $twitter = apply_filters('twitter', $instance['twitter']);
     $pintrest = apply_filters('pintrest', $instance['pintrest']);
   
   echo $before_widget;
   // Display the widget
   echo '<div class="widget-text wp_widget_plugin_box">';

   // Check if title is set
   if ( $title ) {
      echo $before_title . $title . $after_title;
   }
?>

      <ul class="social-icon_footer">
      <?php if($facebookpagelink){ ?>
       <li><a  class="icon_facebook" href="<?php echo $facebookpagelink;?>" target="_blank"></a></li>
       <?php }?>
        <?php if($gplus){ ?>
       <li><a class="icon_g-plus" href="<?php echo $gplus;?>" target="_blank"></a></li>
       <?php }?>
         <?php if($twitter){ ?>
       <li><a class="icon_twitter" href="<?php echo $twitter;?>" target="_blank"></a></li>
        <?php }?>
          <?php if($pintrest){ ?>
      	<li><a class="icon_pinterest" href="<?php echo $pintrest;?>" target="_blank"></a></li>
        <?php }?>
      </ul>
    </div>

<?php
   echo $after_widget;
   

}
}
// register widget
add_action('widgets_init', create_function('', 'return register_widget("social_media_widget");'));