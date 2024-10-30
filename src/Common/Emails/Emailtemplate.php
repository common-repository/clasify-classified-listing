<?php
namespace Clasify\Classified\Common\Emails;

use Clasify\Classified\Traitval\Traitval;
use Clasify\Classified\Front\Models\Listings;

class Emailtemplate {

	use Traitval;

	public function __construct() {
		add_action( 'cl_view_receipt', array( $this, 'cl_render_receipt_in_browser' ) );
		add_action( 'template_redirect', array( $this, 'cl_display_email_template_preview' ) );
		add_action( 'cl_purchase_receipt_email_settings', array( $this, 'cl_email_template_preview' ) );
	}


	public function cl_get_email_templates() {
		$templates = new Emails();
		return $templates->get_templates();
	}

	/**
	 * Email Template Tags
	 *
	 * @since 1.0
	 *
	 * @param string $message Message with the template tags
	 * @param array  $payment_data Payment Data
	 * @param int    $payment_id Payment ID
	 * @param bool   $admin_notice Whether or not this is a notification email
	 *
	 * @return string $message Fully formatted message
	 */
	public function cl_email_template_tags( $message, $payment_data, $payment_id, $admin_notice = false ) {
		return CCP()->common->emailtags->cl_do_email_tags( $message, $payment_id );
	}

	/**
	 * Email Preview Template Tags
	 *
	 * @since 1.0
	 * @param string $message Email message with template tags
	 * @return string $message Fully formatted message
	 */
	public function cl_email_preview_template_tags( $message ) {
		$listing_list  = '<ul>';
		$listing_list .= '<li>' . __( 'Sample Product Title', 'clasify-classified-listing' ) . '<br />';
		$listing_list .= '<div>';
		$listing_list .= '<a href="#">' . __( 'Sample listing File Name', 'clasify-classified-listing' ) . '</a> - <small>' . __( 'Optional notes about this listing.', 'clasify-classified-listing' ) . '</small>';
		$listing_list .= '</div>';
		$listing_list .= '</li>';
		$listing_list .= '</ul>';

		$file_urls = esc_html( trailingslashit( get_site_url() ) . 'test.zip?test=key&key=123' );

		$price = CCP()->front->formatting->cl_currency_filter( CCP()->common->formatting->cl_format_amount( 10.50 ) );

		$gateway = CCP()->front->gateways->cl_get_gateway_admin_label( cl_get_default_gateway() );

		$receipt_id = strtolower( md5( uniqid() ) );

		$notes = __( 'These are some sample notes added to a product.', 'clasify-classified-listing' );

		$tax = CCP()->common->formatting->cl_currency_filter( CCP()->common->formatting->cl_format_amount( 1.00 ) );

		$sub_total = CCP()->common->formatting->cl_currency_filter( CCP()->common->formatting->cl_format_amount( 9.50 ) );

		$payment_id = rand( 1, 100 );

		$user = wp_get_current_user();

		$message = str_replace( '{listing_list}', $listing_list, $message );
		$message = str_replace( '{file_urls}', $file_urls, $message );
		$message = str_replace( '{name}', $user->display_name, $message );
		$message = str_replace( '{fullname}', $user->display_name, $message );
		$message = str_replace( '{username}', $user->user_login, $message );
		$message = str_replace( '{date}', date( get_option( 'date_format' ), current_time( 'timestamp' ) ), $message );
		$message = str_replace( '{subtotal}', $sub_total, $message );
		$message = str_replace( '{tax}', $tax, $message );
		$message = str_replace( '{price}', $price, $message );
		$message = str_replace( '{receipt_id}', $receipt_id, $message );
		$message = str_replace( '{payment_method}', $gateway, $message );
		$message = str_replace( '{sitename}', get_bloginfo( 'name' ), $message );
		$message = str_replace( '{product_notes}', $notes, $message );
		$message = str_replace( '{payment_id}', $payment_id, $message );
		$message = str_replace( '{receipt_link}', cl_email_tag_receipt_link( $payment_id ), $message );

		$message = apply_filters( 'cl_email_preview_template_tags', $message );

		return apply_filters( 'cl_email_template_wpautop', true ) ? wpautop( $message ) : $message;
	}

