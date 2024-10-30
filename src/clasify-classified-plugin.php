<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Clasify\Classified\Admin\Admin;
use Clasify\Classified\Front\Front;
use Clasify\Classified\Common\Common;
use Clasify\Classified\Common\Ajax\Ajax;
use Clasify\Classified\Traitval\Traitval;
use Clasify\Classified\Common\Customer\Dbcustomer;
use Clasify\Classified\Common\Customer\Customermeta;
use Clasify\Classified\Common\Roles\Roles;

final class Clasify_Classified_Plugin {


	use Traitval;

	/**
	 * Plugin Version
	 *
	 * @since 1.2.0
	 * @var string The plugin version.
	 */


	private static $instance;
	public $admin;
	public $front;
	public $common;
	public $ajax;

	private function __construct() {
		$this->define_constants();
		register_activation_hook( CLASIFY_CLASSIFIED_PLUGIN_FILE, array( $this, 'activate' ) );

		add_action( 'activated_plugin', array( $this, 'activation_handler1' ) );
		add_action( 'plugins_loaded', array( $this, 'init_plugin' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'clasify_classified_plugin_admin_enqueue_scripts' ) );
		add_action( 'pre_current_active_plugins', array( $this, 'pre_output1' ) );
	}

	/**
	 * Define the required plugin constants
	 *
	 * @return void
	 */
	public function define_constants() {
		// general constants
		define( 'CLASIFY_CLASSIFIED_PLUGIN_VERSION', CLASIFY_VERSION );
		define( 'CLASIFY_CLASSIFIED_PLUGIN_URL', plugins_url( '', CLASIFY_CLASSIFIED_PLUGIN_FILE ) );
		define( 'CLASIFY_CLASSIFIED_PLUGIN_ASSETS', CLASIFY_CLASSIFIED_PLUGIN_URL . '/assets' );
		define( 'CLASIFY_CLASSIFIED_PLUGIN_ASSETS_CSS', CLASIFY_CLASSIFIED_PLUGIN_ASSETS . '/css' );
		define( 'CLASIFY_CLASSIFIED_PLUGIN_TEMPLATES_DIR', CLASIFY_CLASSIFIED_PLUGIN_PATH . '/templates' );

		define( 'CLASIFY_CLASSIFIED_PLUGIN_DIR', plugin_dir_path( CLASIFY_CLASSIFIED_PLUGIN_FILE ) );
		define( 'CLASIFY_CLASSIFIED_PLUGIN_ASSETS_DIR', CLASIFY_CLASSIFIED_PLUGIN_DIR . '/assets' );
		define( 'CLASIFY_CLASSIFIED_PLUGIN_ASSETS_CSS_DIR', CLASIFY_CLASSIFIED_PLUGIN_ASSETS_DIR . '/css' );

		// src constanst
		define( 'CLASIFY_CLASSIFIED_PLUGIN_SRC_FILE', __FILE__ );
		define( 'CLASIFY_CLASSIFIED_PLUGIN_SRC_PATH', __DIR__ );

		// constants for theme
		$theme = wp_get_theme();
		define( 'THEME_VERSION_CORE', $theme->Version );
		define( 'temp_file', ABSPATH . '/_temp_out.txt' );

		if ( ! defined( 'CLS_PLUGIN_DIR' ) ) {
			define( 'CLS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		}

		if ( ! defined( 'CLS_PLUGIN_URL' ) ) {
			define( 'CLS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		}
	}





	function activation_handler1() {
		$cont = ob_get_contents();
		if ( ! empty( $cont ) ) {
			file_put_contents( temp_file, $cont );
		}
	}


	function pre_output1( $action ) {
		// debug_print_backtrace();
		if ( is_admin() && file_exists( temp_file ) ) {
			$cont = file_get_contents( temp_file );
			if ( ! empty( $cont ) ) {
				echo '<div class="error"> ' . esc_html__( 'Error Message:', 'clasify-classified-listing' ) . $cont . '</div>';
				@unlink( temp_file );
			}
		}
	}

	/**
	 * Initialize the plugin
	 *
	 * @return void
	 */
	public function init_plugin() {
		self::$instance         = self::getInstance();
		self::$instance->common = Common::getInstance();
		self::$instance->front  = Front::getInstance();

		if ( is_admin() ) {
			self::$instance->admin = Admin::getInstance();
		}
	}

	/**
	 * Do stuff upon plugin activation
	 *
	 * @return void
	 */
	public function cl_run_install() {

		$a = new Dbcustomer();
		$b = new Customermeta();
		$c = new Roles();
		$c->add_roles();
		$c->add_caps();
		@$a->create_table();
		@$b->create_table();

		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		$schema          = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}enquiry_message` (
          `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `name` varchar(100) NOT NULL DEFAULT '',
          `email` varchar(30) DEFAULT NULL,
          `phone` varchar(30) DEFAULT NULL,
          `message` varchar(255) DEFAULT NULL,
          `created_for` bigint(20) unsigned NOT NULL,
          `created_at` datetime NOT NULL,
          PRIMARY KEY (`id`)
        ) $charset_collate";

		if ( ! function_exists( 'dbDelta' ) ) {
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		}

		dbDelta( $schema );

	}
	function activate( $network_wide = false ) {
		global $wpdb;

		if ( is_multisite() && $network_wide ) {
			foreach ( $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs LIMIT 100" ) as $blog_id ) {
				switch_to_blog( $blog_id );
				$this->cl_run_install();
				restore_current_blog();
			}
		} else {

			$this->cl_run_install();
		}

	}

	public function clasify_classified_plugin_admin_enqueue_scripts() {
		wp_enqueue_style( $this->plugin_pref . '-admin-style', CLASIFY_CLASSIFIED_PLUGIN_ASSETS . '/css/admin.css', '', time() );
		wp_enqueue_style( $this->plugin_pref . '-admin-fontawesome', 'https://use.fontawesome.com/releases/v6.1.1/css/all.css', '', time() );
	
		wp_enqueue_script( $this->plugin_pref . '-admin-ajax', CLASIFY_CLASSIFIED_PLUGIN_ASSETS . ( '/js/admin-ajax.js' ), array(), time(), false );

		$ajax_var = array(
			'ajax_url'              => esc_url( admin_url( 'admin-ajax.php' ) ),
			'site_url'              => esc_url( site_url() )
		);
		wp_localize_script( $this->plugin_pref . '-admin-ajax', 'ajax_obj', $ajax_var );

		wp_enqueue_script( 'jquery-ui-sortable' );
	}

}

/**
 * Initializes the main plugin
 *
 * @return \Clasify_Classified_Plugin
 */
function CCP() {
	return Clasify_Classified_Plugin::getInstance();
}

// kick-off the plugin
CCP();
