<?php
namespace Clasify\Classified\Common;

use Clasify\Classified\Common\Actions\Actions;
use Clasify\Classified\Common\PostTypes\PostTypes;
use Clasify\Classified\Front\Loader\Sidebars;
use Clasify\Classified\Common\Emails\Emails;
use Clasify\Classified\Common\Emails\Emailtags;
use Clasify\Classified\Common\Emails\Emailtemplate;
use Clasify\Classified\Common\PostTypes\Assign;
use Clasify\Classified\Common\Currencies\Currencies;
use Clasify\Classified\Common\Ajax\Ajax;
use Clasify\Classified\Traitval\Traitval;
use Clasify\Classified\Common\Options\Options;
use Clasify\Classified\Common\Customer\Dbcustomer;
use Clasify\Classified\Common\Customer\Customermeta;
use Clasify\Classified\Common\Formatting\Formatting;
use Clasify\Classified\Common\Roles\Roles;
use Clasify\Classified\Common\User\User;

class Common {

	use Traitval;

	public $posttypes_instance;
	public $assign_sidebars;
	public $assign_instance;
	public $emails;
	public $ajax_action;
	public $loggin_user;
	public $options;
	public $currencies;
	public $customer;
	public $formatting;
	public $roles;
	public $emailtemplate;
	public $user;

	protected function initialize() {
		$this->init_hooks();
	}

	public function init_hooks() {
		$this->posttypes_instance = PostTypes::getInstance();
		$this->actions            = Actions::getInstance();
		$this->assign_sidebars    = Sidebars::getInstance();
		$this->assign_instance    = Assign::getInstance();
		$this->emails             = Emails::getInstance();
		$this->emailtags          = Emailtags::getInstance();
		$this->loggin_user        = wp_get_current_user();
		$this->options            = Options::getInstance();
		$this->currencies         = Currencies::getInstance();
		$this->dbcustomer         = Dbcustomer::getInstance();
		$this->customermeta       = Customermeta::getInstance();
		$this->formatting         = Formatting::getInstance();
		$this->ajax_action        = Ajax::getInstance();
		$this->roles              = Roles::getInstance();
		$this->emailtemplate      = Emailtemplate::getInstance();
		$this->user               = User::getInstance();
	}
}
