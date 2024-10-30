<?php
namespace Clasify\Classified\Common\Currencies;

use Clasify\Classified\Traitval\Traitval;

/**
 * The admin class
 */
class Currencies {

	use Traitval;

	public static function cl_admin_get_currencies() {
		$currencies = array(
			'USD'  => __( 'US Dollars (&#36;)', 'clasify-classified-listing' ),
			'EUR'  => __( 'Euros (&euro;)', 'clasify-classified-listing' ),
			'GBP'  => __( 'Pound Sterling (&pound;)', 'clasify-classified-listing' ),
			'AUD'  => __( 'Australian Dollars (&#36;)', 'clasify-classified-listing' ),
			'BRL'  => __( 'Brazilian Real (R&#36;)', 'clasify-classified-listing' ),
			'CAD'  => __( 'Canadian Dollars (&#36;)', 'clasify-classified-listing' ),
			'CZK'  => __( 'Czech Koruna', 'clasify-classified-listing' ),
			'DKK'  => __( 'Danish Krone', 'clasify-classified-listing' ),
			'HKD'  => __( 'Hong Kong Dollar (&#36;)', 'clasify-classified-listing' ),
			'HUF'  => __( 'Hungarian Forint', 'clasify-classified-listing' ),
			'ILS'  => __( 'Israeli Shekel (&#8362;)', 'clasify-classified-listing' ),
			'JPY'  => __( 'Japanese Yen (&yen;)', 'clasify-classified-listing' ),
			'MYR'  => __( 'Malaysian Ringgits', 'clasify-classified-listing' ),
			'MXN'  => __( 'Mexican Peso (&#36;)', 'clasify-classified-listing' ),
			'NZD'  => __( 'New Zealand Dollar (&#36;)', 'clasify-classified-listing' ),
			'NOK'  => __( 'Norwegian Krone', 'clasify-classified-listing' ),
			'PHP'  => __( 'Philippine Pesos', 'clasify-classified-listing' ),
			'PLN'  => __( 'Polish Zloty', 'clasify-classified-listing' ),
			'SGD'  => __( 'Singapore Dollar (&#36;)', 'clasify-classified-listing' ),
			'SEK'  => __( 'Swedish Krona', 'clasify-classified-listing' ),
			'CHF'  => __( 'Swiss Franc', 'clasify-classified-listing' ),
			'TWD'  => __( 'Taiwan New Dollars', 'clasify-classified-listing' ),
			'THB'  => __( 'Thai Baht (&#3647;)', 'clasify-classified-listing' ),
			'INR'  => __( 'Indian Rupee (&#8377;)', 'clasify-classified-listing' ),
			'TRY'  => __( 'Turkish Lira (&#8378;)', 'clasify-classified-listing' ),
			'RIAL' => __( 'Iranian Rial (&#65020;)', 'clasify-classified-listing' ),
			'RUB'  => __( 'Russian Rubles', 'clasify-classified-listing' ),
			'AOA'  => __( 'Angolan Kwanza', 'clasify-classified-listing' ),
			'AED'  => __( 'United Arab Emirates dirham (د.إ)', 'clasify-classified-listing' ),
			'OMR'  => __( 'Omani Riyals (ر.ع.)', 'essential-wp-real-estate' ),
			'KES'  => __( 'Kenyan shilling (K)', 'clasify-classified-listing' ),
			'NGN'  => __( 'Nigerian naira (₦)', 'clasify-classified-listing' ),
			'GTQ'  => __( 'Guatemalan quetzal (Q)', 'clasify-classified-listing' ),
			'PKR'  => __( 'Pakistani Rupee (PKR)', 'essential-wp-real-estate' ),
			'BGN'  => __( 'Bulgarian lev (BGN)', 'essential-wp-real-estate' ),
		);

		return apply_filters( 'cl_admin_currencies', $currencies );
	}

	function cl_currency_symbol( $currency = '' ) {
		if ( empty( $currency ) ) {
			$currency = CCP()->common->options->cl_get_currency();
		}

		switch ( $currency ) :
			case 'GBP':
				$symbol = '&pound;';
				break;
			case 'BRL':
				$symbol = 'R&#36;';
				break;
			case 'EUR':
				$symbol = '&euro;';
				break;
			case 'USD':
			case 'AUD':
			case 'NZD':
			case 'CAD':
			case 'HKD':
			case 'MXN':
			case 'SGD':
				$symbol = '&#36;';
				break;
			case 'JPY':
				$symbol = '&yen;';
				break;
			case 'AOA':
				$symbol = 'Kz';
				break;
			case 'AED':
				$symbol = 'د.إ';
				break;
			case 'OMR':
				$symbol = 'ر.ع.';
				break;
			case 'KES':
				$symbol = 'K';
				break;
			case 'NGN':
				$symbol = '₦';
				break;
			case 'GTQ':
				$symbol = 'Q';
				break;
			case 'BGN':
				$symbol = 'Лв';
				break;
			default:
				$symbol = $currency;
				break;
		endswitch;

		return apply_filters( 'cl_currency_symbol', $symbol, $currency );
	}
}
