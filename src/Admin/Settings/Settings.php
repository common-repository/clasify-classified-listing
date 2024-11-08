<?php
namespace Clasify\Classified\Admin\Settings;

use Clasify\Classified\Traitval\Traitval;

use Clasify\Classified\Common\Currencies\Currencies;
use Clasify\Classified\Front\Purchase\Gateways\PaypalStandard\PaypalStandard;
use Clasify\Classified\Front\Purchase\Gateways\Stripe\Stripe;
use Clasify\Classified\Front\Purchase\Gateways\Woocommerce\Woocommerce;
use Clasify\Classified\Admin\Settings\Pages;


class Settings {

	use Traitval;

	public $tabList;
	public $subtablist;
	public $settinglist;
	public $activeTab = 'genaral';

	public function __construct() {
		add_action( 'admin_init', array( $this, 'cl_process_actions' ) );
		add_filter( 'pre_update_option_cl_admin_settings', array( $this, 'pre_update_option_function' ), 10, 3 );
	}

	public function initialize() {
		$settingstabs     = $this->setSubTabList();
		$mainsettingstabs = $this->setTabList();
		$settingstabs     = empty( $settingstabs ) ? array() : $settingstabs;
		$activetab        = isset( $_GET['tab'] ) ? cl_sanitization( $_GET['tab'] ) : 'general';
		$activetab        = array_key_exists( $activetab, $settingstabs ) ? $activetab : 'general';
		$sections         = $this->cl_admin_get_settings_tab_sections( $activetab );
		$key              = 'main';
		if ( ! empty( $sections ) ) {
			$key = key( $sections );
		}
		$section = isset( $_GET['section'] ) && $this->cl_settings_tab_exist( $settingstabs, $_GET['section'], $settingstabs ) ? $_GET['section'] : $key;

		if ( file_exists( CLASIFY_CLASSIFIED_PLUGIN_PATH . '/views/settings/settings_' . $activetab . '_' . $section . '.php' ) ) {
			include CLASIFY_CLASSIFIED_PLUGIN_PATH . '/views/settings/settings_' . $activetab . '_' . $section . '.php';
		}
		include CLASIFY_CLASSIFIED_PLUGIN_PATH . '/views/settings/settings.php';
	}

	function cl_admin_sanitize_html_class( $classes, $return_format = 'input' ) {
		if ( 'input' === $return_format ) {
			$return_format = is_array( $classes ) ? 'array' : 'string';
		}
		$classes           = is_array( $classes ) ? $classes : explode( ' ', $classes );
		$sanitized_classes = array_map( 'sanitize_html_class', $classes );
		if ( 'array' === $return_format ) {
			return $sanitized_classes;
		} else {
			return implode( ' ', $sanitized_classes );
		}
	}

	public function pre_update_option_function( $value, array $old_value, $option ) {
		return array_merge( $old_value, $value );
	}

	function cl_admin_get_settings_tab_sections( $tab = false ) {
		$tabs     = array();
		$sections = $this->setSubTabList();
		if ( $tab && ! empty( $sections[ $tab ] ) ) {
			$tabs = $sections[ $tab ];
		} elseif ( $tab ) {
			$tabs = array();
		}
		return $tabs;
	}



	protected function setTabList() {
		$this->tablist = array(
			'general'    => esc_html__( 'General', 'clasify-classified-listing' ),
			'payments'   => esc_html__( 'Payment Gateways', 'clasify-classified-listing' ),
			'emails'     => esc_html__( 'Emails', 'clasify-classified-listing' ),
			'advanced'   => esc_html__( 'Advanced', 'clasify-classified-listing' ),
			'tools'      => esc_html__( 'Tools', 'clasify-classified-listing' ),
			'pagelayout' => esc_html__( 'Pages', 'clasify-classified-listing' ),
			'additional' => esc_html__( 'Additional', 'clasify-classified-listing' ),
		);
		$this->tablist = apply_filters( 'cl_admin_settings_tablist', $this->tablist );
		return $this->tablist;
	}

	/**
	 * Processes all CL actions sent via POST and GET by looking for the 'cl-action'
	 * request and running do_action() to call the function
	 *
	 * @since 1.0
	 * @return void
	 */
	public function cl_process_actions() {
		if ( isset( $_POST['cl-action'] ) ) {
			do_action( 'cl_' . $_POST['cl-action'], $_POST );
		}
		if ( isset( $_GET['cl-action'] ) ) {
			do_action( 'cl_' . $_GET['cl-action'], $_GET );
		}
	}


	public function cl_register_settings() {
		if ( false == get_option( 'cl_admin_settings' ) ) {
			add_option( 'cl_admin_settings' );
		}
		$setsubtablist = $this->setSubTabList();
		foreach ( $this->setSettingList() as $maintab => $sections ) {
			foreach ( $sections as $section => $settings ) {
				if ( ! $this->cl_settings_tab_exist( $setsubtablist, $section ) ) {
					$section  = 'main';
					$settings = $sections;
				}
				add_settings_section(
					'cl_admin_settings_' . $maintab . '_' . $section,
					'',
					'__return_false',
					'cl_admin_settings_' . $maintab . '_' . $section
				);
				$targer = 'cl_admin_settings_' . $maintab . '_' . $section;
				foreach ( $settings as $option ) {
					if ( empty( $option['id'] ) ) {
						continue;
					}
					$args = wp_parse_args(
						$option,
						array(
							'section'       => $section,
							'id'            => null,
							'desc'          => '',
							'name'          => '',
							'size'          => null,
							'options'       => '',
							'std'           => '',
							'min'           => null,
							'max'           => null,
							'step'          => null,
							'chosen'        => null,
							'multiple'      => null,
							'placeholder'   => null,
							'allow_blank'   => true,
							'readonly'      => false,
							'faux'          => false,
							'tooltip_title' => false,
							'tooltip_desc'  => false,
							'field_class'   => '',
						)
					);

					if ( $option['type'] == 'header' ) {
						add_settings_section(
							'cl_admin_settings_' . $maintab . '_' . $section . '_' . $option['type'],
							$option['name'],
							array( $this, 'cl_admin_header_callback' ),
							'cl_admin_settings_' . $maintab . '_' . $section
						);
						$targer = 'cl_admin_settings_' . $maintab . '_' . $section . '_' . $option['type'];
					}

					if ( $option['type'] != 'header' ) {
						add_settings_field(
							'cl_admin_settings[' . $args['id'] . ']',
							$args['name'],
							method_exists( $this, 'cl_admin_' . $args['type'] . '_callback' ) ? array( $this, 'cl_admin_' . $args['type'] . '_callback' ) : array( $this, 'cl_admin_missing_callback' ),
							'cl_admin_settings_' . $maintab . '_' . $section,
							$targer,
							$args
						);
					}
				}
			}
		}
		register_setting( 'cl_all_settings', 'cl_admin_settings' );
	}

	function cl_admin_missing_callback( $args ) {
		printf(
			__( 'The callback function used for the %s setting is missing.', 'clasify-classified-listing' ),
			'<strong>' . $args['id'] . '</strong>'
		);
	}

	function cl_settings_tab_exist( $array, $keysearch ) {
		foreach ( $array as $key => $item ) {
			if ( $key == $keysearch ) {
				return true;
			} elseif ( is_array( $item ) && $this->cl_settings_tab_exist( $item, $keysearch ) ) {
				return true;
			}
		}
		return false;
	}