	/**
	 * Email Template Preview
	 *
	 * @access private
	 * @since 1.0.8.2
	 */
	public function cl_email_template_preview() {
		if ( ! current_user_can( 'manage_shop_settings' ) ) {
			return;
		}

		ob_start();
		?>
		<a href="<?php echo esc_url( add_query_arg( array( 'cl_action' => 'preview_email' ), home_url() ) ); ?>" class="button-secondary" target="_blank"><?php _e( 'Preview Purchase Receipt', 'clasify-classified-listing' ); ?></a>
		<a href="<?php echo wp_nonce_url( add_query_arg( array( 'cl_action' => 'send_test_email' ) ), 'cl-test-email' ); ?>" class="button-secondary"><?php _e( 'Send Test Email', 'clasify-classified-listing' ); ?></a>
		<?php
		echo ob_get_clean();
	}


	/**
	 * Displays the email preview
	 *
	 * @since 2.1
	 * @return void
	 */
	public function cl_display_email_template_preview() {
		if ( empty( $_GET['cl_action'] ) ) {
			return;
		}

		if ( 'preview_email' !== $_GET['cl_action'] ) {
			return;
		}

		if ( ! current_user_can( 'manage_shop_settings' ) ) {
			return;
		}

		CCP()->common->emails->heading = cl_email_preview_template_tags( cl_admin_get_option( 'purchase_heading', __( 'Purchase Receipt', 'clasify-classified-listing' ) ) );

		echo CCP()->common->emails->build_email( cl_email_preview_template_tags( $this->cl_get_email_body_content( 0, array() ) ) );

		exit;
	}


	/**
	 * Email Template Body
	 *
	 * @since 1.0.8.2
	 * @param int   $payment_id Payment ID
	 * @param array $payment_data Payment Data
	 * @return string $email_body Body of the email
	 */
	public function cl_get_email_body_content( $payment_id = 0, $payment_data = array() ) {
		$default_email_body  = __( 'Dear', 'clasify-classified-listing' ) . " {name},\n\n";
		$default_email_body .= __( 'Thank you for your purchase. Please click on the link(s) below to listing your files.', 'clasify-classified-listing' ) . "\n\n";
		$default_email_body .= "{listing_list}\n\n";
		$default_email_body .= '{sitename}';

		$email      = cl_admin_get_option( 'purchase_receipt', false );
		$email      = $email ? stripslashes( $email ) : $default_email_body;
		$email_body = apply_filters( 'cl_email_template_wpautop', true ) ? wpautop( $email ) : $email;

		$email_body = apply_filters( 'cl_purchase_receipt_' . CCP()->common->emails->get_template(), $email_body, $payment_id, $payment_data );

		return apply_filters( 'cl_purchase_receipt', $email_body, $payment_id, $payment_data );
	}

