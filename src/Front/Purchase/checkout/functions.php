<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Determines if we're currently on the Checkout page
 *
 * @since 1.1.2
 * @return bool True if on the Checkout page, false otherwise
 */
function cl_is_checkout() {
	global $wp_query;

	$is_object_set    = isset( $wp_query->queried_object );
	$is_object_id_set = isset( $wp_query->queried_object_id );
	$is_checkout      = is_page( cl_admin_get_option( 'purchase_page' ) );

	if ( ! $is_object_set ) {
		unset( $wp_query->queried_object );
	} elseif ( is_singular() ) {
		$content = $wp_query->queried_object->post_content;
	}

	if ( ! $is_object_id_set ) {
		unset( $wp_query->queried_object_id );
	}

	// If we know this isn't the primary checkout page, check other methods.
	if ( ! $is_checkout && isset( $content ) && has_shortcode( $content, 'listing_checkout' ) ) {
		$is_checkout = true;
	}

	return apply_filters( 'cl_is_checkout', $is_checkout );
}

/**
 * Determines if a user can checkout or not
 *
 * @since 1.3.3
 * @return bool Can user checkout?
 */
function cl_can_checkout() {
	$can_checkout = true; // Always true for now

	return (bool) apply_filters( 'cl_can_checkout', $can_checkout );
}

/**
 * Determines if we're currently on the Success page.
 *
 * @since 1.9.9
 * @return bool True if on the Success page, false otherwise.
 */
function cl_is_success_page() {
	$is_success_page = cl_admin_get_option( 'success_page', false );
	$is_success_page = isset( $is_success_page ) ? is_page( $is_success_page ) : false;

	return apply_filters( 'cl_is_success_page', $is_success_page );
}

/**
 * Send To Success Page
 *
 * Sends the user to the succes page.
 *
 * @param string $query_string
 * @since       1.0
 * @return      void
 */
function cl_send_to_success_page( $query_string = null ) {
	$redirect = cl_get_success_page_uri();

	if ( $query_string ) {
		$redirect .= $query_string;
	}

	$gateway = isset( $_REQUEST['cl-gateway'] ) ? cl_sanitization( $_REQUEST['cl-gateway'] ) : '';

	wp_redirect( apply_filters( 'cl_success_page_redirect', $redirect, $gateway, $query_string ) );
	wp_die();
}

/**
 * Get the URL of the Checkout page
 *
 * @since 1.0.8
 * @param array $args Extra query args to add to the URI
 * @return mixed Full URL to the checkout page, if present | null if it doesn't exist
 */
function cl_get_checkout_uri( $args = array() ) {
	$uri = false;

	if ( cl_is_checkout() ) {
		global $post;
		$uri = $post instanceof WP_Post ? get_permalink( $post->ID ) : null;
	}

	// If we are not on a checkout page, determine the URI from the default.
	if ( empty( $uri ) ) {
		$uri = cl_admin_get_option( 'purchase_page', false );
		$uri = isset( $uri ) ? get_permalink( $uri ) : null;
	}

	if ( ! empty( $args ) ) {
		// Check for backward compatibility
		if ( is_string( $args ) ) {
			$args = str_replace( '?', '', $args );
		}

		$args = wp_parse_args( $args );

		$uri = add_query_arg( $args, $uri );
	}

	$scheme = defined( 'FORCE_SSL_ADMIN' ) && FORCE_SSL_ADMIN ? 'https' : 'admin';

	$ajax_url = admin_url( 'admin-ajax.php', $scheme );

	if ( ( ! preg_match( '/^https/', $uri ) && preg_match( '/^https/', $ajax_url ) && cl_is_ajax_enabled() ) || cl_is_ssl_enforced() ) {
		$uri = preg_replace( '/^http:/', 'https:', $uri );
	}

	if ( cl_admin_get_option( 'no_cache_checkout', false ) ) {
		$uri = cl_add_cache_busting( $uri );
	}

	return apply_filters( 'cl_get_checkout_uri', $uri );
}

/**
 * Send back to checkout.
 *
 * Used to redirect a user back to the purchase
 * page if there are errors present.
 *
 * @param array $args
 * @since  1.0
 * @return Void
 */
