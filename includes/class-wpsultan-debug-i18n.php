<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://wp-sultan.com
 * @since      1.0.0
 *
 * @package    Wpsultan_Debug
 * @subpackage Wpsultan_Debug/includes
 */
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
	die;
}
/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wpsultan_Debug
 * @subpackage Wpsultan_Debug/includes
 * @author     Your Name <email@example.com>
 */
class Wpsultan_Debug_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wpsultan-debug',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
