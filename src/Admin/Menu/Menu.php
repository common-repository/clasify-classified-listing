<?php
namespace Clasify\Classified\Admin\Menu;

use Clasify\Classified\Admin\Settings\Settings;
use Clasify\Classified\Admin\Settings\Discounts\Discount_Codes;
use Clasify\Classified\Admin\Settings\Payment\Payment;

use Clasify\Classified\Traitval\Traitval;

/**
 * The admin class
 */
class Menu {


	use Traitval;
	/**
	 * Initialize the class
	 */
	public $settingclass;
	public function __construct() {
		require_once CLASIFY_CLASSIFIED_PLUGIN_SRC_PATH . '/Admin/Settings/Discounts/discount-actions.php';
		$this->settingclass = Settings::getInstance();
		add_action( 'admin_menu', array( $this, 'listing_settings' ) );
		add_action( 'admin_init', array( $this->settingclass, 'cl_register_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'listing_admin_enqueue_scripts' ) );
		add_filter( 'manage_cl_cpt_posts_columns', array( $this, 'set_custom_edit_cl_cpt_columns' ) );

		add_filter( 'manage_edit-cl_cpt_sortable_columns', array( $this, 'set_custom_edit_cl_cpt_sortable_columns' ) );
		add_action( 'manage_cl_cpt_posts_custom_column', array( $this, 'custom_cl_cpt_column' ), 10, 2 );
		add_action( 'admin_bar_menu', array( $this, 'admin_bar_item' ), 500 );
	}

	public function admin_bar_item( \WP_Admin_Bar $admin_bar ) {

		$url  = add_query_arg( array( 'post_type' => $this->cl_cpt ), admin_url( 'edit.php' ) );
		$args = array(
			'id'    => $this->cl_cpt,
			'title' => esc_html__( 'Clasify Classified', 'clasify-classified-listing' ),
			'href'  => $url,
		);
		$admin_bar->add_menu( $args );

		$url  = add_query_arg( array( 'post_type' => $this->cl_cpt ), admin_url( 'post-new.php' ) );
		$args = array(
			'id'     => $this->cl_cpt . '_add_new',
			'title'  => esc_html__( 'Add New Clasify Classified', 'clasify-classified-listing' ),
			'href'   => $url,
			'parent' => $this->cl_cpt,
		);
		$admin_bar->add_menu( $args );

		$cl_tax_config = CCP()->common->assign_instance->initialize_taxo_default( array() );
		foreach ( $cl_tax_config as $cl_tax ) {
			$url  = add_query_arg(
				array(
					'post_type' => $this->cl_cpt,
					'taxonomy'  => $cl_tax['slug'],
				),
				admin_url( 'edit-tags.php' )
			);
			$args = array(
				'id'     => $this->cl_cpt . $cl_tax['slug'],
				'title'  => esc_html__( $cl_tax['plural'], 'clasify-classified-listing' ),
				'href'   => $url,
				'parent' => $this->cl_cpt,
			);
			$admin_bar->add_menu( $args );
		}

		$url  = add_query_arg(
			array(
				'post_type' => $this->cl_cpt,
				'page'      => 'listing_settings_func',
			),
			admin_url( 'edit.php' )
		);
		$args = array(
			'id'     => $this->cl_cpt . '_settings',
			'title'  => esc_html__( 'Settings', 'clasify-classified-listing' ),
			'href'   => $url,
			'parent' => $this->cl_cpt,
		);
		$admin_bar->add_menu( $args );

		$url  = add_query_arg(
			array(
				'post_type' => $this->cl_cpt,
				'page'      => 'listing_supports',
			),
			admin_url( 'edit.php' )
		);
		$args = array(
			'id'     => $this->cl_cpt . '_supports',
			'title'  => esc_html__( 'Supports', 'clasify-classified-listing' ),
			'href'   => $url,
			'parent' => $this->cl_cpt,
		);
		$admin_bar->add_menu( $args );

		$url  = '#';
		$args = array(
			'id'     => $this->cl_cpt . '_clear_cache',
			'title'  => esc_html__( 'Clear Cache', 'clasify-classified-listing' ),
			'href'   => $url,
			'parent' => $this->cl_cpt,
			'meta'   => array(
				'class' => 'listing_clear_cache', // This title will show on hover
			),
		);
		$admin_bar->add_menu( $args );
	}

	public function set_custom_edit_cl_cpt_sortable_columns( $columns ) {
		$columns['report_abuse'] = 'report_abuse';
		return $columns;
	}

	public function set_custom_edit_cl_cpt_columns( $columns ) {
		$date     = $columns['date'];
		$comments = $columns['comments'];
		unset( $columns['comments'] );
		unset( $columns['date'] );
		unset( $columns['report_abuse'] );
		unset( $columns['feature'] );
		// $columns['report_abuse'] = __( 'Report Abuse', 'clasify-classified-listing' );
		$columns['comments'] = $comments;
		$columns['date']     = $date;
		return $columns;
	}
	public function custom_cl_cpt_column( $column, $post_id ) {
		switch ( $column ) {
			case 'report_abuse':
				$get_abuse = get_post_meta( $post_id, 'listing_abuse_report_by_visitor', true );
				if ( is_numeric( $get_abuse ) ) {
					echo esc_html( $get_abuse );
				} else {
					echo '&mdash;';
				}
				break;
		}
	}


	public function listing_admin_enqueue_scripts() {
		wp_enqueue_media();

		if ( isset( $_GET['tab'] ) && isset( $_GET['section'] ) ) {
			if ( $_GET['tab'] == 'pagelayout' && ( $_GET['section'] == 'archive' || $_GET['section'] == 'archive_list' || $_GET['section'] == 'single' || $_GET['section'] == 'search' || $_GET['section'] == 'add' || $_GET['section'] == 'comp_field_list' || $_GET['section'] == 'custom_field' ) ) {
				wp_enqueue_script( $this->prefix . 'sortable-script', CLASIFY_CLASSIFIED_PLUGIN_ASSETS . ( '/js/sortable.js' ), array( 'jquery-ui-draggable', 'jquery-ui-droppable', 'jquery-ui-sortable' ), time(), false );
				wp_enqueue_style( $this->prefix . 'styles', CLASIFY_CLASSIFIED_PLUGIN_ASSETS . ( '/css/styles.css' ) );

				if ( $_GET['section'] == 'archive' || $_GET['section'] == 'archive_list' ) {
					wp_enqueue_style( $this->prefix . 'listing-settings-archive', CLASIFY_CLASSIFIED_PLUGIN_ASSETS . ( '/css/pages/listing_settings_archive.css' ) );
				}

				if ( $_GET['section'] == 'single' ) {
					wp_enqueue_style( $this->prefix . 'listing-settings-single', CLASIFY_CLASSIFIED_PLUGIN_ASSETS . ( '/css/pages/listing_settings_single.css' ) );
				}
			}
		} elseif ( isset( $_GET['tab'] ) && $_GET['tab'] == 'pagelayout' && ! isset( $_GET['section'] ) ) {
			wp_enqueue_script( $this->prefix . 'sortable-script', CLASIFY_CLASSIFIED_PLUGIN_ASSETS . ( '/js/sortable.js' ), array( 'jquery-ui-draggable', 'jquery-ui-droppable', 'jquery-ui-sortable' ), time(), false );
			wp_enqueue_style( $this->prefix . 'styles', CLASIFY_CLASSIFIED_PLUGIN_ASSETS . ( '/css/styles.css' ) );
			wp_enqueue_style( $this->prefix . 'listing-settings-single', CLASIFY_CLASSIFIED_PLUGIN_ASSETS . ( '/css/pages/listing_settings_single.css' ) );
		}
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'jquery-ui-dialog' );
		wp_enqueue_style( 'wp-jquery-ui-dialog' );
		wp_enqueue_script( $this->prefix . 'metabox-script', CLASIFY_CLASSIFIED_PLUGIN_ASSETS . ( '/js/custom.js' ), array(), time(), false );
	}

	/**
	 * * Custom Post Type Menu Options
	 */

	public function listing_settings() {
		$payment = new Payment();
		add_submenu_page(
			'edit.php?post_type=cl_cpt',
			__( 'Settings', 'clasify-classified-listing' ),
			__( 'Settings', 'clasify-classified-listing' ),
			'manage_options',
			'listing_settings_func',
			array( $this->settingclass, 'initialize' )
		);

		add_submenu_page(
			'edit.php?post_type=cl_cpt',
			__( 'Discounts', 'clasify-classified-listing' ),
			__( 'Discounts', 'clasify-classified-listing' ),
			'manage_options',
			'cl_discounts',
			array( $this, 'listing_discounts_func' )
			// 'edit.php?post_type=cl_discount'
		);

		add_submenu_page(
			'edit.php?post_type=cl_cpt',
			__( 'Payments', 'clasify-classified-listing' ),
			__( 'Payments', 'clasify-classified-listing' ),
			'manage_options',
			'cl-payment-history',
			array( $payment, 'cl_payment_history_page' )
		);
	}

	public function listing_discounts_func() {
		$discount_codes = new Discount_Codes();
		$discount_codes->prepare_items();

		if ( isset( $_GET['cl-action'] ) && $_GET['cl-action'] == 'edit_discount' ) {
			require_once CLASIFY_CLASSIFIED_PLUGIN_SRC_PATH . '/Admin/Settings/Discounts/edit-discount.php';
		} elseif ( isset( $_GET['cl-action'] ) && $_GET['cl-action'] == 'add_discount' ) {
			require_once CLASIFY_CLASSIFIED_PLUGIN_SRC_PATH . '/Admin/Settings/Discounts/add-discount.php';
		} else { ?>
			<div class="wrap">
				<h1><?php _e( 'Discount Codes', 'clasify-classified-listing' ); ?><a href="<?php echo esc_url( add_query_arg( array( 'cl-action' => 'add_discount' ) ) ); ?>" class="add-new-h2"><?php _e( 'Add New', 'clasify-classified-listing' ); ?></a></h1>
				<?php do_action( 'cl_discounts_page_top' ); ?>
				<form id="cl-discounts-filter" method="get" action="<?php echo admin_url( 'edit.php?post_type=cl_cpt&page=cl_discounts' ); ?>">
					<?php $discount_codes->search_box( __( 'Search', 'clasify-classified-listing' ), 'cl-discounts' ); ?>

					<input type="hidden" name="post_type" value="cl_cpt" />
					<input type="hidden" name="page" value="cl_discounts" />

					<?php $discount_codes->views(); ?>
					<?php $discount_codes->display(); ?>
				</form>
				<?php do_action( 'cl_discounts_page_bottom' ); ?>
			</div>
			<?php
		}
	}
}