	protected function setSubTabList() {
		$this->subtablist = array(
			'general'    => apply_filters(
				'cl_admin_settings_sections_general',
				array(
					'main'     => __( 'General', 'clasify-classified-listing' ),
					'currency' => __( 'Currency', 'clasify-classified-listing' ),
				)
			),
			'payments'   => apply_filters(
				'cl_admin_settings_sections_gateways',
				array(
					'paypal_standard' => __( 'Paypal Standard', 'clasify-classified-listing' ),
					'stripe_standard' => __( 'Stripe', 'clasify-classified-listing' ),
					'woocommerce_payment' => __( 'Woocommerce', 'clasify-classified-listing' ),
				)
			),
			'emails'     => apply_filters(
				'cl_admin_settings_sections_emails',
				array(
					'main'               => __( 'General', 'clasify-classified-listing' ),
					'purchase_receipts'  => __( 'Purchase Receipts', 'clasify-classified-listing' ),
					'sale_notifications' => __( 'New Sale Notifications', 'clasify-classified-listing' ),
				)
			),
			'advanced'   => apply_filters(
				'cl_admin_settings_sections_gateways',
				array(
					'main' => __( 'General', 'clasify-classified-listing' ),
				)
			),
			'tools'      => apply_filters(
				'cl_admin_settings_sections_gateways',
				array(
					'main' => __( 'General', 'clasify-classified-listing' ),
				)
			),
			'pagelayout' => apply_filters(
				'cl_admin_settings_sections_gateways',
				array(
					'main'            => __( 'General', 'clasify-classified-listing' ),
					'custom_field'             => __( 'Custom Field', 'clasify-classified-listing' ),
					'add'             => __( 'Add Listing', 'clasify-classified-listing' ),
					'single'          => __( 'Single', 'clasify-classified-listing' ),
					'archive'         => __( 'Archive (Grid)', 'clasify-classified-listing' ),
					'archive_list'    => __( 'Archive (List)', 'clasify-classified-listing' ),
					'comp_field_list' => __( 'Compare Fields', 'clasify-classified-listing' ),
					'search'          => __( 'Search', 'clasify-classified-listing' ),
				)
			),
			'additional' => apply_filters(
				'cl_admin_settings_sections_gateways',
				array(
					'main' => __( 'General', 'clasify-classified-listing' ),
				)
			),

		);
		$this->subtablist = apply_filters( 'cl_admin_settings_tablist', $this->subtablist );
		return $this->subtablist;
	}

