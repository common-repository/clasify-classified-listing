<?php
namespace Clasify\Classified\Admin;

use Clasify\Classified\Admin\Menu\Menu;
use Clasify\Classified\Admin\MetaBoxes\MetaBoxes;
use Clasify\Classified\Traitval\Traitval;
use Clasify\Classified\Admin\Settings\Settings;
use Clasify\Classified\Admin\Settings\Archive;
use Clasify\Classified\Admin\Settings\Single;
use Clasify\Classified\Front\Purchase\Discount\DiscountAction;
use Clasify\Classified\Admin\Adminnotice\Adminnotice;

class Admin {

	use Traitval;

	public $menu_instance;
	public $settings_instances;
	public $listing_instance;
	public $archive_instance;
	public $discount_action;
	public $adminnotice;

	protected function initialize() {
		$this->define_constants();
		new MetaBoxes();
		$this->init_classes();
	}

	private function init_classes() {
		$this->menu_instance      = new Menu();
		$this->settings_instances = new Settings();
		$this->archive_instance   = new Archive();
		$this->single_instance    = new Single();
		$this->discount_action    = new DiscountAction();
		$this->adminnotice        = new Adminnotice();
	}
	private function define_constants() {
		define( 'CLASIFY_CLASSIFIED_PLUGIN_ADMIN_TEMPLATE_DIR', CLASIFY_CLASSIFIED_PLUGIN_TEMPLATES_DIR . '/admin' );
	}
}
