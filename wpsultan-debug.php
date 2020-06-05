<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wp-sultan.com/
 * @since             1.0.0
 * @package           Wpsultan_Debug
 *
 * @wordpress-plugin
 * Plugin Name:       WPSULTAN DEBUG
 * Plugin URI:        https://wp-sultan.com/debug/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            WP Sultan
 * Author URI:        https://wp-sultan.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wpsultan-debug
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
	die;
}

define( 'WPSULTAN_DEBUG_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wpsultan-debug-activator.php
 */
function activate_wpsultan_debug() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpsultan-debug-activator.php';
	Wpsultan_Debug_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wpsultan-debug-deactivator.php
 */
function deactivate_wpsultan_debug() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpsultan-debug-deactivator.php';
	Wpsultan_Debug_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wpsultan_debug' );
register_deactivation_hook( __FILE__, 'deactivate_wpsultan_debug' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wpsultan-debug.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wpsultan_debug() {
	$plugin = Wpsultan_Debug::get_instance();
	$plugin->run();
}
run_wpsultan_debug();
