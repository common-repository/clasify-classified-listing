<?php
namespace Clasify\Classified\Common\PostTypes;

use Clasify\Classified\Traitval\Traitval;

class PostTypes {

	use Traitval;

	public function initialize() {
		add_action( 'init', array( $this, 'initialize_filters' ) );
		add_action( 'init', array( $this, 'initialize_post_types' ) );
		add_action( 'init', array( $this, 'initialize_taxonomies' ) );
		add_action( 'init', array( $this, 'register_statuses' ), 2 );
	}

	/**
	 * Get value of post type / Taxonomies and store in the current object.
	 */
	public function initialize_filters() {
		$this->cl_cpt_config = apply_filters( $this->plugin_pref . 'post_types', array() );
		$this->cl_tax_config = apply_filters( $this->plugin_pref . 'taxonomies', array() );
	}

	/**
	 * Generate Post Type from the current object
	 */
	public function initialize_post_types() {
		foreach ( $this->cl_cpt_config as $config ) {
			$cpt_name     = $config['name'];
			$cpt_slug     = $config['slug'];
			$cpt_singular = $config['singular'];
			$cpt_plural   = $config['plural'];
			$menu_icon    = $config['dashicon'] ?? 'dashicons-wordpress';
			$cpt_supports = array( 'title', 'editor', 'author', 'thumbnail', 'comments','custom-fields' );
			$show_in_menu = $config['show_in_menu'] ?? true;
			$labels       = array(
				'name'                  => _x( $cpt_singular, 'Plural Name of Clasify Classified Plugin listing', 'clasify-classified-listing' ),
				'singular_name'         => _x( $cpt_singular, 'Singular Name of Clasify Classified Plugin listing', 'clasify-classified-listing' ),
				'menu_name'             => __( $cpt_plural, 'clasify-classified-listing' ),
				'name_admin_bar'        => _x( $cpt_name, 'Add New on Toolbar', 'clasify-classified-listing' ),
				'archives'              => __( $cpt_singular . ' Archives', 'clasify-classified-listing' ),
				'attributes'            => __( $cpt_singular . ' Attributes', 'clasify-classified-listing' ),
				'parent_item_colon'     => __( 'Parent ' . $cpt_singular . ':', 'clasify-classified-listing' ),
				'all_items'             => __( 'All ' . $cpt_plural, 'clasify-classified-listing' ),
				'add_new_item'          => __( 'Add New ' . $cpt_singular, 'clasify-classified-listing' ),
				'add_new'               => __( 'Add New ' . $cpt_singular, 'clasify-classified-listing' ),
				'new_item'              => __( 'New ' . $cpt_singular, 'clasify-classified-listing' ),
				'edit_item'             => __( 'Edit ' . $cpt_singular, 'clasify-classified-listing' ),
				'update_item'           => __( 'Update ' . $cpt_singular, 'clasify-classified-listing' ),
				'view_item'             => __( 'View ' . $cpt_singular, 'clasify-classified-listing' ),
				'view_items'            => __( 'View ' . $cpt_plural, 'clasify-classified-listing' ),
				'search_items'          => __( 'Search ' . $cpt_singular, 'clasify-classified-listing' ),
				'not_found'             => __( 'Not found', 'clasify-classified-listing' ),
				'not_found_in_trash'    => __( 'Not found in Trash', 'clasify-classified-listing' ),
				'featured_image'        => __( 'Featured Image', 'clasify-classified-listing' ),
				'set_featured_image'    => __( 'Set featured image', 'clasify-classified-listing' ),
				'remove_featured_image' => __( 'Remove featured image', 'clasify-classified-listing' ),
				'use_featured_image'    => __( 'Use as featured image', 'clasify-classified-listing' ),
				'insert_into_item'      => __( 'Insert into ' . $cpt_singular, 'clasify-classified-listing' ),
				'uploaded_to_this_item' => __( 'Uploaded to this ' . $cpt_singular, 'clasify-classified-listing' ),
				'items_list'            => __( $cpt_plural . ' list', 'clasify-classified-listing' ),
				'items_list_navigation' => __( $cpt_plural . ' list navigation', 'clasify-classified-listing' ),
				'filter_items_list'     => __( 'Filter' . $cpt_plural . ' list', 'clasify-classified-listing' ),
			);
			$args         = array(
				'labels'             => $labels,
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => $show_in_menu,
				'menu_icon'          => $menu_icon,
				'menu_position'      => 5,
				'query_var'          => true,
				'rewrite'            => array(
					'slug'       => untrailingslashit( $cpt_slug ),
					'with_front' => false,
					'feeds'      => true,
				),
				'map_meta_cap'       => true,
				'has_archive'        => $cpt_slug,
				'hierarchical'       => false,
				'supports'           => $cpt_supports,
			);
			register_post_type( $cpt_name, $args );
		}
	}
	/**
	 * Generate Taxonoies from the current object
	 */
	public function initialize_taxonomies() {
		foreach ( $this->cl_tax_config as $config ) {
			$taxonomy_name     = $config['name'];
			$taxonomy_slug     = $config['slug'];
			$taxonomy_singular = $config['singular'];
			$taxonomy_plural   = $config['plural'];
			$reg_cpt           = $config['reg_cpt'];
			$hierarchical      = $config['hierarchical'] ?? 'false';
			$labels            = array(
				'name'              => _x( $taxonomy_plural, 'taxonomy general name', 'clasify-classified-listing' ),
				'singular_name'     => _x( $taxonomy_singular, 'taxonomy singular name', 'clasify-classified-listing' ),
				'search_items'      => __( 'Search ' . $taxonomy_singular, 'clasify-classified-listing' ),
				'all_items'         => __( 'All ' . $taxonomy_singular, 'clasify-classified-listing' ),
				'parent_item'       => __( 'Parent ' . $taxonomy_singular, 'clasify-classified-listing' ),
				'parent_item_colon' => __( 'Parent ' . $taxonomy_singular . ' :', 'clasify-classified-listing' ),
				'edit_item'         => __( 'Edit ' . $taxonomy_singular, 'clasify-classified-listing' ),
				'update_item'       => __( 'Update ' . $taxonomy_singular, 'clasify-classified-listing' ),
				'add_new_item'      => __( 'Add New ' . $taxonomy_singular, 'clasify-classified-listing' ),
				'new_item_name'     => __( 'New ' . $taxonomy_singular . ' Name', 'clasify-classified-listing' ),
				'menu_name'         => __( $taxonomy_plural, 'clasify-classified-listing' ),
			);
			$args              = array(
				'hierarchical'      => $hierarchical,
				'labels'            => $labels,
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array(
					'slug'       => $taxonomy_slug,
					'with_front' => false,
				),
			);
			register_taxonomy( $taxonomy_name, $reg_cpt, $args );
		}
	}

