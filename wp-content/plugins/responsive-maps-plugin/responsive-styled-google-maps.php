<?php
/*
Plugin Name: Responsive Styled Google Maps
Description: The only wordpress plugin on the market which adds RESPONSIVE AND STYLED google maps to pages and posts using a simple shortcode: [res_map address="street, city, country"]
Version: 2.13
Author: greenline
Author URI: http://codecanyon.net/user/greenline
Plugin URI: http://yava.ro
*/

/**
 * Set-up Action and Filter Hooks
 */
add_action('admin_menu', 'rsmaps_add_options_page');
add_filter('plugin_action_links', 'rsmaps_plugin_action_links', 10, 2);

/**
 * Add a menu item for plugin's settings in wordpress admin panel
 */
function rsmaps_add_options_page() {
    add_options_page('Responsive Styled Google Maps Helper', 'Responsive Styled Google Maps Helper', 'manage_options', __FILE__, 'rsmaps_render_form');
}

/**
 * Display a Settings link on the Plugins admin page
 */
function rsmaps_plugin_action_links($links, $file) {
    if ($file == plugin_basename( __FILE__ )) {
        $rsmaps_links = '<a href="'.get_admin_url().'options-general.php?page=responsive-maps-plugin/responsive-styled-google-maps.php">'.__('Settings').'</a>';
        // Make the 'Settings' link appear first
        array_unshift( $links, $rsmaps_links );
    }
    return $links;
}

/**
 * Now add stylesheet needed for the plugin
 */
function responsive_map_css() {
    wp_register_style('responsive_map_css', plugins_url('includes/css/style.css', __FILE__), false, '2.13');
    // Enque the stylesheet file
    wp_enqueue_style('responsive_map_css');
}
add_action('wp_enqueue_scripts', 'responsive_map_css');
 
/**
 * Register needed jquery scripts
 */
function responsive_map_scripts() {
    wp_register_script('geogooglemap', (is_ssl() ? 'https://' :'http://').'maps.googleapis.com/maps/api/js?sensor=false&v=3.exp', array( 'jquery' ), null, true);
    wp_register_script('jquerygmap', plugins_url('includes/js/jquery.gmap.min.js', __FILE__), array('jquery'), '3.3.3', true);
}
add_action('wp_enqueue_scripts', 'responsive_map_scripts');

/**
 * Register and enqueue scripts neccessary to plugin's settings page
 */
function admin_responsive_map_scripts() {
    // Register the neccessary scripts
    wp_register_script('geogooglemap', (is_ssl() ? 'https://' :'http://').'maps.googleapis.com/maps/api/js?v=3.exp&amp;libraries=adsense&amp;sensor=false');
    wp_register_script('jquerygmap', plugins_url('includes/js/jquery.gmap.min.js', __FILE__), array('jquery'), '3.3.3');
    wp_register_script('rsmaps', plugins_url('includes/js/rsmaps.functions.js', __FILE__), array('jquery'), '2.13');
    
    // Enque the neccessary scripts
    wp_enqueue_script("jquery");
    wp_enqueue_script('geogooglemap');
    wp_enqueue_script('jquerygmap');
    wp_enqueue_script('rsmaps');
}
add_action('admin_print_scripts', 'admin_responsive_map_scripts');

/**
 * Add the Farbtastic color picker script and style
 */
function load_color_picker_script() {
    wp_enqueue_script('farbtastic');
}
function load_color_picker_style() {
    wp_enqueue_style('farbtastic');	
}
add_action('admin_print_scripts', 'load_color_picker_script');
add_action('admin_print_styles', 'load_color_picker_style');

/**
 * Include the php functions needed for various transformations for the map.
 */
require('functions.php');

/**
 * Include the settings panel (the shortcode generator form)
 */
require('panel.php');

/**
 * Define the shortcode: [res_map] and its attributes
 */
