<?php
namespace Clasify\Classified\Common\Roles;

use Clasify\Classified\Traitval\Traitval;

class Roles {

	use Traitval;

	/**
	 * Get things going
	 *
	 * @since 1.4.4
	 */
	public function __construct() {
		add_filter( 'map_meta_cap', array( $this, 'meta_caps' ), 10, 4 );
		add_action( 'admin_init', array( $this, 'cl_install_roles_on_network' ) );
	}

	/**
	 * Add new shop roles with default WP caps
	 *
	 * @since 1.4.4
	 * @return void
	 */
	public function add_roles() {
		add_role(
			'shop_manager',
			__( 'Shop Manager', 'clasify-classified-listing' ),
			array(
				'read'                   => true,
				'edit_posts'             => true,
				'delete_posts'           => true,
				'unfiltered_html'        => true,
				'upload_files'           => true,
				'export'                 => true,
				'import'                 => true,
				'delete_others_pages'    => true,
				'delete_others_posts'    => true,
				'delete_pages'           => true,
				'delete_private_pages'   => true,
				'delete_private_posts'   => true,
				'delete_published_pages' => true,
				'delete_published_posts' => true,
				'edit_others_pages'      => true,
				'edit_others_posts'      => true,
				'edit_pages'             => true,
				'edit_private_pages'     => true,
				'edit_private_posts'     => true,
				'edit_published_pages'   => true,
				'edit_published_posts'   => true,
				'manage_categories'      => true,
				'manage_links'           => true,
				'moderate_comments'      => true,
				'publish_pages'          => true,
				'publish_posts'          => true,
				'read_private_pages'     => true,
				'read_private_posts'     => true,
			)
		);

		add_role(
			'shop_accountant',
			__( 'Shop Accountant', 'clasify-classified-listing' ),
			array(
				'read'         => true,
				'edit_posts'   => false,
				'delete_posts' => false,
			)
		);

		add_role(
			'shop_worker',
			__( 'Shop Worker', 'clasify-classified-listing' ),
			array(
				'read'         => true,
				'edit_posts'   => false,
				'upload_files' => true,
				'delete_posts' => false,
			)
		);

		add_role(
			'shop_vendor',
			__( 'Shop Vendor', 'clasify-classified-listing' ),
			array(
				'read'         => true,
				'edit_posts'   => false,
				'upload_files' => true,
				'delete_posts' => false,
			)
		);
	}

	/**
	 * Add new shop-specific capabilities
	 *
	 * @since  1.4.4
	 * @global WP_Roles $wp_roles
	 * @return void
	 */
	public function add_caps() {
		global $wp_roles;

		if ( class_exists( 'WP_Roles' ) ) {
			if ( ! isset( $wp_roles ) ) {
				$wp_roles = new WP_Roles();
			}
		}

		if ( is_object( $wp_roles ) ) {
			$wp_roles->add_cap( 'shop_manager', 'view_shop_reports' );
			$wp_roles->add_cap( 'shop_manager', 'view_shop_sensitive_data' );
			$wp_roles->add_cap( 'shop_manager', 'export_shop_reports' );
			$wp_roles->add_cap( 'shop_manager', 'manage_shop_settings' );
			$wp_roles->add_cap( 'shop_manager', 'manage_shop_discounts' );

			$wp_roles->add_cap( 'administrator', 'view_shop_reports' );
			$wp_roles->add_cap( 'administrator', 'view_shop_sensitive_data' );
			$wp_roles->add_cap( 'administrator', 'export_shop_reports' );
			$wp_roles->add_cap( 'administrator', 'manage_shop_discounts' );
			$wp_roles->add_cap( 'administrator', 'manage_shop_settings' );

			// Add the main post type capabilities
			$capabilities = $this->get_core_caps();
			foreach ( $capabilities as $cap_group ) {
				foreach ( $cap_group as $cap ) {
					$wp_roles->add_cap( 'shop_manager', $cap );
					$wp_roles->add_cap( 'administrator', $cap );
					$wp_roles->add_cap( 'shop_worker', $cap );
				}
			}

			$wp_roles->add_cap( 'shop_accountant', 'edit_products' );
			$wp_roles->add_cap( 'shop_accountant', 'read_private_products' );
			$wp_roles->add_cap( 'shop_accountant', 'view_shop_reports' );
			$wp_roles->add_cap( 'shop_accountant', 'export_shop_reports' );
			$wp_roles->add_cap( 'shop_accountant', 'edit_shop_payments' );

			$wp_roles->add_cap( 'shop_vendor', 'edit_product' );
			$wp_roles->add_cap( 'shop_vendor', 'edit_products' );
			$wp_roles->add_cap( 'shop_vendor', 'delete_product' );
			$wp_roles->add_cap( 'shop_vendor', 'delete_products' );
			$wp_roles->add_cap( 'shop_vendor', 'publish_products' );
			$wp_roles->add_cap( 'shop_vendor', 'edit_published_products' );
			$wp_roles->add_cap( 'shop_vendor', 'upload_files' );
			$wp_roles->add_cap( 'shop_vendor', 'assign_product_terms' );
		}
	}

