<?php
namespace Clasify\Classified\Admin\MetaBoxes;

use Clasify\Classified\Traitval\Traitval;

/**
 * Loader class loads everything related templates
 *
 * since 1.0.0
 */
class Styles {

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
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_frontend_styles' ) );
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
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_frontend_scripts' ) );
	}

	/**
	 * enqueue_frontend_styles loads styles & scripts
	 *
	 * @return void
	 */
	public function enqueue_frontend_styles() {
		 /**
		 * enqueue Select2 style on edit page
		 *
		 * @since 1.0.0
		 */
		wp_enqueue_style( $this->plugin_pref . '-select2', CLASIFY_CLASSIFIED_PLUGIN_ASSETS . ( '/lib/select2/select2.min.css' ), array(), time(), false );

		/**
		 * enqueue metabox style on edit page
		 *
		 * @since 1.0.0
		 */
		wp_enqueue_style( $this->plugin_pref . '-metabox-style', CLASIFY_CLASSIFIED_PLUGIN_ASSETS . ( '/css/metabox.css' ), array(), time(), false );
		wp_enqueue_style( $this->prefix . 'leaflet', CLASIFY_CLASSIFIED_PLUGIN_ASSETS . ( '/css/plugins/leaflet.css' ) );
	}

	/**
	 * enqueue_frontend_scripts loads styles & scripts
	 *
	 * @return void
	 */
	public function enqueue_frontend_scripts() {
		/**
		 * enqueue Select2 script on edit page
		 *
		 * @since 1.0.0
		 */
		wp_enqueue_script( $this->plugin_pref . '-select2', CLASIFY_CLASSIFIED_PLUGIN_ASSETS . ( '/lib/select2/select2.min.js' ), array(), time(), false );
		/**
		 * enqueue metabox script on edit page
		 *
		 * @since 1.0.0
		 */
		wp_enqueue_script( $this->plugin_pref . '-leaflet', CLASIFY_CLASSIFIED_PLUGIN_ASSETS . ( '/js/leaflet.js' ), array( 'jquery' ), time(), false );
		wp_enqueue_script( $this->plugin_pref . '-metabox-script', CLASIFY_CLASSIFIED_PLUGIN_ASSETS . ( '/js/metabox.js' ), array( 'jquery' ), time(), false );
	}
}