function responsive_map_shortcode($atts) {

    // Extract the attributes user gave in the shortcode
    $atts = shortcode_atts(array(
      'width'           => '',        // Leave blank for 100% (responsive map), or use a width in 'px' or '%'
      'height'          => '500px',   // Use a height in 'px' or '%'
      'maptype'         => 'roadmap', // Possible values: roadmap, satellite, terrain or hybrid
      'zoom'            => 14,        // Zoom, use values between 1-19
      'address'         => 'usa',     // Markers addresses in this format: street, city, country | street, city, country | street, city, country
      'description'     => '',        // Markers descriptions in this format: description1 | description2 | description3 (one for each marker address above)
      'popup'           => 'false',   // true or false
      'pancontrol'      => 'false',   // true or false
      'zoomcontrol'     => 'false',   // true or false
      'draggable'       => 'true',    // true or false
      'scrollwheel'     => 'false',   // true or false
      'typecontrol'     => 'false',   // true or false
      'scalecontrol'    => 'false',   // true or false
      'streetcontrol'   => 'false',   // true or false
      'directionstext'  => '',        // The text to be displayed for directions link
      'center'          => '',        // The point where the map should be centered (latitude, longitude) for instance: center="38.980288, 22.145996"
      'icon'            => 'green',   // Possible color values: black, blue, gray, green, magenta, orange, purple, red, white, yellow or a link to a custom image icon
      'color'           => '',        // Map color. Possible values: 'classic' (classic colors), 'bw' (black&white) or a '#123456' (a hex color)
      'refresh'         => 'false'    // true or false (true if the map should be refreshed and re-centered when window is scaled; false otherwise)
    ), $atts);
    
    // Enque the neccessary jquery files
    wp_enqueue_script("jquery");
    wp_enqueue_script('geogooglemap');
    wp_enqueue_script('jquerygmap');

    // Generate an unique identifier for the map
    $mapid = rand();

    // Extract the map type
    $atts['maptype'] = strtoupper($atts['maptype']);
    
    // If width or height were specified in the shortcode, extract them too
    $dimensions = 'height:'.$atts['height'];
    if($atts['width'])
        $dimensions .= ';width:'.$atts['width'];

    // If color is not set, show the default map style. If color is set to 'bw' show black&white map. Otherwise, if color given, set the map color to the given color.
    if (isset($atts['color']) && $atts['color'] == 'bw') {
        $style = '[ { "stylers": [ { "featureType": "all" }, { "saturation": -100 }, { "gamma": 0.50 }, {"lightness": 30 } ] } ]';
    } else if (isset($atts['color']) && $atts['color'] == 'classic') {
        $style = '[]';
    } else {
        $style = '[ { "stylers": [ { "hue": "' . $atts['color'] . '" } ] } ]';
    }
    
    // Clean the html code in the directionstext or set the default value if directionstext was not specified in the shortcode
    if (isset($atts['directionstext']) && strlen(trim($atts['directionstext'])) != 0) {
        $atts['directionstext'] = $atts['directionstext'];
    } 
    
    // Extract the langitude and longitude for the map center
    if (trim($atts['center'])  != "") {
        sscanf($atts['center'], '%f, %f', $lat, $long);
    } else {
        $lat = 'null'; $long = 'null';
    }
    
    // Split the addresses and descriptions (by | delimiter) and build markers JSON list
    if ($atts['address'] != '') {
      $addresses = explode("|",$atts['address']);
      $descriptions = explode("|",$atts['description']);
      $icons = explode("|",$atts['icon']);

      // Build a marker for each address
      $markers = '[';

      for($i = 0;$i < count($addresses);$i ++) {
        $address = cleanHtml($addresses[$i]);
        
        // If multiple markers, hide popup, else show popup according to parameter from shortcode
        if (count($addresses) > 1) {
            $atts['popup'] = "no";
        } 
        
        // if it's empty, set the default description equal to the the address
        if(isset($descriptions[$i]) && strlen(trim($descriptions[$i])) != 0) {
            $html = $descriptions[$i];  
        }
        else
            $html = $address;
            
        // Add the directions link to the description
        $directions = 'http://maps.google.com/?daddr=' . urlencode($address);
        $html .= '<strong><br><a target=\'_blank\' href="'. $directions .'">'. $atts['directionstext'] .'</a></strong>' ;
        
        // Prepare the description html
        $html = cleanHtml($html);
        
        // Get the correct icon image based on icon color/url given in the shortcode
        $icon = getIcon($icons[$i]);
        
        // Extract the langitude and longitude for the map center
        $marker_latitude = null;
        $marker_longitude = null;
        if (trim($address)  != "") {
            sscanf($address, '%f, %f', $marker_latitude, $marker_longitude);
        }
        // If more markers, add the neccessary "," delimiter between markers
        if ($i > 0) $markers .= ",";
        
        // Build markers list based on given address or latitude/longitude
        if ($marker_latitude == '' || $marker_longitude == '') {
            $markers .= '{
                    address: \''. $address .'\', 
                    html:\''. $html .'\',
                    popup: '. toBool($atts['popup']) .',
                    flat: true,
                    icon: {
                        image: \''. $icon .'\',
                        iconsize: [50, 50],
                        iconanchor: null,
                        shadow: \''. plugins_url('/includes/icons/shadow.png', __FILE__) .'\',
                        shadowsize: [50, 50],
                        shadowanchor: null}}';
        } else {
            $markers .= '{
                    latitude:' . $marker_latitude .', 
                    longitude:' . $marker_longitude .', 
                    html:"'. $html .'",
                    popup: '. toBool($atts['popup']) .',
                    flat: true,
                    icon: {
                        image: \''. $icon .'\',
                        iconsize: [50, 50],
                        iconanchor: [0, 0],
                        infowindowanchor:[0,0],
                        shadow: \''. plugins_url('/includes/icons/shadow.png', __FILE__) .'\',
                        shadowsize: [50, 50],
                        shadowanchor: null}}';
        }
      }
      $markers .= ']';
    }
    // Tell PHP to start output buffering
    ob_start();
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
    var mapdiv = jQuery("#responsive_map_<?php echo $mapid; ?>");
    mapdiv.gMapResp({
      maptype: google.maps.MapTypeId.<?php echo $atts['maptype']; ?>,
      zoom: <?php echo $atts['zoom']; ?>,
      markers: <?php echo $markers; ?>,
      panControl: <?php echo toBool($atts['pancontrol']); ?>,
      zoomControl: <?php echo toBool($atts['zoomcontrol']); ?>,
      draggable: <?php echo toBool($atts['draggable']); ?>,
      scrollwheel: <?php echo toBool($atts['scrollwheel']); ?>,
      mapTypeControl: <?php echo toBool($atts['typecontrol']); ?>,
      scaleControl: <?php echo toBool($atts['scalecontrol']); ?>,
      streetViewControl: <?php echo toBool($atts['streetcontrol']); ?>,
      overviewMapControl: true,
      styles: <?php echo $style; ?>,
      latitude: <?php echo $lat; ?>,
      longitude: <?php echo $long; ?>
     });
  });
  <?php if (isset($atts['refresh']) && $atts['refresh'] == 'yes') { ?>
  window.onresize = function() {
        jQuery('.responsive-map').each(function(i, obj) {
            var gmap = jQuery(this).data('gmap').gmap;
            google.maps.event.trigger(gmap, 'resize');
            jQuery(this).gMapResp('fixAfterResize');
        });
  };
  <?php } ?>
  </script>
  <div id="responsive_map_<?php echo $mapid; ?>" class="responsive-map" style="<?php echo $dimensions; ?>;"></div>
  <?php
  return ob_get_clean();
}
add_shortcode('res_map', 'responsive_map_shortcode');