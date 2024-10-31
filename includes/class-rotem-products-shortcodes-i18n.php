<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.journey.co.il
 * @since      1.0.0
 *
 * @package    Rotem_Products_Shortcodes
 * @subpackage Rotem_Products_Shortcodes/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Rotem_Products_Shortcodes
 * @subpackage Rotem_Products_Shortcodes/includes
 * @author     Rotem Shmueli <yortem@gmail.com>
 */
class Rotem_Products_Shortcodes_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'rotem-Products-shortcodes',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
