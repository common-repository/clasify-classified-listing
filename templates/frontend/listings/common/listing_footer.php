<?php
add_action( 'wp_footer', 'clasify_classified_plugin_compare_func' );
add_action( 'wp_footer', 'clasify_classified_plugin_alert_func' );
function clasify_classified_plugin_compare_func() {
	$comp_url  = get_page_link( cl_admin_get_option( 'compare_page' ) );
	$comp_data = array();
	if ( isset( $_COOKIE['compare_listing_data'] ) ) {
		$comp_data = explode( ',', cl_sanitization( $_COOKIE['compare_listing_data'] ) );
		$comp_data = array_filter( $comp_data );
	};
	?>
	<div id="clasify-classified-plugin-compare-wrapper" class="clasify-classified-plugin-compare-wrapper">
		<div class="clasify-classified-plugin-compare-collapse-button">
			<a class="clasify-classified-plugin-collapse-btn" href="javascript:void(0)"><i class="fas fa-random"></i></a>
		</div>
		<div class="clasify-classified-plugin-compare-container">
			<h4 class="compare_title"><?php echo esc_html__( 'Compare Listings', 'clasify-classified-listing' ); ?></h4>

			<div class="clasify-classified-plugin-compare-items">
				<?php foreach ( $comp_data as $post ) { ?>
					<!-- Compare selected Item -->
					<div id="clasify-classified-plugin-compare-item<?php echo esc_attr( $post ); ?>" class="compare-listing-single">
						<div class="compare-item-img">
							<?php
							if ( has_post_thumbnail( $post ) ) {
								$alt = get_post_meta( $post, '_wp_attachment_image_alt', true );
								?>
								<img src="<?php echo esc_html( get_the_post_thumbnail_url( $post, 'thumbnail' ) ); ?>" alt="<?php echo esc_attr( $alt ); ?>">
							<?php } else { ?>
								<img src="<?php echo CLASIFY_CLASSIFIED_PLUGIN_ASSETS . '/img/placeholder_light.png'; ?>" alt="<?php esc_attr_e( 'Placeholder', 'clasify-classified-plugin' ); ?>">
							<?php } ?>
						</div>
						<div class="compare-item-content">
							<span class="item-title"><?php echo esc_html( get_the_title( $post ) ); ?></span>
							<a class="clasify-classified-plugin-compare-remove-btn" data-remove_compare_item="<?php echo esc_attr( $post ); ?>" href="javascript:void(0)" target="_blank"><i class="fas fa-trash-alt"></i></a>
						</div>
					</div>
				<?php } ?>
			</div>

			<div class="clasify-classified-plugin-compare-button">
				<a class="clasify-classified-plugin-compare-btn" href="<?php echo esc_url( $comp_url ); ?>" target="_blank"><?php echo esc_html__( 'Compare', 'clasify-classified-listing' ); ?></a>
			</div>
		</div>
	</div>
	<?php
}

function clasify_classified_plugin_alert_func() {
	?>
	<div class="clasify-classified-plugin-alart"></div>
	<?php
}
