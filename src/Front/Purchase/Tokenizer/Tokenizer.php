<?php
namespace Clasify\Classified\Front\Purchase\Tokenizer;

class Tokenizer {

	/**
	 * @var mixed Data to tokenize.
	 */
	private $data;

	/**
	 * Tokenizer constructor.
	 *
	 * @param string|int|float $data Data to be tokenized.
	 */
	public function __construct( $data ) {
		$this->data = $data;
	}

	/**
	 * Retrieves the signing key.
	 *
	 * @since 2.11
	 *
	 * @return string
	 */
	private function get_signing_key() {
		$key = get_option( 'cl_tokenizer_signing_key' );
		if ( empty( $key ) ) {
			$key = $this->generate_and_save_signing_key();
		}

		return $key;
	}

	/**
	 * Generates and saves a new signing key.
	 *
	 * @since 2.11
	 *
	 * @return string
	 */
	private function generate_and_save_signing_key() {
		if ( function_exists( 'random_bytes' ) ) {
			try {
				$key = bin2hex( random_bytes( 32 ) );
			} catch ( \Exception $e ) {
				// If this failed for some reason, we'll generate using the fallback below.
			}
		}

		if ( empty( $key ) ) {
			$key = function_exists( 'openssl_random_pseudo_bytes' ) ? bin2hex( openssl_random_pseudo_bytes( 32 ) ) : md5( uniqid() );
		}

		update_option( 'cl_tokenizer_signing_key', $key );

		return $key;
	}

	/**
	 * Generates a token from the data.
	 *
	 * @since 2.11
	 *
	 * @return string|false
	 */
	public function generate_token() {
		return hash_hmac( 'sha256', $this->data, $this->get_signing_key() );
	}

	/**
	 * Determines whether or not the supplied token is valid for the
	 * supplied data.
	 *
	 * @since 2.11
	 *
	 * @param string           $token Token to check.
	 * @param string|int|float $data  Data that's been tokenized.
	 *
	 * @return bool
	 */
	public static function is_token_valid( $token, $data ) {
		$real_token = self::tokenize( $data );

		return hash_equals( $token, $real_token );
	}

	/**
	 * Generates a token for the supplied data.
	 *
	 * @since 2.11
	 *
	 * @param string|int|float $data
	 *
	 * @return string|false
	 */
	public static function tokenize( $data ) {
		$generator = new Tokenizer( $data );

		return $generator->generate_token();
	}
}
