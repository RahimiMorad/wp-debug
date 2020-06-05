<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wp-sultan.com
 * @since      1.0.0
 *
 * @package    Wpsultan_Debug
 * @subpackage Wpsultan_Debug/admin
 */
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
	die;
}

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wpsultan_Debug
 * @subpackage Wpsultan_Debug/admin
 * @author     Your Name <email@example.com>
 */
class Wpsultan_Debug_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $wpsultan_debug    The ID of this plugin.
	 */
	private $wpsultan_debug;
	private $wp_debug_ready;
	private $debug_file;
	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $wpsultan_debug       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $wpsultan_debug, $version, $object ) {

		$this->wpsultan_debug = $wpsultan_debug;
		$this->version        = $version;
		$this->load_dependencies();
		$this->init();

	}
	public function my_active_notice() {
		?>
<br>
<div class="update-nag mb-3" style="border-left: 4px solid #000;    background-color: #fbff00;">
    <a target="_blank"
        href="https://wp-sultan.com/Debug/"><?php _e( 'WP_SULTAN Debugging Plugin', 'wpsultan-debug' );?></a>
    <?php _e( 'Needs your WordPress Debug set to be Enabled .', 'wpsultan-debug' );?><a href="
		<?php
$url = admin_url() . "admin.php?page=wpsultan-debug&active=1";
		echo wp_nonce_url( $url, 'active_wb_debug' );
		?>"
        aria-label="<?php _e( 'Just CLICK HERE to Handle everything Automatically ;)', 'wpsultan-debug' );?>"><?php _e( 'Just CLICK HERE to Handle everything Automatically ;)', 'wpsultan-debug' );?></a>
</div>
<?php
}

	private function init() {
		if ( false === WP_DEBUG || false === WP_DEBUG_LOG ) {
			add_action( 'admin_notices', array( $this, 'my_active_notice' ) );
			$this->wp_debug_ready = false;
		} else {
			$this->wp_debug_ready = true;
		}
		if ( true === WP_DEBUG_LOG ) {
			$this->debug_file = '/debug.log';
		} else {
			$this->debug_file = '/' . WP_DEBUG_LOG;
		}
	}

	private function not_found_wp_debug() {
		$string_debug = "";
		if ( false === $this->check_wp_debug() ) {
			$string_debug .= "define('WP_DEBUG', true);" . PHP_EOL;
		}
		if ( false === $this->check_wp_debug_log() ) {
			$string_debug .= "define('WP_DEBUG_LOG', true);" . PHP_EOL;
		}
		if ( !empty( $string_debug ) ) {
			$this->write( $string_debug );
		}

	}

	private function write( $string_debug ) {

		$file = ABSPATH . 'wp-config.php';

		$string_debug .= "define('WP_DEBUG_DISPLAY', false);" . PHP_EOL . "@ini_set('display_errors', 0);" . PHP_EOL;

		if ( @is_writable( $file ) ) {
			/* Check is wp-config already containes required strings */
			$all   = file_get_contents( $file );
			$find  = strpos( $all, "/* That's all, stop editing! Happy publishing. */" );
			$write = substr( $all, 0, $find ) . $string_debug . PHP_EOL . substr( $all, $find );
			file_put_contents( $file, $write );
			/* Check if writable wp-content directory and create log file */
			$log = @fopen( WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'debug.log', 'w' );
			if ( empty( $log ) ) {
				return __( "File 'wp-config.php' contains the following code, but plugin couldn't create 'debug.log', change permissions to the 'wp-content' directory, or create this file by yourself", 'wpsultan-debug' );
			}
		} else {
			return __( "Plugin couldn't open and rewritable 'wp-config.php', change permissions to the file, or try the next method", 'wpsultan-debug' );
		}
	}

	private function active() {
		$file         = get_home_path() . 'wp-config.php';
		$pattern      = "/^define\(\s?'WP_DEBUG'\s?,\s?(false|true)\s?\);/";
		$string_debug = "define('WP_DEBUG', true);" . PHP_EOL . "define('WP_DEBUG_LOG', true);" . PHP_EOL . "define('WP_DEBUG_DISPLAY', false);" . PHP_EOL . "@ini_set('display_errors', 0);" . PHP_EOL;
		if ( @is_writable( $file ) ) {
			if ( false === strstr( file_get_contents( $file ), $string_debug ) ) {
				$not_found = true;
				$wpconfig  = fopen( $file, 'c+' );
				while ( !feof( $wpconfig ) ) {
					$line = fgets( $wpconfig );
					if ( preg_match( $pattern, $line, $matches ) ) {
						$offset       = ftell( $wpconfig );
						$delta_offset = strlen( $line );
						$not_found    = false;
					}
				}
				if ( $not_found ) {
					$this->not_found_wp_debug();
				} else {
					$last_content = file_get_contents( $file, null, null, $offset );
					fseek( $wpconfig, $offset - $delta_offset );
					fwrite( $wpconfig, $string_debug . $last_content );
					fclose( $wpconfig );
				}
			}
		}
		$url = admin_url() . "admin.php?page=wpsultan-debug";
		?>
<script>
window.location = '<?php echo $url; ?>';
</script>
<?php
}
	private function check_wp_debug() {

		if ( false === WP_DEBUG ) {
			return false;
		}
		return true;
	}

	private function check_wp_debug_log() {

		if ( false === WP_DEBUG_LOG ) {
			return false;
		}
		if ( true === WP_DEBUG_LOG ) {
			return true;
		}
		return WP_DEBUG_LOG;
	}

	private function check_wp_debug_display() {

		if ( false === WP_DEBUG_DISPLAY ) {
			return false;
		}
		return true;
	}

	/**
	 * Load the required dependencies for the Admin facing functionality.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wppb_Demo_Plugin_Admin_Settings. Registers the admin settings and page.
	 *
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wpsultan_Debug_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wpsultan_Debug_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->wpsultan_debug, plugin_dir_url( __FILE__ ) . 'css/wpsultan-debug-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wpsultan_Debug_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wpsultan_Debug_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->wpsultan_debug, plugin_dir_url( __FILE__ ) . 'js/wpsultan-debug-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function wpsultan_debug_admin_menus() {

		$admin_page_hooks   = [];
		$admin_page_hooks[] = add_menu_page( __( 'My Debug File', 'wpsultan-debug' ), __( 'My Debug File', 'wpsultan-debug' ), 'administrator', $this->wpsultan_debug, array( $this, 'wpsultan_debug_main_admin_display' ), 'dashicons-text-page', '80.99999' );

		foreach ( $admin_page_hooks as $admin_page_hook ) {

			add_action( "load-{$admin_page_hook}", array( $this, 'enqueue_assets_only_plugin_pages' ) );

		}

	}

	public function enqueue_assets_only_plugin_pages() {

		wp_enqueue_style( 'bootstrap-reboot', plugin_dir_url( __FILE__ ) . 'css/bootstrap-reboot.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'bootstrap', plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array(), $this->version, 'all' );

	}

	public function wpsultan_debug_main_admin_display() {

		require_once 'partials/' . $this->wpsultan_debug . '-admin-main-display.php';

	}

	private function get_debug_file() {
		return $this->debug_file;
	}

}
