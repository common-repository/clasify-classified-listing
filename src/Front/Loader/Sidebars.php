<?php
namespace Clasify\Classified\Front\Loader;

use Clasify\Classified\Traitval\Traitval;

/**
 * Sidebar class loads everything related templates
 *
 * since 1.0.0
 */
class Sidebars {

	use Traitval;
	/**
	 * init_listing_sidebars
	 *
	 * @return void
	 */
	public function initialize() {
		add_action( 'widgets_init', array( $this, 'init_listing_sidebars' ) );
	}
	public function init_listing_sidebars() {
		register_sidebar(
			array(
				'name'          => __( 'Listing Sidebar', 'clasify-classified-listing' ),
				'id'            => 'listing-sidebar',
				'description'   => __( 'Widgets in this area will be shown on all posts and pages.', 'clasify-classified-listing' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h2 class="listing_title">',
				'after_title'   => '</h2>',
			)
		);
		register_sidebar(
			array(
				'name'          => __( 'Listing Single', 'clasify-classified-listing' ),
				'id'            => 'listing-single',
				'description'   => __( 'Widgets in this area will be shown on Listing Single.', 'clasify-classified-listing' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h2 class="listing_title">',
				'after_title'   => '</h2>',
			)
		);

		require_once CLASIFY_CLASSIFIED_PLUGIN_TEMPLATES_DIR . '/frontend/widgets/listing_sidebar_search.php';
		register_widget( 'Listing_Search_Widget' );
		require_once CLASIFY_CLASSIFIED_PLUGIN_TEMPLATES_DIR . '/frontend/widgets/listing_enquiry_form.php';
		register_widget( 'Listing_Enquiry_Widget' );
	}
}
