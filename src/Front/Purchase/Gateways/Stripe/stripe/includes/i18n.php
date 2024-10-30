<?php
/**
 * Internationalization
 *
 * @package CL_Stripe
 * @copyright Copyright (c) 2020, Sandhills Development, LLC
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since 2.8.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns a list of error codes and corresponding localized error messages.
 *
 * @since 2.8.0
 *
 * @return array $error_list List of error codes and corresponding error messages.
 */
function cls_get_localized_error_messages() {
	$error_list = array(
		'invalid_number'           => __( 'The card number is not a valid credit card number.', 'clasify-classified-listing' ),
		'invalid_expiry_month'     => __( 'The card\'s expiration month is invalid.', 'clasify-classified-listing' ),
		'invalid_expiry_year'      => __( 'The card\'s expiration year is invalid.', 'clasify-classified-listing' ),
		'invalid_cvc'              => __( 'The card\'s security code is invalid.', 'clasify-classified-listing' ),
		'incorrect_number'         => __( 'The card number is incorrect.', 'clasify-classified-listing' ),
		'incomplete_number'        => __( 'The card number is incomplete.', 'clasify-classified-listing' ),
		'incomplete_cvc'           => __( 'The card\'s security code is incomplete.', 'clasify-classified-listing' ),
		'incomplete_expiry'        => __( 'The card\'s expiration date is incomplete.', 'clasify-classified-listing' ),
		'expired_card'             => __( 'The card has expired.', 'clasify-classified-listing' ),
		'incorrect_cvc'            => __( 'The card\'s security code is incorrect.', 'clasify-classified-listing' ),
		'incorrect_zip'            => __( 'The card\'s zip code failed validation.', 'clasify-classified-listing' ),
		'invalid_expiry_year_past' => __( 'The card\'s expiration year is in the past', 'clasify-classified-listing' ),
		'card_declined'            => __( 'The card was declined.', 'clasify-classified-listing' ),
		'processing_error'         => __( 'An error occurred while processing the card.', 'clasify-classified-listing' ),
		'invalid_request_error'    => __( 'Unable to process this payment, please try again or use alternative method.', 'clasify-classified-listing' ),
		'email_invalid'            => __( 'Invalid email address, please correct and try again.', 'clasify-classified-listing' ),
	);

	/**
	 * Filters the list of available error codes and corresponding error messages.
	 *
	 * @since 2.8.0
	 *
	 * @param array $error_list List of error codes and corresponding error messages.
	 */
	$error_list = apply_filters( 'cls_get_localized_error_list', $error_list );

	return $error_list;
}

/**
 * Returns a localized error message for a corresponding Stripe
 * error code.
 *
 * @link https://stripe.com/docs/error-codes
 *
 * @since 2.8.0
 *
 * @param string $error_code Error code.
 * @param string $error_message Original error message to return if a localized version does not exist.
 * @return string $error_message Potentially localized error message.
 */
function cls_get_localized_error_message( $error_code, $error_message ) {
	$error_list = cls_get_localized_error_messages();

	if ( ! empty( $error_list[ $error_code ] ) ) {
		return $error_list[ $error_code ];
	}

	return $error_message;
}