	protected function setSettingList() {
		$this->settinglist = array(
			/** General Settings */
			'general'    => apply_filters(
				'cl_admin_settings_general',
				array(
					'main'     => Pages::get_setting(),
					'currency' => array(
						'currency'            => array(
							'id'      => 'currency',
							'name'    => __( 'Currency', 'clasify-classified-listing' ),
							'desc'    => __( 'Choose your currency. Note that some payment gateways have currency restrictions.', 'clasify-classified-listing' ),
							'type'    => 'select',
							'options' => Currencies::cl_admin_get_currencies(),
							'chosen'  => true,
						),
						'currency_position'   => array(
							'id'      => 'currency_position',
							'name'    => __( 'Currency Position', 'clasify-classified-listing' ),
							'desc'    => __( 'Choose the location of the currency sign.', 'clasify-classified-listing' ),
							'type'    => 'select',
							'options' => array(
								'before' => __( 'Before - $10', 'clasify-classified-listing' ),
								'after'  => __( 'After - 10$', 'clasify-classified-listing' ),
							),
						),
						'thousands_separator' => array(
							'id'   => 'thousands_separator',
							'name' => __( 'Thousands Separator', 'clasify-classified-listing' ),
							'desc' => __( 'The symbol (usually , or .) to separate thousands.', 'clasify-classified-listing' ),
							'type' => 'text',
							'size' => 'small',
							'std'  => ',',
						),
						'decimal_separator'   => array(
							'id'   => 'decimal_separator',
							'name' => __( 'Decimal Separator', 'clasify-classified-listing' ),
							'desc' => __( 'The symbol (usually , or .) to separate decimal points.', 'clasify-classified-listing' ),
							'type' => 'text',
							'size' => 'small',
							'std'  => '.',
						),
						'number_of_decimal'   => array(
							'id'   => 'number_of_decimal',
							'name' => __( 'Number of decimals', 'clasify-classified-listing' ),
							'type' => 'number',
							'size' => 'small',
							'std'  => '0',
						),
					),
				)
			),
			/** Payment Gateways Settings */
			'payments'   => apply_filters(
				'cl_admin_settings_gateways',
				array(
					'paypal_standard' => PaypalStandard::get_setting(),
					'stripe_standard' => Stripe::get_setting(),
					'woocommerce_payment' => Woocommerce::get_setting(),
				)
			),
			/** Emails Settings */
			'emails'     => apply_filters(
				'cl_admin_settings_emails',
				array(
					'main'               => array(
						'email_header'     => array(
							'id'   => 'email_header',
							'name' => '<strong>' . __( 'Email Configuration', 'clasify-classified-listing' ) . '</strong>',
							'type' => 'header',
						),
						'email_logo'       => array(
							'id'   => 'email_logo',
							'name' => __( 'Logo', 'clasify-classified-listing' ),
							'desc' => __( 'Upload or choose a logo to be displayed at the top of the purchase receipt emails. Displayed on HTML emails only.', 'clasify-classified-listing' ),
							'type' => 'upload',
						),
						'from_name'        => array(
							'id'   => 'from_name',
							'name' => __( 'From Name', 'clasify-classified-listing' ),
							'desc' => __( 'The name purchase receipts are said to come from. This should probably be your site or shop name.', 'clasify-classified-listing' ),
							'type' => 'text',
							'std'  => get_bloginfo( 'name' ),
						),
						'from_email'       => array(
							'id'   => 'from_email',
							'name' => __( 'From Email', 'clasify-classified-listing' ),
							'desc' => __( 'Email to send purchase receipts from. This will act as the "from" and "reply-to" address.', 'clasify-classified-listing' ),
							'type' => 'email',
							'std'  => get_bloginfo( 'admin_email' ),
						),
						'email_settings'   => array(
							'id'   => 'email_settings',
							'name' => '',
							'desc' => '',
							'type' => 'hook',
						),
						'sendwp_header'    => array(
							'id'   => 'sendwp_header',
							'name' => '<strong>' . __( 'SendWP', 'clasify-classified-listing' ) . '</strong>',
							'type' => 'header',
						),
						'sendwp'           => array(
							'id'   => 'sendwp',
							'name' => __( 'Deliverability settings', 'clasify-classified-listing' ),
							'desc' => '',
							'type' => 'sendwp',
						),
						'recapture_header' => array(
							'id'   => 'recapture_header',
							'name' => '<strong>' . __( 'Recapture', 'clasify-classified-listing' ) . '</strong>',
							'type' => 'header',
						),
						'recapture'        => array(
							'id'   => 'recapture',
							'name' => __( 'Abandoned cart recovery', 'clasify-classified-listing' ),
							'desc' => '',
							'type' => 'recapture',
						),
					),
					'purchase_receipts'  => array(
						'purchase_receipt_email_settings' => array(
							'id'   => 'purchase_receipt_email_settings',
							'name' => '',
							'desc' => '',
							'type' => 'hook',
						),
						'purchase_subject'                => array(
							'id'   => 'purchase_subject',
							'name' => __( 'Purchase Email Subject', 'clasify-classified-listing' ),
							'desc' => __( 'Enter the subject line for the purchase receipt email.', 'clasify-classified-listing' ),
							'type' => 'text',
							'std'  => __( 'Purchase Receipt', 'clasify-classified-listing' ),
						),
						'purchase_heading'                => array(
							'id'   => 'purchase_heading',
							'name' => __( 'Purchase Email Heading', 'clasify-classified-listing' ),
							'desc' => __( 'Enter the heading for the purchase receipt email.', 'clasify-classified-listing' ),
							'type' => 'text',
							'std'  => __( 'Purchase Receipt', 'clasify-classified-listing' ),
						),
						'purchase_receipt'                => array(
							'id'   => 'purchase_receipt',
							'name' => __( 'Purchase Receipt', 'clasify-classified-listing' ),
							'desc' => __( 'Enter the text that is sent as purchase receipt email to users after completion of a successful purchase. HTML is accepted. Available template tags:', 'clasify-classified-listing' ) . '<br/>' . CCP()->common->emailtags->cl_get_emails_tags_list(),
							'type' => 'rich_editor',
							'std'  => __( 'Dear', 'clasify-classified-listing' ) . " {name},\n\n" . __( 'Thank you for your purchase. Please click on the link(s) below to listing your files.', 'clasify-classified-listing' ) . "\n\n{listing_list}\n\n{sitename}",
						),
					),
					'sale_notifications' => array(
						'sale_notification_subject' => array(
							'id'   => 'sale_notification_subject',
							'name' => __( 'Sale Notification Subject', 'clasify-classified-listing' ),
							'desc' => __( 'Enter the subject line for the sale notification email.', 'clasify-classified-listing' ),
							'type' => 'text',
							'std'  => 'New listing purchase - Order #{payment_id}',
						),
						'sale_notification_heading' => array(
							'id'   => 'sale_notification_heading',
							'name' => __( 'Sale Notification Heading', 'clasify-classified-listing' ),
							'desc' => __( 'Enter the heading for the sale notification email.', 'clasify-classified-listing' ),
							'type' => 'text',
							'std'  => __( 'New Sale!', 'clasify-classified-listing' ),
						),
						'sale_notification'         => array(
							'id'   => 'sale_notification',
							'name' => __( 'Sale Notification', 'clasify-classified-listing' ),
							'desc' => __( 'Enter the text that is sent as sale notification email after completion of a purchase. HTML is accepted. Available template tags:', 'clasify-classified-listing' ) . '<br/>' . CCP()->common->emailtags->cl_get_emails_tags_list(),
							'type' => 'rich_editor',
							'std'  => cl_get_default_sale_notification_email(),
						),
						'admin_notice_emails'       => array(
							'id'   => 'admin_notice_emails',
							'name' => __( 'Sale Notification Emails', 'clasify-classified-listing' ),
							'desc' => __( 'Enter the email address(es) that should receive a notification anytime a sale is made, one per line.', 'clasify-classified-listing' ),
							'type' => 'textarea',
							'std'  => get_bloginfo( 'admin_email' ),
						),
						'disable_admin_notices'     => array(
							'id'   => 'disable_admin_notices',
							'name' => __( 'Disable Admin Notifications', 'clasify-classified-listing' ),
							'desc' => __( 'Check this box if you do not want to receive sales notification emails.', 'clasify-classified-listing' ),
							'type' => 'checkbox',
						),
					),
				)
			),
			/** Styles Settings */
			'advanced'   => apply_filters(
				'cl_admin_settings_advanced',
				array(
					'main' => array(
						'enforce_ssl'       => array(
							'id'   => 'enforce_ssl',
							'name' => __( 'Enforce SSL', 'clasify-classified-listing' ),
							'desc' => __( 'The act of forcing all website traffic to HTTPS via a 301 redirect.', 'clasify-classified-listing' ),
							'type' => 'checkbox',
						),
						'no_cache_checkout' => array(
							'id'   => 'no_cache_checkout',
							'name' => __( 'No Cache Checkout', 'clasify-classified-listing' ),
							'desc' => '',
							'type' => 'checkbox',
						),
						'redirect_on_add'   => array(
							'id'   => 'redirect_on_add',
							'name' => __( 'Redirect On Add', 'clasify-classified-listing' ),
							'desc' => '',
							'type' => 'checkbox',
						),
						'disable_advanced'  => array(
							'id'            => 'disable_styles',
							'name'          => __( 'Disable Styles', 'clasify-classified-listing' ),
							'desc'          => __( 'Check this to disable all included styling of buttons, checkout fields, and all other elements.', 'clasify-classified-listing' ),
							'type'          => 'checkbox',
							'tooltip_title' => __( 'Disabling Styles', 'clasify-classified-listing' ),
							'tooltip_desc'  => __( "If your theme has a complete custom CSS file for Clasify Classified Plugin, you may wish to disable our default styles. This is not recommended unless you're sure your theme has a complete custom CSS.", 'clasify-classified-listing' ),
						),
					),
				)
			),
			/** Tools Settings */
			'tools'      => apply_filters(
				'cl_admin_settings_tools',
				array(
					'main' => array(
						'item_quantities' => array(
							'id'   => 'item_quantities',
							'name' => __( 'Enable Item Quantities', 'clasify-classified-listing' ),
							'desc' => '',
							'type' => 'checkbox',
						),
						'enable_taxes'    => array(
							'id'   => 'enable_taxes',
							'name' => __( 'Enable Taxes', 'clasify-classified-listing' ),
							'desc' => __( 'Choose if you want to enable taxes or not', 'clasify-classified-listing' ),
							'type' => 'checkbox',
						),
					),
				)
			),
			/** Page Settings */
			'pagelayout' => apply_filters(
				'cl_admin_settings_pagelayout',
				array(
					'main'    => array(
						// -- Listing archive settings
						'listing_settings_header' => array(
							'id'   => 'listing_settings_header',
							'name' => '<strong>' . __( 'LISTING ARCHIVE', 'clasify-classified-listing' ) . '</strong>',
							'type' => 'header',
						),
						'listing_slug'            => array(
							'id'   => 'listing_slug',
							'name' => __( 'Listing Slug', 'clasify-classified-listing' ),
							'type' => 'text',
							'std'  => 'listings',
						),
						'default_layout'          => array(
							'id'      => 'default_layout',
							'name'    => __( 'Default Archive Layout', 'clasify-classified-listing' ),
							'desc'    => __( 'Choose your default listing archive page layout.', 'clasify-classified-listing' ),
							'type'    => 'select',
							'options' => array(
								'grid' => __( 'Grid', 'clasify-classified-listing' ),
								'list' => __( 'List', 'clasify-classified-listing' ),
								'map'  => __( 'Map', 'clasify-classified-listing' ),
							),
							'chosen'  => true,
						),
						'layout_columns_grid'     => array(
							'id'      => 'layout_columns_grid',
							'name'    => __( 'Number of Columns [Grid]', 'clasify-classified-listing' ),
							'desc'    => __( 'Choose number of Listing layout columns.', 'clasify-classified-listing' ),
							'type'    => 'select',
							'options' => array(
								'col-md-12' => __( '1', 'clasify-classified-listing' ),
								'col-md-6'  => __( '2', 'clasify-classified-listing' ),
								'col-md-4'  => __( '3', 'clasify-classified-listing' ),
								'col-md-3'  => __( '4', 'clasify-classified-listing' ),
								'col-md-2'  => __( '6', 'clasify-classified-listing' ),
								'col-md-1'  => __( '12', 'clasify-classified-listing' ),
							),
							'chosen'  => true,
							'std'     => 'col-md-4',
						),
						'layout_columns_list'     => array(
							'id'      => 'layout_columns_list',
							'name'    => __( 'Number of Columns [List]', 'clasify-classified-listing' ),
							'desc'    => __( 'Choose number of Listing layout columns.', 'clasify-classified-listing' ),
							'type'    => 'select',
							'options' => array(
								'col-md-12' => __( '1', 'clasify-classified-listing' ),
								'col-md-6'  => __( '2', 'clasify-classified-listing' ),
								'col-md-4'  => __( '3', 'clasify-classified-listing' ),
								'col-md-3'  => __( '4', 'clasify-classified-listing' ),
								'col-md-2'  => __( '6', 'clasify-classified-listing' ),
								'col-md-1'  => __( '12', 'clasify-classified-listing' ),
							),
							'chosen'  => true,
							'std'     => 'col-md-6',
						),
						'listings_per_pages'      => array(
							'id'   => 'listings_per_pages',
							'name' => __( 'Listings per pages', 'clasify-classified-listing' ),
							'desc' => __( 'Number of Listings shown on per archive page', 'clasify-classified-listing' ),
							'type' => 'number',
							'std'  => 4,
						),
						'listing_purchase'      => array(
							'id'   => 'listing_purchase',
							'name' => __( 'Listing Purchase', 'clasify-classified-listing' ),
							'type' => 'checkbox',
							'std'  => true,
						),
						'enable_geolocation'      => array(
							'id'   => 'enable_geolocation',
							'name' => __( 'Enable Geolocation', 'clasify-classified-listing' ),
							'desc' => __( 'Enabling geolocation added the functionality of the visited user to know their own location on the map. User need to give gps access on their device.', 'clasify-classified-listing' ),
							'type' => 'checkbox',
							'std'  => true,
						),
						'default_latitude'        => array(
							'id'   => 'default_latitude',
							'name' => __( 'Default Latitude', 'clasify-classified-listing' ),
							'type' => 'text',
							'std'  => '32.780927',
						),
						'default_longitude'       => array(
							'id'   => 'default_longitude',
							'name' => __( 'Default Longitude', 'clasify-classified-listing' ),
							'type' => 'text',
							'std'  => ' -96.798205',
						),
						'default_zoom'            => array(
							'id'   => 'default_zoom',
							'name' => __( 'Default Zoom', 'clasify-classified-listing' ),
							'type' => 'number',
							'std'  => '4',
						),
					),
					'single'  => array(
						'single_page' => array(
							'id'   => 'single_page',
							'name' => '',
							'type' => 'hidden',
						),
					),
					'archive' => array(
						'archive_page' => array(
							'id'   => 'archive_page',
							'name' => '',
							'type' => 'hidden',
						),
					),
					'search'  => array(
						'search_page' => array(
							'id'   => 'search_page',
							'name' => '',
							'type' => 'hidden',
						),
					),
				)
			),
			/** Additional Settings */
			'additional' => apply_filters(
				'cl_admin_settings_advanced',
				array(
					'main' => array(
						'add_listing_status'   => array(
							'id'      => 'add_listing_status',
							'name'    => __( 'Add Listing Status', 'clasify-classified-listing' ),
							'desc'    => __( 'Choose the status of the listig when added.', 'clasify-classified-listing' ),
							'type'    => 'select',
							'options' => array(
								'publish' => __( 'Publish', 'clasify-classified-listing' ),
								'pending' => __( 'Pending', 'clasify-classified-listing' ),
							),
							'chosen'  => true,
							'std'     => 'publish',
						),
						'add_listing_redirect_url'     => array(
							'id'   => 'add_listing_redirect_url',
							'name' => __( 'Add Listing Redirect', 'clasify-classified-listing' ),
							'desc' => __( 'The url the site will redirect after user add listing', 'clasify-classified-listing' ),
							'type' => 'url',
							'std'  => '',
						),
						'reg_redirect_url'     => array(
							'id'   => 'reg_redirect_url',
							'name' => __( 'Registration Redirect', 'clasify-classified-listing' ),
							'desc' => __( 'The url the site will redirect after user ragitration', 'clasify-classified-listing' ),
							'type' => 'url',
							'std'  => '',
						),
						'subscription_on_off'   => array(
							'id'      => 'subscription_on_off',
							'name'    => __( 'Subscription', 'clasify-classified-listing' ),
							'type'    => 'select',
							'options' => array(
								'on' => __( 'On', 'clasify-classified-listing' ),
								'off' => __( 'Off', 'clasify-classified-listing' ),
							),
							'chosen'  => true,
							'std'     => 'on',
						),
						'checkout_button_text' => array(
							'id'   => 'checkout_button_text',
							'name' => __( 'Checkout Button Text', 'clasify-classified-listing' ),
							'desc' => __( 'Text shown on the Add to Cart Button when the product is already in the cart', 'clasify-classified-listing' ),
							'type' => 'text',
							'std'  => get_bloginfo( 'name' ),
						),
						'checkout_color'       => array(
							'id'      => 'checkout_color',
							'name'    => __( 'Button Type', 'clasify-classified-listing' ),
							'desc'    => __( 'Choose your checkout button type.', 'clasify-classified-listing' ),
							'type'    => 'select',
							'options' => array(
								'btn btn-default' => __( 'Default', 'clasify-classified-listing' ),
								'btn btn-primary' => __( 'Primary', 'clasify-classified-listing' ),
								'btn btn-success' => __( 'Success', 'clasify-classified-listing' ),
								'btn btn-info'    => __( 'Info', 'clasify-classified-listing' ),
								'btn btn-warning' => __( 'Warning', 'clasify-classified-listing' ),
								'btn btn-danger'  => __( 'Danger', 'clasify-classified-listing' ),
								'btn btn-link'    => __( 'Link', 'clasify-classified-listing' ),
							),
							'chosen'  => true,
						),
						'button_style'         => array(
							'id'   => 'button_style',
							'name' => __( 'Button Style', 'clasify-classified-listing' ),
							'desc' => __( 'Text shown on the Add to Cart Button when the product is already in the cart', 'clasify-classified-listing' ),
							'type' => 'text',
						),
						'default_gateway'      => array(
							'id'      => 'default_gateway',
							'name'    => __( 'Default Payment Gateway', 'clasify-classified-listing' ),
							'desc'    => __( 'Choose your default payment gateway.', 'clasify-classified-listing' ),
							'type'    => 'select',
							'options' => array(
								'paypal' => __( 'Paypal', 'clasify-classified-listing' ),
								'stripe' => __( 'Stripe', 'clasify-classified-listing' ),
							),
							'chosen'  => true,
						),
						'logged_in_only'       => array(
							'id'   => 'logged_in_only',
							'name' => __( 'Logged In Only', 'clasify-classified-listing' ),
							'desc' => __( 'Must be logged into an account to purchase', 'clasify-classified-listing' ),
							'type' => 'checkbox',
							'std'  => true,
						),
						'enable_skus'          => array(
							'id'   => 'enable_skus',
							'name' => __( 'Enable Skus', 'clasify-classified-listing' ),
							'desc' => '',
							'type' => 'checkbox',
							'std'  => true,
						),
						'tax_rate'             => array(
							'id'   => 'tax_rate',
							'name' => __( 'Tax Rate', 'clasify-classified-listing' ),
							'desc' => '',
							'type' => 'text',
							'std'  => get_bloginfo( 'name' ),
						),
					),
				)
			),
		);
		$this->settinglist = apply_filters( 'cl_admin_settings_settinglist', $this->settinglist );
		return $this->settinglist;
	}