	/**
	 * Gets the core post type capabilities
	 *
	 * @since  1.4.4
	 * @return array $capabilities Core post type capabilities
	 */
	public function get_core_caps() {
		$capabilities = array();

		$capability_types = array( 'product', 'shop_payment', 'shop_discount' );

		foreach ( $capability_types as $capability_type ) {
			$capabilities[ $capability_type ] = array(
				// Post type
				"edit_{$capability_type}",
				"read_{$capability_type}",
				"delete_{$capability_type}",
				"edit_{$capability_type}s",
				"edit_others_{$capability_type}s",
				"publish_{$capability_type}s",
				"read_private_{$capability_type}s",
				"delete_{$capability_type}s",
				"delete_private_{$capability_type}s",
				"delete_published_{$capability_type}s",
				"delete_others_{$capability_type}s",
				"edit_private_{$capability_type}s",
				"edit_published_{$capability_type}s",

				// Terms
				"manage_{$capability_type}_terms",
				"edit_{$capability_type}_terms",
				"delete_{$capability_type}_terms",
				"assign_{$capability_type}_terms",

				// Custom
				"view_{$capability_type}_stats",
				"import_{$capability_type}s",
			);
		}

		return $capabilities;
	}

	/**
	 * Map meta caps to primitive caps
	 *
	 * @since  2.0
	 * @return array $caps
	 */
	public function meta_caps( $caps, $cap, $user_id, $args ) {

		switch ( $cap ) {

			case 'view_product_stats':
				if ( empty( $args[0] ) ) {
					break;
				}

				$listing = get_post( $args[0] );
				if ( empty( $listing ) ) {
					break;
				}

				if ( user_can( $user_id, 'view_shop_reports' ) || $user_id == $listing->post_author ) {
					$caps = array();
				}

				break;
		}

		return $caps;
	}

	/**
	 * Remove core post type capabilities (called on uninstall)
	 *
	 * @since 1.5.2
	 * @return void
	 */
	public function remove_caps() {

		global $wp_roles;

		if ( class_exists( 'WP_Roles' ) ) {
			if ( ! isset( $wp_roles ) ) {
				$wp_roles = new WP_Roles();
			}
		}

		if ( is_object( $wp_roles ) ) {
			/** Shop Manager Capabilities */
			$wp_roles->remove_cap( 'shop_manager', 'view_shop_reports' );
			$wp_roles->remove_cap( 'shop_manager', 'view_shop_sensitive_data' );
			$wp_roles->remove_cap( 'shop_manager', 'export_shop_reports' );
			$wp_roles->remove_cap( 'shop_manager', 'manage_shop_discounts' );
			$wp_roles->remove_cap( 'shop_manager', 'manage_shop_settings' );

			/** Site Administrator Capabilities */
			$wp_roles->remove_cap( 'administrator', 'view_shop_reports' );
			$wp_roles->remove_cap( 'administrator', 'view_shop_sensitive_data' );
			$wp_roles->remove_cap( 'administrator', 'export_shop_reports' );
			$wp_roles->remove_cap( 'administrator', 'manage_shop_discounts' );
			$wp_roles->remove_cap( 'administrator', 'manage_shop_settings' );

			/** Remove the Main Post Type Capabilities */
			$capabilities = $this->get_core_caps();

			foreach ( $capabilities as $cap_group ) {
				foreach ( $cap_group as $cap ) {
					$wp_roles->remove_cap( 'shop_manager', $cap );
					$wp_roles->remove_cap( 'administrator', $cap );
					$wp_roles->remove_cap( 'shop_worker', $cap );
				}
			}

			/** Shop Accountant Capabilities */
			$wp_roles->remove_cap( 'shop_accountant', 'edit_products' );
			$wp_roles->remove_cap( 'shop_accountant', 'read_private_products' );
			$wp_roles->remove_cap( 'shop_accountant', 'view_shop_reports' );
			$wp_roles->remove_cap( 'shop_accountant', 'export_shop_reports' );

			/** Shop Vendor Capabilities */
			$wp_roles->remove_cap( 'shop_vendor', 'edit_product' );
			$wp_roles->remove_cap( 'shop_vendor', 'edit_products' );
			$wp_roles->remove_cap( 'shop_vendor', 'delete_product' );
			$wp_roles->remove_cap( 'shop_vendor', 'delete_products' );
			$wp_roles->remove_cap( 'shop_vendor', 'publish_products' );
			$wp_roles->remove_cap( 'shop_vendor', 'edit_published_products' );
			$wp_roles->remove_cap( 'shop_vendor', 'upload_files' );
		}
	}



	function cl_install_roles_on_network() {

		global $wp_roles;

		if ( ! is_object( $wp_roles ) ) {
			return;
		}

		if ( empty( $wp_roles->roles ) || ! array_key_exists( 'shop_manager', $wp_roles->roles ) ) {
			$this->add_roles();
			$this->add_caps();
		}
	}
}