	/**
	 * Registers Custom Post Statuses which are used by the Payments and Discount
	 * Codes
	 *
	 * @since 1.0.9.1
	 * @return void
	 */
	public function register_statuses() {
		// Payment Statuses
		register_post_status(
			'refunded',
			array(
				'label'                     => _x( 'Refunded', 'Refunded payment status', 'clasify-classified-listing' ),
				'public'                    => true,
				'exclude_from_search'       => false,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				'label_count'               => _n_noop( 'Refunded <span class="count">(%s)</span>', 'Refunded <span class="count">(%s)</span>', 'clasify-classified-listing' ),
			)
		);
		register_post_status(
			'failed',
			array(
				'label'                     => _x( 'Failed', 'Failed payment status', 'clasify-classified-listing' ),
				'public'                    => true,
				'exclude_from_search'       => false,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				'label_count'               => _n_noop( 'Failed <span class="count">(%s)</span>', 'Failed <span class="count">(%s)</span>', 'clasify-classified-listing' ),
			)
		);
		register_post_status(
			'revoked',
			array(
				'label'                     => _x( 'Revoked', 'Revoked payment status', 'clasify-classified-listing' ),
				'public'                    => true,
				'exclude_from_search'       => false,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				'label_count'               => _n_noop( 'Revoked <span class="count">(%s)</span>', 'Revoked <span class="count">(%s)</span>', 'clasify-classified-listing' ),
			)
		);
		register_post_status(
			'abandoned',
			array(
				'label'                     => _x( 'Abandoned', 'Abandoned payment status', 'clasify-classified-listing' ),
				'public'                    => true,
				'exclude_from_search'       => false,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				'label_count'               => _n_noop( 'Abandoned <span class="count">(%s)</span>', 'Abandoned <span class="count">(%s)</span>', 'clasify-classified-listing' ),
			)
		);
		register_post_status(
			'processing',
			array(
				'label'                     => _x( 'Processing', 'Processing payment status', 'clasify-classified-listing' ),
				'public'                    => true,
				'exclude_from_search'       => false,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				'label_count'               => _n_noop( 'Processing <span class="count">(%s)</span>', 'Processing <span class="count">(%s)</span>', 'clasify-classified-listing' ),
			)
		);

		// Discount Code Statuses
		register_post_status(
			'active',
			array(
				'label'                     => _x( 'Active', 'Active discount code status', 'clasify-classified-listing' ),
				'public'                    => true,
				'exclude_from_search'       => false,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				'label_count'               => _n_noop( 'Active <span class="count">(%s)</span>', 'Active <span class="count">(%s)</span>', 'clasify-classified-listing' ),
			)
		);
		register_post_status(
			'inactive',
			array(
				'label'                     => _x( 'Inactive', 'Inactive discount code status', 'clasify-classified-listing' ),
				'public'                    => true,
				'exclude_from_search'       => false,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				'label_count'               => _n_noop( 'Inactive <span class="count">(%s)</span>', 'Inactive <span class="count">(%s)</span>', 'clasify-classified-listing' ),
			)
		);
	}
}