	function cl_get_sanitize_key( $key ) {
		$process_key = preg_replace( '/[^a-zA-Z0-9_\-\.\:\/]/', '', $key );
		return apply_filters( 'cl_get_sanitize_key', $process_key, $key );
	}

	function cl_admin_header_callback( $args ) {
		$html = '';
		switch ( $args['id'] ) {
			case 'cl_admin_settings_payments_paypal_standard_header':
				$html = sprintf( __( 'Enter your PayPal API credentials to process refunds via PayPal. Learn how to access your <a href="%s"> PayPal API Credentials</a> .', 'clasify-classified-listing' ), 'https://developer.paypal.com/webapps/developer/docs/classic/api/apiCredentials/#create-an-api-signature' );
		}

		echo apply_filters( 'cl_admin_after_setting_output_header', $html, $args );
	}

	function cl_admin_checkbox_callback( $args ) {
		$cl_admin_option = cl_admin_get_option( $args['id'] );
		if ( isset( $args['faux'] ) && true === $args['faux'] ) {
			$name = '';
		} else {
			$name = 'name="cl_admin_settings[' . $this->cl_get_sanitize_key( $args['id'] ) . ']"';
		}
		$class   = $this->cl_admin_sanitize_html_class( $args['field_class'] );
		$checked = ! empty( $cl_admin_option ) ? checked( 1, $cl_admin_option, false ) : '';
		$html    = '<input type="hidden"' . $name . ' value="-1" />';
		if ( isset( $args['extra'] ) ) {
			$html .= '<input type="checkbox" id="cl_admin_settings[' . esc_attr( $args['extra'] ) . '][' . $this->cl_get_sanitize_key( $args['id'] ) . ']"' . $name . ' value="1" ' . esc_attr( $checked ) . ' class="' . esc_attr( $class ) . '"/>';
		} else {
			$html .= '<input type="checkbox" id="cl_admin_settings[' . $this->cl_get_sanitize_key( $args['id'] ) . ']"' . $name . ' value="1" ' . esc_attr( $checked ) . ' class="' . esc_attr( $class ) . '"/>';
		}

		$html .= '<label for="cl_admin_settings[' . $this->cl_get_sanitize_key( $args['id'] ) . ']"> ' . wp_kses_post( $args['desc'] ) . '</label>';

		if ( isset( $args['description'] ) ) {
			$html .= '<p class="description">' . wp_kses_post( $args['description'] ) . '</p>';
		}
		echo apply_filters( 'cl_admin_after_setting_output', $html, $args );
	}