function cl_send_back_to_checkout( $args = array() ) {
	$redirect = cl_get_checkout_uri();

	if ( ! empty( $args ) ) {
		// Check for backward compatibility
		if ( is_string( $args ) ) {
			$args = str_replace( '?', '', $args );
		}

		$args = wp_parse_args( $args );

		$redirect = add_query_arg( $args, $redirect );
	}

	wp_redirect( apply_filters( 'cl_send_back_to_checkout', $redirect, $args ) );
	wp_die();
}

/**
 * Get the URL of the Transaction Failed page
 *
 * @since 1.3.4
 * @param bool $extras Extras to append to the URL
 * @return mixed|void Full URL to the Transaction Failed page, if present, home page if it doesn't exist
 */
function cl_get_failed_transaction_uri( $extras = false ) {
	$uri = cl_admin_get_option( 'failure_page', '' );
	$uri = ! empty( $uri ) ? trailingslashit( get_permalink( $uri ) ) : home_url();

	if ( $extras ) {
		$uri .= $extras;
	}

	return apply_filters( 'cl_get_failed_transaction_uri', $uri );
}

/**
 * Determines if we're currently on the Failed Transaction page.
 *
 * @since 2.1
 * @return bool True if on the Failed Transaction page, false otherwise.
 */
function cl_is_failed_transaction_page() {
	$ret = cl_admin_get_option( 'failure_page', false );
	$ret = isset( $ret ) ? is_page( $ret ) : false;

	return apply_filters( 'cl_is_failure_page', $ret );
}

/**
 * Mark payments as Failed when returning to the Failed Transaction page
 *
 * @since       1.9.9
 * @return      void
 */
function cl_listen_for_failed_payments() {

	$failed_page = cl_admin_get_option( 'failure_page', 0 );

	if ( ! empty( $failed_page ) && is_page( $failed_page ) && ! empty( $_GET['payment-id'] ) ) {

		$payment_id = absint( cl_sanitization( $_GET['payment-id'] ) );
		$payment    = get_post( $payment_id );
		$status     = cl_get_payment_status( $payment );

		if ( $status && 'pending' === strtolower( $status ) ) {

			cl_update_payment_status( $payment_id, 'failed' );

		}
	}

}
add_action( 'template_redirect', 'cl_listen_for_failed_payments' );

/**
 * Check if a field is required
 *
 * @param string $field
 * @since       1.7
 * @return      bool
 */
function cl_field_is_required( $field = '' ) {
	$required_fields = CCP()->front->gateways->cl_purchase_form_required_fields();
	return array_key_exists( $field, $required_fields );
}

/**
 * Retrieve an array of banned_emails
 *
 * @since       2.0
 * @return      array
 */
function cl_get_banned_emails() {
	$emails = array_map( 'trim', cl_admin_get_option( 'banned_emails', array() ) );

	return apply_filters( 'cl_get_banned_emails', $emails );
}

/**
 * Determines if an email is banned
 *
 * @since       2.0
 * @param string $email Email to check if is banned.
 * @return bool
 */
function cl_is_email_banned( $email = '' ) {

	$email = trim( $email );
	if ( empty( $email ) ) {
		return false;
	}

	$email         = strtolower( $email );
	$banned_emails = cl_get_banned_emails();

	if ( ! is_array( $banned_emails ) || empty( $banned_emails ) ) {
		return false;
	}

	$return = false;
	foreach ( $banned_emails as $banned_email ) {

		$banned_email = strtolower( $banned_email );

		if ( is_email( $banned_email ) ) {

			// Complete email address
			$return = ( $banned_email == $email ? true : false );

		} elseif ( strpos( $banned_email, '.' ) === 0 ) {

			// TLD block
			$return = ( substr( $email, ( strlen( $banned_email ) * -1 ) ) == $banned_email ) ? true : false;

		} else {

			// Domain block
			$return = ( stristr( $email, $banned_email ) ? true : false );

		}

		if ( true === $return ) {
			break;
		}
	}

	return apply_filters( 'cl_is_email_banned', $return, $email );
}

