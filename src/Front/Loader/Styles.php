<?php
namespace Clasify\Classified\Front\Loader;

use Clasify\Classified\Traitval\Traitval;

/**
 * Loader class loads everything related templates
 *
 * since 1.0.0
 */
class Styles extends Condition {

	use Traitval;

	/**
	 * enque_styles calls wp_enqueue_scripts hooks to load styles
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function enqueue_styles() {
		/**
		   * Load archive page style
		   */
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_styles' ) );
	}

	/**
	 * enque_scripts calls wp_enqueue_scripts hooks to load scripts
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {
		 /**
		 * Load archive page scripts
		 */
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_scripts' ) );
	}

	/**
	 * enqueue_frontend_styles loads styles & scripts
	 *
	 * @return void
	 */
	public function enqueue_frontend_styles() {
		wp_enqueue_style( $this->plugin_pref . '-frontend-fontawesome', 'https://use.fontawesome.com/releases/v6.1.1/css/all.css', array(), time(), false );
		wp_enqueue_style( $this->plugin_pref . '-select2', CLASIFY_CLASSIFIED_PLUGIN_ASSETS . ( '/lib/select2/select2.min.css' ), array(), time(), false );
		wp_enqueue_style( $this->prefix . 'leaflet', CLASIFY_CLASSIFIED_PLUGIN_ASSETS . ( '/css/plugins/leaflet.css' ) );
		wp_enqueue_style( $this->prefix . 'bootstrap', CLASIFY_CLASSIFIED_PLUGIN_ASSETS . ( '/css/plugins/bootstrap.min.css' ), array(), time(), false );
		wp_enqueue_style( $this->prefix . 'balloon', CLASIFY_CLASSIFIED_PLUGIN_ASSETS . ( '/css/plugins/balloon.min.css' ), array(), time(), false );
		wp_enqueue_style( $this->prefix . 'slick', CLASIFY_CLASSIFIED_PLUGIN_ASSETS . ( '/css/plugins/slick.css' ), array(), time(), false );
		wp_enqueue_style( $this->prefix . 'slick-theme', CLASIFY_CLASSIFIED_PLUGIN_ASSETS . ( '/css/plugins/slick-theme.css' ), array(), time(), false );
		wp_enqueue_style( $this->prefix . 'responsive', CLASIFY_CLASSIFIED_PLUGIN_ASSETS . ( '/css/responsive.css' ), array(), time(), false );
		wp_enqueue_style( $this->prefix . 'var', CLASIFY_CLASSIFIED_PLUGIN_ASSETS . ( '/css/var.css' ), array(), time(), false );
		wp_enqueue_style( $this->prefix . 'frontend-layout', CLASIFY_CLASSIFIED_PLUGIN_ASSETS . ( '/css/pages/frontend_layout.css' ), array(), time(), false );
		wp_enqueue_style( $this->plugin_pref . '-frontend-style', CLASIFY_CLASSIFIED_PLUGIN_ASSETS . ( '/css/styles.css' ) );
		wp_enqueue_style( $this->plugin_pref . '-leaflet-markercluster', CLASIFY_CLASSIFIED_PLUGIN_ASSETS . ( '/lib/leaflet-markercluster/MarkerCluster.css' ) );
		wp_enqueue_style( $this->plugin_pref . '-leaflet-markercluster-default', CLASIFY_CLASSIFIED_PLUGIN_ASSETS . ( '/lib/leaflet-markercluster/MarkerCluster.Default.css' ) );
	}

	/**
	 * enqueue_frontend_scripts loads styles & scripts
	 *
	 * @return void
	 */
	public function enqueue_frontend_scripts() {
		global $post;

		wp_enqueue_script( 'jquery-ui-dialog' );
		wp_enqueue_script( $this->plugin_pref . '-slick', CLASIFY_CLASSIFIED_PLUGIN_ASSETS . ( '/js/slick.js' ), array( 'jquery' ), '', true );
		wp_enqueue_script( $this->plugin_pref . '-select2', CLASIFY_CLASSIFIED_PLUGIN_ASSETS . ( '/lib/select2/select2.min.js' ), array(), time(), false );
		wp_enqueue_script( $this->plugin_pref . '-frontend-script', CLASIFY_CLASSIFIED_PLUGIN_ASSETS . ( '/js/scripts.js' ), array(), time(), true );
		wp_enqueue_script( $this->plugin_pref . '-frontend-main', CLASIFY_CLASSIFIED_PLUGIN_ASSETS . ( '/js/main.js' ), array(), time(), true );
		wp_enqueue_script( $this->plugin_pref . '-leaflet', CLASIFY_CLASSIFIED_PLUGIN_ASSETS . ( '/js/leaflet.js' ), array(), time(), false );
		wp_enqueue_script( $this->plugin_pref . '-leaflet-markercluster', CLASIFY_CLASSIFIED_PLUGIN_ASSETS . ( '/lib/leaflet-markercluster/leaflet.markercluster.js' ), array(), time(), false );
		wp_enqueue_script( $this->plugin_pref . '-frontend-map', CLASIFY_CLASSIFIED_PLUGIN_ASSETS . ( '/js/map.js' ), array(), time(), true );


		$ajax_var = array(
			'ajax_url'              => esc_url( admin_url( 'admin-ajax.php' ) ),
			'site_url'              => esc_url( site_url() ),
			'abuse_dialog'          => esc_html( 'Report Abuse' ),
			'checkout_nonce'        => wp_create_nonce( 'cl_checkout_nonce' ),
			'checkout_error_anchor' => '#cl_purchase_submit',
			'currency_sign'         => CCP()->common->formatting->cl_currency_filter( '' ),
			'currency_pos'          => cl_admin_get_option( 'currency_position', 'before' ),
			'decimal_separator'     => cl_admin_get_option( 'decimal_separator', '.' ),
			'thousands_separator'   => cl_admin_get_option( 'thousands_separator', ',' ),
			'number_of_decimal'     => cl_admin_get_option( 'number_of_decimal', '0' ),
			'no_gateway'            => __( 'Please select a payment method', 'clasify-classified-listing' ),
			'no_discount'           => __( 'Please enter a discount code', 'clasify-classified-listing' ), // Blank discount code message
			'enter_discount'        => __( 'Enter discount', 'clasify-classified-listing' ),
			'discount_applied'      => __( 'Discount Applied', 'clasify-classified-listing' ), // Discount verified message
			'no_email'              => __( 'Please enter an email address before applying a discount code', 'clasify-classified-listing' ),
			'no_username'           => __( 'Please enter a username before applying a discount code', 'clasify-classified-listing' ),
			'purchase_loading'      => __( 'Please Wait...', 'clasify-classified-listing' ),
			'complete_purchase'     => CCP()->front->checkout->cl_get_checkout_button_purchase_label(),
			'taxes_enabled'         => CCP()->front->tax->cl_use_taxes() ? '1' : '0',
			'cl_version'            => CLASIFY_CLASSIFIED_PLUGIN_VERSION,
		);
		wp_localize_script( $this->plugin_pref . '-frontend-main', 'ajax_obj', $ajax_var );
	}
}
