<?php
/**
 * @package CLASIFYCLASSIFIEDLISTING
 * @version 1.0.7
 */
/*
Plugin Name: Clasify Classified Listing
Plugin URI: https://wordpress.org/plugins/clasify-classified-listing/
Description: One of the best and advanced listing plugin. Which is a comprehensive solution to create professional looking listing site of any kind.
Version: 1.0.7
Requires at least: 5.2
Requires PHP: 7.2
Author: SmartDataSoft
Author URI: http://www.smartdatasoft.com/
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: clasify-classified-listing
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CLASIFY_CLASSIFIED_PLUGIN_TITLE', 'Clasify Classified Listing' );
define( 'CLASIFY_CLASSIFIED_PLUGIN_PATH', dirname( __FILE__ ) );
define( 'CLASIFY_CLASSIFIED_PLUGIN_FILE', __FILE__ );
define( 'CLASIFY_VERSION', '1.0.7' );

add_action( 'plugins_loaded', 'clasify_classified_plugin_load_plugin_textdomain' );
if ( ! version_compare( PHP_VERSION, '7.0', '>=' ) ) {
	add_action( 'admin_notices', 'clasify_classified_plugin_fail_php_version' );
} elseif ( ! version_compare( get_bloginfo( 'version' ), '5.2', '>=' ) ) {
	add_action( 'admin_notices', 'clasify_classified_plugin_fail_wp_version' );
} else {
	require_once __DIR__ . '/src/clasify-classified-plugin.php';
}

/**
 * clasify_classified_plugin_fail_php_version admin notice for minimum PHP version.
 *
 * Warning when the site doesn't have the minimum required PHP version.
 *
 * @since 1.0.0
 *
 * @return void
 */

function clasify_classified_plugin_fail_php_version() {
	 /* translators: %s: PHP version */
	$message      = sprintf( esc_html__( '%1$s requires PHP version %2$s+, plugin is currently NOT RUNNING.', 'clasify-classified-listing' ), CLASIFY_CLASSIFIED_PLUGIN_TITLE, '5.6' );
	$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
	echo wp_kses_post( $html_message );
}

/**
 * clasify_classified_plugin_fail_wp_version admin notice for minimum WordPress version.
 *
 * Warning when the site doesn't have the minimum required WordPress version.
 *
 * @since 1.0.0
 *
 * @return void
 */
function clasify_classified_plugin_fail_wp_version() {
	/* translators: %s: WordPress version */
	$message      = sprintf( esc_html__( '%1$s requires WordPress version %2$s+. Because you are using an earlier version, the plugin is currently NOT RUNNING.', 'clasify-classified-listing' ), CLASIFY_CLASSIFIED_PLUGIN_TITLE, '5.2' );
	$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
	echo wp_kses_post( $html_message );
}

/**
 * clasify_classified_plugin_load_plugin_textdomain loads clasify-classified-listing textdomain.
 *
 * Load gettext translate for clasify-classified-listing text domain.
 *
 * @since 1.0.0
 *
 * @return void
 */
function clasify_classified_plugin_load_plugin_textdomain() {
	load_plugin_textdomain( 'clasify-classified-listing' );
}



function clasify_classified_plugin_set_listing_views_count( $postID ) {
	$count_key = 'listing_views_count';
	$count     = get_post_meta( $postID, $count_key, true );
	if ( $count == '' ) {
		delete_post_meta( $postID, $count_key );
		add_post_meta( $postID, $count_key, '1' );
	} else {
		$count++;
		update_post_meta( $postID, $count_key, $count );
	}
}

/*
 * track post views
 */
function clasify_classified_plugin_track_listing_views( $post_id ) {
	if ( is_single() ) {
		if ( empty( $post_id ) ) {
			global $post;
			$post_id = $post->ID;
			clasify_classified_plugin_set_listing_views_count( $post_id );
		}
	}
}
add_action( 'wp_head', 'clasify_classified_plugin_track_listing_views' );



function clasify_classified_plugin_kmb( $count, $precision = 2 ) {
	if ( $count < 1000 ) {
		$n_format = $count;
	} elseif ( $count < 1000000 ) {
		// Anything less than a million
		$n_format = number_format( $count / 1000 ) . 'K';
	} elseif ( $count < 1000000000 ) {
		// Anything less than a billion
		$n_format = number_format( $count / 1000000, $precision ) . 'M';
	} else {
		// At least a billion
		$n_format = number_format( $count / 1000000000, $precision ) . 'B';
	}
	return $n_format;
}