/**
 * Determines if secure checkout pages are enforced
 *
 * @since       2.0
 * @return      bool True if enforce SSL is enabled, false otherwise
 */
function cl_is_ssl_enforced() {
	$ssl_enforced = cl_admin_get_option( 'enforce_ssl', false );
	return (bool) apply_filters( 'cl_is_ssl_enforced', $ssl_enforced );
}

/**
 * Handle redirections for SSL enforced checkouts
 *
 * @since 2.0
 * @return void
 */
function cl_enforced_ssl_redirect_handler() {

	if ( ! cl_is_ssl_enforced() || ! cl_is_checkout() || is_admin() || is_ssl() ) {
		return;
	}

	if ( cl_is_checkout() && false !== strpos( cl_get_current_page_url(), 'https://' ) ) {
		return;
	}

	$uri = 'https://' . cl_sanitization( $_SERVER['HTTP_HOST'] ) . cl_sanitization( $_SERVER['REQUEST_URI'] );

	wp_safe_redirect( $uri );
	exit;
}
add_action( 'template_redirect', 'cl_enforced_ssl_redirect_handler' );

/**
 * Handle rewriting asset URLs for SSL enforced checkouts
 *
 * @since 2.0
 * @return void
 */
function cl_enforced_ssl_asset_handler() {
	if ( ! cl_is_ssl_enforced() || ! cl_is_checkout() || is_admin() ) {
		return;
	}

	$filters = array(
		'post_thumbnail_html',
		'wp_get_attachment_url',
		'wp_get_attachment_image_attributes',
		'wp_get_attachment_url',
		'option_stylesheet_url',
		'option_template_url',
		'script_loader_src',
		'style_loader_src',
		'template_directory_uri',
		'stylesheet_directory_uri',
		'site_url',
	);

	$filters = apply_filters( 'cl_enforced_ssl_asset_filters', $filters );

	foreach ( $filters as $filter ) {
		add_filter( $filter, 'cl_enforced_ssl_asset_filter', 1 );
	}
}
add_action( 'template_redirect', 'cl_enforced_ssl_asset_handler' );

/**
 * Filter filters and convert http to https
 *
 * @since 2.0
 * @param mixed $content
 * @return mixed
 */
function cl_enforced_ssl_asset_filter( $content ) {

	if ( is_array( $content ) ) {

		$content = array_map( 'cl_enforced_ssl_asset_filter', $content );

	} else {

		// Detect if URL ends in a common domain suffix. We want to only affect assets
		$extension = untrailingslashit( cl_get_file_extension( $content ) );
		$suffixes  = array(
			'br',
			'ca',
			'cn',
			'com',
			'de',
			'dev',
			'edu',
			'fr',
			'in',
			'info',
			'jp',
			'local',
			'mobi',
			'name',
			'net',
			'nz',
			'org',
			'ru',
		);

		if ( ! in_array( $extension, $suffixes ) ) {

			$content = str_replace( 'http:', 'https:', $content );

		}
	}

	return $content;
}

/**
 * Given a number and algorithem, determine if we have a valid credit card format
 *
 * @since  2.4
 * @param  integer $number The Credit Card Number to validate
 * @return bool            If the card number provided matches a specific format of a valid card
 */
function cl_validate_card_number_format( $number = 0 ) {

	$number = trim( $number );
	if ( empty( $number ) ) {
		return false;
	}

	if ( ! is_numeric( $number ) ) {
		return false;
	}

	$is_valid_format = false;

	// First check if it passes with the passed method, Luhn by default
	$is_valid_format = cl_validate_card_number_format_luhn( $number );

	// Run additional checks before we start the regexing and looping by type
	$is_valid_format = apply_filters( 'cl_valiate_card_format_pre_type', $is_valid_format, $number );

	if ( true === $is_valid_format ) {
		// We've passed our method check, onto card specific checks
		$card_type       = cl_detect_cc_type( $number );
		$is_valid_format = ! empty( $card_type ) ? true : false;
	}

	return apply_filters( 'cl_cc_is_valid_format', $is_valid_format, $number );
}