	function cl_admin_multicheck_callback( $args ) {
		$cl_admin_option = cl_admin_get_option( $args['id'] );
		$class           = $this->cl_admin_sanitize_html_class( $args['field_class'] );
		$html            = '';
		if ( ! empty( $args['options'] ) ) {
			$html .= '<input type="hidden" name="cl_admin_settings[' . $this->cl_get_sanitize_key( $args['id'] ) . ']" value="-1" />';
			foreach ( $args['options'] as $key => $option ) {
				if ( isset( $cl_admin_option[ $key ] ) ) {
					$enabled = $option;
				} else {
					$enabled = null;
				}
				$html .= '<input name="cl_admin_settings[' . $this->cl_get_sanitize_key( $args['id'] ) . '][' . $this->cl_get_sanitize_key( $key ) . ']" id="cl_admin_settings[' . $this->cl_get_sanitize_key( $args['id'] ) . '][' . $this->cl_get_sanitize_key( $key ) . ']" class="' . esc_attr( $class ) . '" type="checkbox" value="' . esc_attr( $option ) . '" ' . checked( $option, $enabled, false ) . '/>&nbsp;';
				$html .= '<label for="cl_admin_settings[' . $this->cl_get_sanitize_key( $args['id'] ) . '][' . $this->cl_get_sanitize_key( $key ) . ']">' . wp_kses_post( $option ) . '</label><br/>';
			}
			$html .= '<p class="description">' . esc_html( $args['desc'] ) . '</p>';
		}
		echo apply_filters( 'cl_admin_after_setting_output', $html, $args );
	}


	function cl_admin_hook_callback( $args ) {
		do_action( 'cl_' . $args['id'], $args );
	}

	function cl_admin_rich_editor_callback( $args ) {
		$cl_option = cl_admin_get_option( $args['id'] );

		if ( $cl_option ) {
			$value = $cl_option;
		} else {
			if ( ! empty( $args['allow_blank'] ) && empty( $cl_option ) ) {
				$value = '';
			} else {
				$value = isset( $args['std'] ) ? $args['std'] : '';
			}
		}

		$rows = isset( $args['size'] ) ? $args['size'] : 20;

		$class = cl_sanitize_html_class( $args['field_class'] );

		ob_start();
		wp_editor(
			stripslashes( $value ),
			'cl_settings_' . esc_attr( $args['id'] ),
			array(
				'textarea_name' => 'cl_admin_settings[' . esc_attr( $args['id'] ) . ']',
				'textarea_rows' => absint( $rows ),
				'editor_class'  => $class,
			)
		);
		$html = ob_get_clean();

		$html .= '<br/><label for="cl_settings[' . CCP()->common->formatting->cl_sanitize_key( $args['id'] ) . ']"> ' . wp_kses_post( $args['desc'] ) . '</label>';

		echo apply_filters( 'cl_after_setting_output', $html, $args );
	}


	function cl_admin_select_callback( $args ) {
		$class           = '';
		$cl_admin_option = cl_admin_get_option( $args['id'] );
		if ( $cl_admin_option ) {
			$value = $cl_admin_option;
		} else {
			if ( empty( $args['multiple'] ) ) {
				$value = isset( $args['std'] ) ? $args['std'] : '';
			} else {
				$value = ! empty( $args['std'] ) ? $args['std'] : array();
			}
		}
		if ( isset( $args['placeholder'] ) ) {
			$placeholder = $args['placeholder'];
		} else {
			$placeholder = '';
		}
		if ( isset( $args['field_class'] ) ) {
			$class = $this->cl_admin_sanitize_html_class( $args['field_class'] );
		}

		if ( isset( $args['chosen'] ) ) {
			$class .= ' cl-select-chosen';
		}
		$nonce     = isset( $args['data']['nonce'] ) ? ' data-nonce="' . cl_sanitization( $args['data']['nonce'] ) . '" ' : '';
		$name_attr = 'cl_admin_settings[' . esc_attr( $args['id'] ) . ']';

		$name_attr = ( isset( $args['multiple'] ) ) ? $name_attr . '[]' : $name_attr;
		$key       = preg_replace( '/[^a-zA-Z0-9_\-\.\:\/]/', '', $args['id'] );
		$html      = '<select ' . $nonce . ' id="cl_admin_settings[' . $this->cl_get_sanitize_key( $args['id'] ) . ']" name="' . esc_attr( $name_attr ) . '" class="' . esc_attr( $class ) . '" data-placeholder="' . esc_html( $placeholder ) . '" ' . ( ( isset( $args['multiple'] ) ) ? 'multiple="true"' : '' ) . '>';
		foreach ( $args['options'] as $option => $name ) {
			if ( isset( $args['multiple'] ) ) {
				if ( ! $args['multiple'] ) {
					$selected = selected( $option, $value, false );
					$html    .= '<option value="' . esc_attr( $option ) . '" ' . esc_attr( $selected ) . '>' . esc_html( $name ) . '</option>';
				} else {
					$html .= '<option value="' . esc_attr( $option ) . '" ' . ( ( in_array( $option, $value ) ) ? 'selected="true"' : '' ) . '>' . esc_html( $name ) . '</option>';
				}
			} else {
				$selected = selected( $option, $value, false );
				$html    .= '<option value="' . esc_attr( $option ) . '" ' . esc_attr( $selected ) . '>' . esc_html( $name ) . '</option>';
			}
		}
		$desc = '';
		if ( isset( $args['desc'] ) ) {
			$desc = $args['desc'];
		}
		$html .= '</select>';
		$html .= '<label for="cl_admin_settings[' . $this->cl_get_sanitize_key( $args['id'] ) . ']"> ' . wp_kses_post( $desc ) . '</label>';
		echo apply_filters( 'cl_admin_after_setting_output', $html, $args );
	}