	/**
	 * Sale Notification Template Body
	 *
	 * @since 1.7
	 * @author Daniel J Griffiths
	 * @param int   $payment_id Payment ID
	 * @param array $payment_data Payment Data
	 * @return string $email_body Body of the email
	 */
	public function cl_get_sale_notification_body_content( $payment_id = 0, $payment_data = array() ) {
		$payment = cl_get_payment( $payment_id );

		if ( $payment->user_id > 0 ) {
			$user_data = get_userdata( $payment->user_id );
			$name      = $user_data->display_name;
		} elseif ( ! empty( $payment->first_name ) && ! empty( $payment->last_name ) ) {
			$name = $payment->first_name . ' ' . $payment->last_name;
		} else {
			$name = $payment->email;
		}

		$listing_list = '';

		if ( is_array( $payment->listing ) ) {
			foreach ( $payment->listing as $item ) {
				$listing = new Listings( $item['id'] );
				$title   = $listing->get_name();
				if ( isset( $item['options'] ) ) {
					if ( isset( $item['options']['price_id'] ) ) {
						$title .= ' - ' . CCP()->front->listingsaction->cl_get_price_option_name( $item['id'], $item['options']['price_id'], $payment_id );
					}
				}
				$listing_list .= html_entity_decode( $title, ENT_COMPAT, 'UTF-8' ) . "\n";
			}
		}

		$gateway = CCP()->front->gateways->cl_get_gateway_admin_label( $payment->gateway );

		$default_email_body  = __( 'Hello', 'clasify-classified-listing' ) . "\n\n" . sprintf( __( 'A %s purchase has been made', 'clasify-classified-listing' ), cl_get_label_plural() ) . ".\n\n";
		$default_email_body .= sprintf( __( '%s sold:', 'clasify-classified-listing' ), cl_get_label_plural() ) . "\n\n";
		$default_email_body .= $listing_list . "\n\n";
		$default_email_body .= __( 'Purchased by: ', 'clasify-classified-listing' ) . ' ' . html_entity_decode( $name, ENT_COMPAT, 'UTF-8' ) . "\n";
		$default_email_body .= __( 'Amount: ', 'clasify-classified-listing' ) . ' ' . html_entity_decode( CCP()->common->formatting->cl_currency_filter( CCP()->common->formatting->cl_format_amount( $payment->total ) ), ENT_COMPAT, 'UTF-8' ) . "\n";
		$default_email_body .= __( 'Payment Method: ', 'clasify-classified-listing' ) . ' ' . esc_html( $gateway ) . "\n\n";
		$default_email_body .= __( 'Thank you', 'clasify-classified-listing' );

		$message    = cl_admin_get_option( 'sale_notification', false );
		$message    = $message ? stripslashes( $message ) : $default_email_body;
		$email      = cl_admin_get_option( 'purchase_receipt', false );
		$email      = $email ? stripslashes( $email ) : $default_email_body;
		$email_body = $this->cl_email_template_tags( $email, $payment_data, $payment_id, true );
		$email_body = CCP()->common->emailtags->cl_do_email_tags( $message, $payment_id );

		$email_body = apply_filters( 'cl_email_template_wpautop', true ) ? wpautop( $email_body ) : $email_body;

		return apply_filters( 'cl_sale_notification', $email_body, $payment_id, $payment_data );
	}

	/**
	 * Render Receipt in the Browser
	 *
	 * A link is added to the Purchase Receipt to view the email in the browser and
	 * this function renders the Purchase Receipt in the browser. It overrides the
	 * Purchase Receipt template and provides its only styling.
	 *
	 * @since 1.5
	 * @author Sunny Ratilal
	 */
	public function cl_render_receipt_in_browser() {
		if ( ! isset( $_GET['payment_key'] ) ) {
			wp_die( __( 'Missing purchase key.', 'clasify-classified-listing' ), __( 'Error', 'clasify-classified-listing' ) );
		}

		$key = urlencode( cl_sanitization( $_GET['payment_key'] ) );

		ob_start();
		// Disallows caching of the page
		header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
		header( 'Cache-Control: no-store, no-cache, must-revalidate' ); // HTTP/1.1
		header( 'Cache-Control: post-check=0, pre-check=0', false );
		header( 'Pragma: no-cache' ); // HTTP/1.0
		header( 'Expires: Sat, 23 Oct 1977 05:00:00 PST' ); // Date in the past
		?>
		<!DOCTYPE html>
		<html lang="en">

		<head>
			<title><?php _e( 'Receipt', 'clasify-classified-listing' ); ?></title>
			<meta charset="utf-8" />
			<meta name="robots" content="noindex, nofollow" />
			<?php wp_head(); ?>
		</head>

		<body class="<?php echo apply_filters( 'cl_receipt_page_body_class', 'cl_receipt_page' ); ?>">
			<div id="cl_receipt_wrapper">
				<?php do_action( 'cl_render_receipt_in_browser_before' ); ?>
				<?php echo do_shortcode( '[cl_receipt payment_key=' . $key . ']' ); ?>
				<?php do_action( 'cl_render_receipt_in_browser_after' ); ?>
			</div>
			<?php wp_footer(); ?>
		</body>

		</html>
		<?php
		echo ob_get_clean();
		die();
	}
}
