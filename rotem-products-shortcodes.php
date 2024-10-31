<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.journey.co.il
 * @since             1.0.0
 * @package           Rotem_Products_Shortcodes
 *
 * @wordpress-plugin
 * Plugin Name:       Rotem Products Shortcodes
 * Plugin URI:        https://www.journey.co.il/rotem-Products-shortcodes
 * Description:       Add a button to the editor that create a [products] shorcode for Products plugin 
 * Version:           1.0.0
 * Author:            Rotem Shmueli
 * Author URI:        https://www.journey.co.il
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       rotem-products-shortcodes
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'ROTEM_Products_SHORTCODES_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-rotem-Products-shortcodes-activator.php
 */
function activate_rotem_Products_shortcodes() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-rotem-Products-shortcodes-activator.php';
	Rotem_Products_Shortcodes_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-rotem-Products-shortcodes-deactivator.php
 */
function deactivate_rotem_Products_shortcodes() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-rotem-Products-shortcodes-deactivator.php';
	Rotem_Products_Shortcodes_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_rotem_Products_shortcodes' );
register_deactivation_hook( __FILE__, 'deactivate_rotem_Products_shortcodes' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-rotem-Products-shortcodes.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_rotem_Products_shortcodes() {

	$plugin = new Rotem_Products_Shortcodes();
	$plugin->run();

}
run_rotem_Products_shortcodes();

///////////////////////////////////////////////////////////////////////////////////////////////////

// init process for registering our button
add_action('init', 'rotem_product_init');
function rotem_product_init() {

	 //Abort early if the user will never see TinyMCE
	 if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') && get_user_option('rich_editing') == 'true')
		  return;

	 //Add a callback to regiser our tinymce plugin   
	 add_filter("mce_external_plugins", "rotem_product_register"); 

	 // Add a callback to add our button to the TinyMCE toolbar
	 add_filter('mce_buttons', 'rotem_product_register2');
}


//This callback registers our plug-in
function rotem_product_register($plugin_array) {
   $plugin_array['wpse72394_button'] = plugin_dir_url( __FILE__ ) . 'tinymce_buttons.js';
   return $plugin_array;
}

//This callback adds our button to the toolbar
function rotem_product_register2($buttons) {
		   //Add the button ID to the $button array
   $buttons[] = "wpse72394_button";
   return $buttons;
}


add_action('admin_head', 'rotem_product_button');

function rotem_product_button() {
  global $typenow;
  // Check user permissions
  if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) {
    return;
  }

  // Check if WYSIWYG is enabled
  if ( get_user_option('rich_editing') == 'true') {
    add_filter('mce_external_plugins', 'rotem_product_button2');
    add_filter('mce_buttons', 'rotem_product_button3');
  }

}

// Create the custom TinyMCE plugins
function rotem_product_button2($plugin_array) {
  $plugin_array['zz_tc_simple'] = plugin_dir_url( __FILE__ ) . 'tinymce_buttons.js';
  $plugin_array['zz_tc_button'] = plugin_dir_url( __FILE__ ) . 'tinymce_buttons.js';
  $plugin_array['zz_tc_list'] = plugin_dir_url( __FILE__ ) . 'tinymce_buttons.js';
  return $plugin_array;
}
// Add the buttons to the TinyMCE array of buttons that display, so they appear in the WYSIWYG editor 
function rotem_product_button3($buttons) {
  array_push($buttons, 'zz_tc_simple');
  array_push($buttons, 'zz_tc_button');
  array_push($buttons, 'zz_tc_list');
  return $buttons;
}


function get_rotem_pruduct_ajax_posts() {
    // Query Arguments
    $args = array(
        'post_type' => array('product'),
        'post_status' => array('publish'),
        'posts_per_page' => 20,
        'nopaging' => true,
        'order' => 'DESC',
        'orderby' => 'date',
    );

    // The Query
    $ajaxposts = get_posts( $args ); // changed to get_posts from wp_query, because `get_posts` returns an array

    echo json_encode( $ajaxposts );

    exit; // exit ajax call(or it will return useless information to the response)
}

// Fire AJAX action for both logged in and non-logged in users
add_action('wp_ajax_get_ajax_posts', 'get_rotem_pruduct_ajax_posts');
add_action('wp_ajax_nopriv_get_ajax_posts', 'get_rotem_pruduct_ajax_posts');