/**
 * Validate credit card number based on the luhn algorithm
 *
 * @since  2.4
 * @param string $number
 * @return bool
 */
function cl_validate_card_number_format_luhn( $number ) {

	// Strip any non-digits (useful for credit card numbers with spaces and hyphens)
	$number = preg_replace( '/\D/', '', $number );

	// Set the string length and parity
	$length = strlen( $number );
	$parity = $length % 2;

	// Loop through each digit and do the math
	$total = 0;
	for ( $i = 0; $i < $length; $i++ ) {
		$digit = $number[ $i ];

		// Multiply alternate digits by two
		if ( $i % 2 == $parity ) {
			$digit *= 2;

			// If the sum is two digits, add them together (in effect)
			if ( $digit > 9 ) {
				$digit -= 9;
			}
		}

		// Total up the digits
		$total += $digit;
	}

	// If the total mod 10 equals 0, the number is valid
	return ( $total % 10 == 0 ) ? true : false;

}

/**
 * Detect credit card type based on the number and return an
 * array of data to validate the credit card number
 *
 * @since  2.4
 * @param string $number
 * @return string|bool
 */
function cl_detect_cc_type( $number ) {

	$return = false;

	$card_types = array(
		array(
			'name'         => 'amex',
			'pattern'      => '/^3[4|7]/',
			'valid_length' => array( 15 ),
		),
		array(
			'name'         => 'diners_club_carte_blanche',
			'pattern'      => '/^30[0-5]/',
			'valid_length' => array( 14 ),
		),
		array(
			'name'         => 'diners_club_international',
			'pattern'      => '/^36/',
			'valid_length' => array( 14 ),
		),
		array(
			'name'         => 'jcb',
			'pattern'      => '/^35(2[89]|[3-8][0-9])/',
			'valid_length' => array( 16 ),
		),
		array(
			'name'         => 'laser',
			'pattern'      => '/^(6304|670[69]|6771)/',
			'valid_length' => array( 16, 17, 18, 19 ),
		),
		array(
			'name'         => 'visa_electron',
			'pattern'      => '/^(4026|417500|4508|4844|491(3|7))/',
			'valid_length' => array( 16 ),
		),
		array(
			'name'         => 'visa',
			'pattern'      => '/^4/',
			'valid_length' => array( 16 ),
		),
		array(
			'name'         => 'mastercard',
			'pattern'      => '/^5[1-5]/',
			'valid_length' => array( 16 ),
		),
		array(
			'name'         => 'maestro',
			'pattern'      => '/^(5018|5020|5038|6304|6759|676[1-3])/',
			'valid_length' => array( 12, 13, 14, 15, 16, 17, 18, 19 ),
		),
		array(
			'name'         => 'discover',
			'pattern'      => '/^(6011|622(12[6-9]|1[3-9][0-9]|[2-8][0-9]{2}|9[0-1][0-9]|92[0-5]|64[4-9])|65)/',
			'valid_length' => array( 16 ),
		),
	);

	$card_types = apply_filters( 'cl_cc_card_types', $card_types );

	if ( ! is_array( $card_types ) ) {
		return false;
	}

	foreach ( $card_types as $card_type ) {

		if ( preg_match( $card_type['pattern'], $number ) ) {

			$number_length = strlen( $number );
			if ( in_array( $number_length, $card_type['valid_length'] ) ) {
				$return = $card_type['name'];
				break;
			}
		}
	}

	return apply_filters( 'cl_cc_found_card_type', $return, $number, $card_types );
}

/**
 * Validate credit card expiration date
 *
 * @since  2.4
 * @param string $exp_month
 * @param string $exp_year
 * @return bool
 */
function cl_purchase_form_validate_cc_exp_date( $exp_month, $exp_year ) {

	$month_name = date( 'M', mktime( 0, 0, 0, $exp_month, 10 ) );
	$expiration = strtotime( date( 't', strtotime( $month_name . ' ' . $exp_year ) ) . ' ' . $month_name . ' ' . $exp_year . ' 11:59:59PM' );

	return $expiration >= time();

}