	function cl_admin_payment_icons_callback( $args ) {
		$cl_admin_option = cl_admin_get_option( $args['id'] );
		$class           = $this->cl_admin_sanitize_html_class( $args['field_class'] );
		$html            = '<input type="hidden" name="cl_admin_settings[' . $this->cl_get_sanitize_key( $args['id'] ) . ']" value="-1" />';
		if ( ! empty( $args['options'] ) ) {
			foreach ( $args['options'] as $key => $option ) {
				if ( isset( $cl_admin_option[ $key ] ) ) {
					$enabled = $option;
				} else {
					$enabled = null;
				}
				$html .= '<label for="cl_admin_settings[' . $this->cl_get_sanitize_key( $args['id'] ) . '][' . $this->cl_get_sanitize_key( $key ) . ']" class="cl-settings-payment-icon-wrapper">';
				$html .= '<input name="cl_admin_settings[' . $this->cl_get_sanitize_key( $args['id'] ) . '][' . $this->cl_get_sanitize_key( $key ) . ']" id="cl_admin_settings[' . $this->cl_get_sanitize_key( $args['id'] ) . '][' . $this->cl_get_sanitize_key( $key ) . ']" class="' . esc_attr( $class ) . '" type="checkbox" value="' . esc_attr( $option ) . '" ' . checked( $option, $enabled, false ) . '/>&nbsp;';
				if ( cl_admin_string_is_image_url( $key ) ) {
					$html .= '<img class="payment-icon" src="' . esc_url( $key ) . '" style="width:32px;height:24px;position:relative;top:6px;margin-right:5px;"/>';
				} else {
					$card = strtolower( str_replace( ' ', '', $option ) );
					if ( has_filter( 'cl_admin_accepted_payment_' . $card . '_image' ) ) {
						$image = apply_filters( 'cl_admin_accepted_payment_' . $card . '_image', '' );
					} else {
						$image       = cl_admin_locate_template( 'images' . DIRECTORY_SEPARATOR . 'icons' . DIRECTORY_SEPARATOR . $card . '.png', false );
						$content_dir = WP_CONTENT_DIR;
						if ( function_exists( 'wp_normalize_path' ) ) {
							$image       = wp_normalize_path( $image );
							$content_dir = wp_normalize_path( $content_dir );
						}
						$image = str_replace( $content_dir, content_url(), $image );
					}
					$html .= '<img class="payment-icon" src="' . esc_url( $image ) . '" style="width:32px;height:24px;position:relative;top:6px;margin-right:5px;"/>';
				}
				$html .= $option . '</label>';
			}
			$html .= '<p class="description" style="margin-top:16px;">' . wp_kses_post( $args['desc'] ) . '</p>';
		}

		echo apply_filters( 'cl_admin_after_setting_output', $html, $args );
	}


	function cl_admin_radio_callback( $args ) {
		$cl_admin_options = cl_admin_get_option( $args['id'] );
		$html             = '';
		$class            = $this->cl_admin_sanitize_html_class( $args['field_class'] );
		foreach ( $args['options'] as $key => $option ) {
			$checked = false;
			if ( $cl_admin_options && $cl_admin_options == $key ) {
				$checked = true;
			} elseif ( isset( $args['std'] ) && $args['std'] == $key && ! $cl_admin_options ) {
				$checked = true;
			}
			$html .= '<input name="cl_admin_settings[' . $this->cl_get_sanitize_key( $args['id'] ) . ']" id="cl_admin_settings[' . $this->cl_get_sanitize_key( $args['id'] ) . '][' . $this->cl_get_sanitize_key( $key ) . ']" class="' . esc_attr( $class ) . '" type="radio" value="' . $this->cl_get_sanitize_key( $key ) . '" ' . checked( true, $checked, false ) . '/>&nbsp;';
			$html .= '<label for="cl_admin_settings[' . $this->cl_get_sanitize_key( $args['id'] ) . '][' . $this->cl_get_sanitize_key( $key ) . ']">' . esc_html( $option ) . '</label><br/>';
		}
		$html .= '<p class="description">' . wp_kses_post( $args['desc'] ) . '</p>';
		echo apply_filters( 'cl_admin_after_setting_output', $html, $args );
	}

	function cl_admin_gateways_callback( $args ) {
		$cl_admin_option = cl_admin_get_option( $args['id'] );
		$class           = $this->cl_admin_sanitize_html_class( $args['field_class'] );
		$html            = '<input type="hidden" name="cl_admin_settings[' . $this->cl_get_sanitize_key( $args['id'] ) . ']" value="-1" />';
		foreach ( $args['options'] as $key => $option ) {
			if ( isset( $cl_admin_option[ $key ] ) ) {
				$enabled = '1';
			} else {
				$enabled = null;
			}
			$html .= '<input name="cl_admin_settings[' . esc_attr( $args['id'] ) . '][' . $this->cl_get_sanitize_key( $key ) . ']" id="cl_admin_settings[' . $this->cl_get_sanitize_key( $args['id'] ) . '][' . $this->cl_get_sanitize_key( $key ) . ']" class="' . esc_attr( $class ) . '" type="checkbox" value="1" ' . checked( '1', $enabled, false ) . '/>&nbsp;';
			$html .= '<label for="cl_admin_settings[' . $this->cl_get_sanitize_key( $args['id'] ) . '][' . $this->cl_get_sanitize_key( $key ) . ']">' . esc_html( $option['admin_label'] ) . '</label><br/>';
		}

		echo apply_filters( 'cl_admin_after_setting_output', $html, $args );
	}

	function cl_admin_gateway_select_callback( $args ) {
		$cl_admin_option = cl_admin_get_option( $args['id'] );
		$class           = $this->cl_admin_sanitize_html_class( $args['field_class'] );
		$html            = '';
		$html           .= '<select name="cl_admin_settings[' . $this->cl_get_sanitize_key( $args['id'] ) . ']"" id="cl_admin_settings[' . $this->cl_get_sanitize_key( $args['id'] ) . ']" class="' . esc_attr( $class ) . '">';
		foreach ( $args['options'] as $key => $option ) {
			$selected = isset( $cl_admin_option ) ? selected( $key, $cl_admin_option, false ) : '';
			$html    .= '<option value="' . esc_attr( $this->cl_get_sanitize_key( $key ) ) . '"' . esc_attr( $selected ) . '>' . esc_html( $option['admin_label'] ) . '</option>';
		}
		$html .= '</select>';
		$html .= '<label for="cl_admin_settings[' . esc_attr( $this->cl_get_sanitize_key( $args['id'] ) ) . ']"> ' . wp_kses_post( $args['desc'] ) . '</label>';
		echo apply_filters( 'cl_admin_after_setting_output', $html, $args );
	}

