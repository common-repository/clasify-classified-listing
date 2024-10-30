<?php
namespace Clasify\Classified\Common\PostTypes;

use Clasify\Classified\Traitval\Traitval;

class Assign {

	use Traitval;
	public function initialize() {
		add_filter( $this->plugin_pref . 'post_types', array( $this, 'initialize_cpt_default' ) );
		add_filter( $this->plugin_pref . 'taxonomies', array( $this, 'initialize_taxo_default' ) );
	}

	public function initialize_cpt_default( $post_types ) {
		$post_types[] = array(
			'name'         => 'cl_cpt',
			'slug'         => cl_admin_get_option( 'listing_slug' ) ? cl_admin_get_option( 'listing_slug' ) : 'listings',
			'singular'     => _x('Listings','taxonomy singular name', 'clasify-classified-listing' ),
			'plural'       => _x('Clasify Classified','taxonomy plural name', 'clasify-classified-listing' ),
			'dashicon'     => CLASIFY_CLASSIFIED_PLUGIN_ASSETS . ( '/img/ccl-icon.svg' ),
			'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments', 'revision', 'author' ,'custom-fields'),
			'show_in_menu' => true,
		);
		$post_types[] = array(
			'name'         => 'cl_payment',
			'slug'         => 'crazy-payments',
			'singular'     => _x('Payment','taxonomy singular name', 'clasify-classified-listing' ),
			'plural'       => _x('Payments','taxonomy plural name', 'clasify-classified-listing' ),
			'dashicon'     => CLASIFY_CLASSIFIED_PLUGIN_ASSETS . ( '/img/ccl-icon.svg' ),
			'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments', 'revision', 'author' ),
			'show_in_menu' => false,
		);
		$post_types[] = array(
			'name'         => 'cl_discount',
			'slug'         => 'crazy-discounts',
			'singular'     => _x('Discount','taxonomy singular name', 'clasify-classified-listing' ),
			'plural'       => _x('Discounts','taxonomy plural name', 'clasify-classified-listing' ),
			'dashicon'     => CLASIFY_CLASSIFIED_PLUGIN_ASSETS . ( '/img/ccl-icon.svg' ),
			'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments', 'revision', 'author' ),
			'show_in_menu' => false,
		);
		return $post_types;

	}
	
	public function initialize_taxo_default( $taxonomies ) {
		$taxonomies[] = array(
			'name'         => 'listings_category',
			'slug'         => 'listings_category',
			'singular'     => _x('Category','taxonomy singular name', 'clasify-classified-listing' ),
			'plural'       => _x('Categories','taxonomy plural name', 'clasify-classified-listing' ),
			'hierarchical' => true,
			'reg_cpt'      => array( 'cl_cpt' ),
		);
		$taxonomies[] = array(
			'name'         => 'listing_location',
			'slug'         => 'listing_location',
			'singular'     =>  _x('Location','taxonomy singular name', 'clasify-classified-listing' ),
			'plural'       =>  _x('Locations','taxonomy plural name', 'clasify-classified-listing' ),
			'hierarchical' => true,
			'reg_cpt'      => array( 'cl_cpt' ),
		);
		$taxonomies[] = array(
			'name'         => 'listing_status',
			'slug'         => 'listing_status',
			'singular'     => _x('Status','taxonomy singular name', 'clasify-classified-listing' ),
			'plural'       => _x('Status', 'taxonomy plural name', 'clasify-classified-listing' ),
			'hierarchical' => false,
			'reg_cpt'      => array( 'cl_cpt' ),
		);
		$taxonomies[] = array(
			'name'         => 'listing_conditions',
			'slug'         => 'listing_conditions',
			'singular'     => _x('Condition', 'taxonomy singular name', 'clasify-classified-listing' ),
			'plural'       => _x('Conditions', 'taxonomy plural name', 'clasify-classified-listing' ),
			'hierarchical' => false,
			'reg_cpt'      => array( 'cl_cpt' ),
		);
		$taxonomies[] = array(
			'name'              => 'listing_features',
			'slug'              => 'listing_features',
			'singular'          => _x('Feature', 'taxonomy singular name', 'clasify-classified-listing' ),
			'plural'            => _x('Features', 'taxonomy plural name', 'clasify-classified-listing' ),
			'hierarchical'      => false,
			'show_admin_column' => false,
			'reg_cpt'           => array( 'cl_cpt' ),
		);
		return $taxonomies;
	}
}
