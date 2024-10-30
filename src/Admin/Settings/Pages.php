<?php
namespace Clasify\Classified\Admin\Settings;

use Clasify\Classified\Traitval\Traitval;


/**
 * The admin class
 */
class Pages {

	use Traitval;

	public function __construct() {     }

	public static function cl_get_pages( $force = false ) {

		$pages = get_pages();
		$pages_return = array();
		if ( $pages ) {
			foreach ( $pages as $page ) {
				$pages_return[ $page->ID ] = $page->post_title;
			}
		}
		return $pages_return;
	}


	public static function get_setting() {
		return array(

			'page_settings'         => array(
				'id'            => 'page_settings',
				'name'          => '<h3>' . __( 'Pages', 'clasify-classified-listing' ) . '</h3>',
				'desc'          => '',
				'type'          => 'header',
				'tooltip_title' => __( 'Page Settings', 'clasify-classified-listing' ),
				'tooltip_desc'  => __( 'Clasify Classified Plugin uses the pages below for handling the display of checkout, purchase confirmation, purchase history, and purchase failures. If pages are deleted or removed in some way, they can be recreated manually from the Pages menu. When re-creating the pages, enter the shortcode shown in the page content area.', 'clasify-classified-listing' ),
			),
			'purchase_page'         => array(
				'id'          => 'purchase_page',
				'name'        => __( 'Primary Checkout Page', 'clasify-classified-listing' ),
				'desc'        => __( 'This is the checkout page where buyers will complete their purchases. The [listing_checkout] shortcode must be on this page.', 'clasify-classified-listing' ),
				'type'        => 'select',
				'options'     => self::cl_get_pages(),
				'chosen'      => true,
				'placeholder' => __( 'Select a page', 'clasify-classified-listing' ),
			),
			'success_page'          => array(
				'id'          => 'success_page',
				'name'        => __( 'Success Page', 'clasify-classified-listing' ),
				'desc'        => __( 'This is the page buyers are sent to after completing their purchases. The [cl_receipt] shortcode should be on this page.', 'clasify-classified-listing' ),
				'type'        => 'select',
				'options'     => self::cl_get_pages(),
				'chosen'      => true,
				'placeholder' => __( 'Select a page', 'clasify-classified-listing' ),
			),
			'failure_page'          => array(
				'id'          => 'failure_page',
				'name'        => __( 'Failed Transaction Page', 'clasify-classified-listing' ),
				'desc'        => __( 'This is the page buyers are sent to if their transaction fails.', 'clasify-classified-listing' ),
				'type'        => 'select',
				'options'     => self::cl_get_pages(),
				'chosen'      => true,
				'placeholder' => __( 'Select a page', 'clasify-classified-listing' ),
			),
			'cancel_payment_page'   => array(
				'id'          => 'cancel_payment_page',
				'name'        => __( 'Cancel Payment Page', 'clasify-classified-listing' ),
				'desc'        => __( 'This is the page buyers are sent to if their transaction is cancelled.', 'clasify-classified-listing' ),
				'type'        => 'select',
				'options'     => self::cl_get_pages(),
				'chosen'      => true,
				'placeholder' => __( 'Select a page', 'clasify-classified-listing' ),
			),
			'purchase_history_page' => array(
				'id'          => 'purchase_history_page',
				'name'        => __( 'Purchase History Page', 'clasify-classified-listing' ),
				'desc'        => __( 'This page shows a complete purchase history for the current user, including listing links. The [purchase_history] shortcode should be on this page.', 'clasify-classified-listing' ),
				'type'        => 'select',
				'options'     => self::cl_get_pages(),
				'chosen'      => true,
				'placeholder' => __( 'Select a page', 'clasify-classified-listing' ),
			),
			'register_user_page'    => array(
				'id'          => 'register_user_page',
				'name'        => __( 'Register User Page', 'clasify-classified-listing' ),
				'desc'        => sprintf(
					__( 'If a customer sign up using the [cl_register_user] shortcode, this is the page they will be redirected to. Note, this can be overridden using the redirect attribute in the shortcode like this: [cl_admin_login redirect="%s"].', 'clasify-classified-listing' ),
					trailingslashit( home_url() )
				),
				'type'        => 'select',
				'options'     => self::cl_get_pages(),
				'chosen'      => true,
				'placeholder' => __( 'Select a page', 'clasify-classified-listing' ),
			),
			'update_user_page'      => array(
				'id'          => 'update_user_page',
				'name'        => __( 'User Profile', 'clasify-classified-listing' ),
				'desc'        => sprintf(
					__( 'If a customer sign up using the [cl_update_user] shortcode, this is the page they will be redirected to. Note, this can be overridden using the redirect attribute in the shortcode like this: [cl_admin_login redirect="%s"].', 'clasify-classified-listing' ),
					trailingslashit( home_url() )
				),
				'type'        => 'select',
				'options'     => self::cl_get_pages(),
				'chosen'      => true,
				'placeholder' => __( 'Select a page', 'clasify-classified-listing' ),
			),
			'login_redirect_page'   => array(
				'id'          => 'login_redirect_page',
				'name'        => __( 'Login Page', 'clasify-classified-listing' ),
				'desc'        => sprintf(
					__( 'If a customer logs in using the [cl_admin_login] shortcode, this is the page they will be redirected to. Note, this can be overridden using the redirect attribute in the shortcode like this: [cl_admin_login redirect="%s"].', 'clasify-classified-listing' ),
					trailingslashit( home_url() )
				),
				'type'        => 'select',
				'options'     => self::cl_get_pages(),
				'chosen'      => true,
				'placeholder' => __( 'Select a page', 'clasify-classified-listing' ),
			),
			'compare_page'          => array(
				'id'          => 'compare_page',
				'name'        => __( 'Compare Page', 'clasify-classified-listing' ),
				'desc'        => sprintf( __( 'The is main compare page. The [cl_compare_listing] shortcode should be on this page.', 'clasify-classified-listing' ), trailingslashit( home_url() ) ),
				'type'        => 'select',
				'options'     => self::cl_get_pages(),
				'chosen'      => true,
				'placeholder' => __( 'Select a page', 'clasify-classified-listing' ),
			),
			'dashboard_page'        => array(
				'id'          => 'dashboard_page',
				'name'        => __( 'Listing Dashboard Page', 'clasify-classified-listing' ),
				'desc'        => sprintf( __( 'The is main listing dashboard page. The [cl_dashboard] shortcode should be on this page.', 'clasify-classified-listing' ), trailingslashit( home_url() ) ),
				'type'        => 'select',
				'options'     => self::cl_get_pages(),
				'chosen'      => true,
				'placeholder' => __( 'Select a page', 'clasify-classified-listing' ),
			),
			'cl_add_listing'        => array(
				'id'          => 'cl_add_listing',
				'name'        => __( 'Add Listing Page', 'clasify-classified-listing' ),
				'desc'        => sprintf( __( 'The is main add listing page. The [cl_add_listing] shortcode should be on this page.', 'clasify-classified-listing' ), trailingslashit( home_url() ) ),
				'type'        => 'select',
				'options'     => self::cl_get_pages(),
				'chosen'      => true,
				'placeholder' => __( 'Select a page', 'clasify-classified-listing' ),
			),
			'cl_edit_listing'       => array(
				'id'          => 'cl_edit_listing',
				'name'        => __( 'Edit Listing Page', 'clasify-classified-listing' ),
				'desc'        => sprintf( __( 'The is main edit listing page. The [cl_edit_listing] shortcode should be on this page.', 'clasify-classified-listing' ), trailingslashit( home_url() ) ),
				'type'        => 'select',
				'options'     => self::cl_get_pages(),
				'chosen'      => true,
				'placeholder' => __( 'Select a page', 'clasify-classified-listing' ),
			),
			'checkout_page'         => array(
				'id'          => 'checkout_page',
				'name'        => __( 'Checkout Page', 'clasify-classified-listing' ),
				'desc'        => sprintf( __( 'The is main Checkout Page. The [cl_compare_listing] shortcode should be on this page.', 'clasify-classified-listing' ), trailingslashit( home_url() ) ),
				'type'        => 'select',
				'options'     => self::cl_get_pages(),
				'chosen'      => true,
				'placeholder' => __( 'Select a page', 'clasify-classified-listing' ),
			),
			'cart_page'             => array(
				'id'          => 'cart_page',
				'name'        => __( 'Cart Page', 'clasify-classified-listing' ),
				'desc'        => sprintf( __( 'The is main Cart Page. The [cl_compare_listing] shortcode should be on this page.', 'clasify-classified-listing' ), trailingslashit( home_url() ) ),
				'type'        => 'select',
				'options'     => self::cl_get_pages(),
				'chosen'      => true,
				'placeholder' => __( 'Select a page', 'clasify-classified-listing' ),
			),
		);
	}
}