	function cl_admin_input_text_email( $args, $type ) {

		$cl_admin_option = cl_admin_get_option( $args['id'] );
		if ( $cl_admin_option ) {
			$value = $cl_admin_option;
		} elseif ( ! empty( $args['allow_blank'] ) && empty( $cl_admin_option ) ) {
			$value = '';
		} else {
			$value = isset( $args['std'] ) ? $args['std'] : '';
		}
		if ( isset( $args['faux'] ) && true === $args['faux'] ) {
			$args['readonly'] = true;
			$value            = isset( $args['std'] ) ? $args['std'] : '';
			$name             = '';
		} else {
			$name = 'name="cl_admin_settings[' . esc_attr( $args['id'] ) . ']"';
		}
		$class    = $this->cl_admin_sanitize_html_class( $args['field_class'] );
		$disabled = ! empty( $args['disabled'] ) ? ' disabled="disabled"' : '';
		$readonly = $args['readonly'] === true ? ' readonly="readonly"' : '';
		$size     = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
		$html     = '<input type="' . esc_attr( $type ) . '" class="' . esc_attr( $class ) . ' ' . esc_attr( $this->cl_admin_sanitize_html_class( $size ) ) . '-text" id="cl_admin_settings[' . esc_attr( $this->cl_get_sanitize_key( $args['id'] ) ) . ']" ' . $name . ' value="' . esc_attr( stripslashes( $value ) ) . '"' . $readonly . $disabled . ' placeholder="' . esc_attr( $args['placeholder'] ) . '"/>';
		$html    .= '<label for="cl_admin_settings[' . esc_attr( $this->cl_get_sanitize_key( $args['id'] ) ) . ']"> ' . wp_kses_post( $args['desc'] ) . '</label>';
		return $html;
	}

	function cl_admin_set_value( $args ) {
		$cl_admin_option = cl_admin_get_option( $args['id'] );
		if ( $cl_admin_option ) {
			$value = $cl_admin_option;
		} else {
			$value = isset( $args['std'] ) ? $args['std'] : '';
		}
		return $value;
	}



	function cl_admin_text_callback( $args ) {
		echo apply_filters( 'cl_admin_after_setting_output', $this->cl_admin_input_text_email( $args, 'text' ), $args );
	}

	function cl_admin_email_callback( $args ) {
		echo apply_filters( 'cl_admin_after_setting_output', $this->cl_admin_input_text_email( $args, 'email' ), $args );
	}

	function cl_admin_hidden_callback( $args ) {
		echo apply_filters( 'cl_admin_after_setting_output', $this->cl_admin_input_text_email( $args, 'hidden' ), $args );
	}
	function cl_admin_tel_callback( $args ) {
		echo apply_filters( 'cl_admin_after_setting_output', $this->cl_admin_input_text_email( $args, 'tel' ), $args );
	}
	function cl_admin_url_callback( $args ) {
		echo apply_filters( 'cl_admin_after_setting_output', $this->cl_admin_input_text_email( $args, 'url' ), $args );
	}



	function cl_admin_number_callback( $args ) {
		$value = $this->cl_admin_set_value( $args );
		if ( isset( $args['faux'] ) && true === $args['faux'] ) {
			$args['readonly'] = true;
			$value            = isset( $args['std'] ) ? $args['std'] : '';
			$name             = '';
		} else {
			$name = 'name="cl_admin_settings[' . esc_attr( $args['id'] ) . ']"';
		}
		$class = $this->cl_admin_sanitize_html_class( $args['field_class'] );
		$max   = isset( $args['max'] ) ? $args['max'] : 999999;
		$min   = isset( $args['min'] ) ? $args['min'] : 0;
		$step  = isset( $args['step'] ) ? $args['step'] : 1;
		$size  = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
		$html  = '<input type="number" step="' . esc_attr( $step ) . '" max="' . esc_attr( $max ) . '" min="' . esc_attr( $min ) . '" class="' . esc_attr( $class ) . ' ' . $this->cl_admin_sanitize_html_class( $size ) . '-text" id="cl_admin_settings[' . $this->cl_get_sanitize_key( $args['id'] ) . ']" ' . $name . ' value="' . esc_attr( stripslashes( $value ) ) . '"/>';
		$html .= '<label for="cl_admin_settings[' . $this->cl_get_sanitize_key( $args['id'] ) . ']"> ' . wp_kses_post( $args['desc'] ) . '</label>';
		echo apply_filters( 'cl_admin_after_setting_output', $html, $args );
	}

	function cl_admin_upload_callback( $args ) {
		$value = $this->cl_admin_set_value( $args );
		$class = $this->cl_admin_sanitize_html_class( $args['field_class'] );
		$size  = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
		$html  = '<input type="text" class="' . sanitize_html_class( $size ) . '-text cl_admin_settings_upload_input" id="cl_admin_settings[' . $this->cl_get_sanitize_key( $args['id'] ) . ']" class="' . esc_attr( $class ) . '" name="cl_admin_settings[' . esc_attr( $args['id'] ) . ']" value="' . esc_attr( stripslashes( $value ) ) . '"/>';
		$html .= '<span>&nbsp;<input type="button" class="cl_admin_settings_upload_button button-secondary" value="' . __( 'Upload File', 'clasify-classified-listing' ) . '"/></span>';
		$html .= '<label for="cl_admin_settings[' . $this->cl_get_sanitize_key( $args['id'] ) . ']"> ' . wp_kses_post( $args['desc'] ) . '</label>';
		echo apply_filters( 'cl_admin_after_setting_output', $html, $args );
	}

	function cl_admin_textarea_callback( $args ) {
		$value = $this->cl_admin_set_value( $args );
		$class = $this->cl_admin_sanitize_html_class( $args['field_class'] );
		$html  = '<textarea class="' . esc_attr( $class ) . ' large-text" cols="50" rows="5" id="cl_admin_settings[' . $this->cl_get_sanitize_key( $args['id'] ) . ']" name="cl_admin_settings[' . esc_attr( $args['id'] ) . ']">' . esc_textarea( stripslashes( $value ) ) . '</textarea>';
		$html .= '<label for="cl_admin_settings[' . $this->cl_get_sanitize_key( $args['id'] ) . ']"> ' . wp_kses_post( $args['desc'] ) . '</label>';
		echo apply_filters( 'cl_admin_after_setting_output', $html, $args );
	}


	function cl_admin_password_callback( $args ) {
		$value = $this->cl_admin_set_value( $args );
		$class = $this->cl_admin_sanitize_html_class( $args['field_class'] );
		$size  = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
		$html  = '<input type="password" class="' . esc_attr( $class ) . ' ' . $this->cl_admin_sanitize_html_class( $size ) . '-text" id="cl_admin_settings[' . $this->cl_get_sanitize_key( $args['id'] ) . ']" name="cl_admin_settings[' . esc_attr( $args['id'] ) . ']" value="' . esc_attr( $value ) . '"/>';
		$html .= '<label for="cl_admin_settings[' . $this->cl_get_sanitize_key( $args['id'] ) . ']"> ' . wp_kses_post( $args['desc'] ) . '</label>';
		echo apply_filters( 'cl_admin_after_setting_output', $html, $args );
	}

