<?php
namespace Clasify\Classified\Front;

use Clasify\Classified\Traitval\Traitval;
use Clasify\Classified\Front\Loader\TemplateHooks;
use Clasify\Classified\Front\Provider\ListingProvider;
use Clasify\Classified\Front\Provider\Query;
use Clasify\Classified\Front\Models\Listingsaction;
use Clasify\Classified\Front\Session\Session;
use Clasify\Classified\Front\Purchase\Cart\Cart;
use Clasify\Classified\Front\Purchase\Cart\Fees;
use Clasify\Classified\Front\Purchase\Tax\Tax;
use Clasify\Classified\Front\Error\Error;
use Clasify\Classified\Front\Purchase\Gateways\Gateways;
use Clasify\Classified\Front\Purchase\Checkout\Checkout;
use Clasify\Classified\Front\Country\Country;
use Clasify\Classified\Front\Purchase\Discount\Discount;
use Clasify\Classified\Front\Purchase\Discount\DiscountAction;
use Clasify\Classified\Front\Purchase\Gateways\PaypalStandard\PaypalStandard;
use Clasify\Classified\Front\Purchase\Gateways\Stripe\Stripe;
use Clasify\Classified\Front\Purchase\Gateways\Manual;

class Front {

	use Traitval;

	public $provider;
	public $listingsaction;
	public $error;
	public $template_hooks;
	public $query;
	public $session;
	public $checkout;
	public $gateways;
	public $cart;
	public $fees;
	public $tax;
	public $tokenizer;
	public $country;
	public $discount;
	public $paypalstandard;
	public $manual;

	protected function initialize() {
		$this->define_constants();
		$this->error            = Error::getInstance();
		$this->template_hooks   = TemplateHooks::getInstance();
		$this->listing_provider = ListingProvider::getInstance();
		$this->paypalstandard   = PaypalStandard::getInstance();
		$this->Stripe           = Stripe::getInstance();
		$this->listingsaction   = Listingsaction::getInstance();
		$this->query            = Query::getInstance();
		$this->session          = Session::getInstance();
		$this->gateways         = Gateways::getInstance();
		$this->checkout         = Checkout::getInstance();
		$this->fees             = Fees::getInstance();
		$this->tax              = Tax::getInstance();
		$this->country          = Country::getInstance();
		$this->cart             = Cart::getInstance();
		$this->discount         = discount::getInstance();
		$this->discountaction   = DiscountAction::getInstance();
		$this->manual           = Manual::getInstance();
	}

	private function define_constants() {
		define( 'CLASIFY_CLASSIFIED_PLUGIN_FRONT_TEMPLATE_DIR', CLASIFY_CLASSIFIED_PLUGIN_TEMPLATES_DIR . '/frontend' );
	}
}