	public function admin_settings_extend( array $a, array $b ) {

		foreach ( $b as $k => $v ) {
			$a[ $k ] = is_array( $v ) && isset( $a[ $k ] ) ? $this->admin_settings_extend( is_array( $a[ $k ] ) ? $a[ $k ] : array(), $v ) : ( isset( $a[ $k ] ) ? $a[ $k ] : $v );
		}

		return $a;
	}

	public function cl_listing_modal_container( $str ) {
		return '<div class="clasify-classified-plugin-modal-container">
			<div class="option-card active">
				<div class="option-card-header">
					<div class="option-card-header-title-section">
						<h3 class="option-card-header-title">Insert Element</h3>
						<div class="header-action-area"><a href="#" class="cptm-header-action-link cptm-header-action-close"><span class="fa fa-times" aria-hidden="true"></span></a></div>
					</div>
				</div>
				<div class="option-card-body">' . $str . '
					
				</div>
				<span class="anchor-down"></span>
			</div>
		</div>';
	}

	function select( $args = array() ) {
		$defaults = array(
			'options'          => array(),
			'name'             => null,
			'class'            => '',
			'id'               => '',
			'selected'         => array(),
			'chosen'           => false,
			'placeholder'      => null,
			'multiple'         => false,
			'show_option_all'  => _x( 'All', 'all dropdown items', 'clasify-classified-listing' ),
			'show_option_none' => _x( 'None', 'no dropdown items', 'clasify-classified-listing' ),
			'data'             => array(),
			'readonly'         => false,
			'disabled'         => false,
		);

		$args = wp_parse_args( $args, $defaults );

		$data_elements = '';
		foreach ( $args['data'] as $key => $value ) {
			$data_elements .= ' data-' . esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
		}

		if ( $args['multiple'] ) {
			$multiple = ' MULTIPLE';
		} else {
			$multiple = '';
		}

		if ( $args['chosen'] ) {
			$args['class'] .= ' cl-select-chosen';
			if ( is_rtl() ) {
				$args['class'] .= ' chosen-rtl';
			}
		}

		if ( $args['placeholder'] ) {
			$placeholder = $args['placeholder'];
		} else {
			$placeholder = '';
		}

		if ( isset( $args['readonly'] ) && $args['readonly'] ) {
			$readonly = ' readonly="readonly"';
		} else {
			$readonly = '';
		}

		if ( isset( $args['disabled'] ) && $args['disabled'] ) {
			$disabled = ' disabled="disabled"';
		} else {
			$disabled = '';
		}

		$class  = implode( ' ', array_map( 'sanitize_html_class', explode( ' ', $args['class'] ) ) );
		$output = '<select' . $disabled . $readonly . ' name="' . esc_attr( $args['name'] ) . '" id="' . esc_attr( CCP()->common->formatting->cl_sanitize_key( str_replace( '-', '_', $args['id'] ) ) ) . '" class="cl-select ' . esc_attr( $class ) . '"' . $multiple . ' data-placeholder="' . esc_attr( $placeholder ) . '"' . $data_elements . '>';

		if ( ! isset( $args['selected'] ) || ( is_array( $args['selected'] ) && empty( $args['selected'] ) ) || ! $args['selected'] ) {
			$selected = '';
		}

		if ( $args['show_option_all'] ) {
			if ( $args['multiple'] && ! empty( $args['selected'] ) ) {
				$selected = selected( true, in_array( 0, (array) $args['selected'] ), false );
			} else {
				$selected = selected( $args['selected'], 0, false );
			}
			$output .= '<option value="all" ' . esc_attr( $selected ) . '>' . esc_html( $args['show_option_all'] ) . '</option>';
		}

		if ( ! empty( $args['options'] ) ) {
			if ( $args['show_option_none'] ) {
				if ( $args['multiple'] ) {
					$selected = selected( true, in_array( -1, $args['selected'] ), false );
				} elseif ( isset( $args['selected'] ) && ! is_array( $args['selected'] ) && ! empty( $args['selected'] ) ) {
					$selected = selected( $args['selected'], -1, false );
				}
				$output .= '<option value="-1" ' . esc_attr( $selected ) . '>' . esc_html( $args['show_option_none'] ) . '</option>';
			}

			foreach ( $args['options'] as $key => $option ) {
				if ( $args['multiple'] && is_array( $args['selected'] ) ) {
					$selected = selected( true, in_array( (string) $key, $args['selected'] ), false );
				} elseif ( isset( $args['selected'] ) && ! is_array( $args['selected'] ) ) {
					$selected = selected( $args['selected'], $key, false );
				}

				$output .= '<option value="' . esc_attr( $key ) . '"' . esc_attr( $selected ) . '>' . esc_html( $option ) . '</option>';
			}
		}

		$output .= '</select>';

		return $output;
	}



	public function text( $args = array() ) {
		// Backwards compatibility
		if ( func_num_args() > 1 ) {
			$args = func_get_args();

			$name  = $args[0];
			$value = isset( $args[1] ) ? $args[1] : '';
			$label = isset( $args[2] ) ? $args[2] : '';
			$desc  = isset( $args[3] ) ? $args[3] : '';
		}

		$defaults = array(
			'id'           => '',
			'name'         => isset( $name ) ? $name : 'text',
			'value'        => isset( $value ) ? $value : null,
			'label'        => isset( $label ) ? $label : null,
			'desc'         => isset( $desc ) ? $desc : null,
			'placeholder'  => '',
			'class'        => 'regular-text',
			'disabled'     => false,
			'autocomplete' => '',
			'data'         => false,
		);

		$args = wp_parse_args( $args, $defaults );

		$class    = implode( ' ', array_map( 'sanitize_html_class', explode( ' ', $args['class'] ) ) );
		$disabled = '';
		if ( $args['disabled'] ) {
			$disabled = ' disabled="disabled"';
		}

		$data = '';
		if ( ! empty( $args['data'] ) ) {
			foreach ( $args['data'] as $key => $value ) {
				$data .= 'data-' . CCP()->common->formatting->cl_sanitize_key( $key ) . '="' . esc_attr( $value ) . '" ';
			}
		}

		$output = '<span id="cl-' . CCP()->common->formatting->cl_sanitize_key( $args['name'] ) . '-wrap">';
		if ( ! empty( $args['label'] ) ) {
			$output .= '<label class="cl-label" for="' . CCP()->common->formatting->cl_sanitize_key( $args['id'] ) . '">' . esc_html( $args['label'] ) . '</label>';
		}

		if ( ! empty( $args['desc'] ) ) {
			$output .= '<span class="cl-description">' . esc_html( $args['desc'] ) . '</span>';
		}

		$output .= '<input type="text" name="' . esc_attr( $args['name'] ) . '" id="' . esc_attr( $args['id'] ) . '" autocomplete="' . esc_attr( $args['autocomplete'] ) . '" value="' . esc_attr( $args['value'] ) . '" placeholder="' . esc_attr( $args['placeholder'] ) . '" class="' . esc_attr( $class ) . '" ' . $data . '' . $disabled . '/>';

		$output .= '</span>';

		return $output;
	}
}
