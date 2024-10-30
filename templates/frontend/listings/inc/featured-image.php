<?php
/**
 * Displays the featured image
 *
 * @package WordPress
 * @since 1.0.0
 */

$provider = CCP()->front->listing_provider;
$gallery  = $provider->get_meta_data( 'clasify_classified_plugin_gallery' );

if ( has_post_thumbnail() && $gallery ) {
	?>
	<!-- Slide Section -->
	<div class="listing-img-wrapper">
		<div class="list-img-slide">
			<div class="gallery-slider-active">
				<?php
				$provider->show_thumb();
				if ( $gallery ) {
					foreach ( $gallery as $value ) {
						echo wp_get_attachment_image( $value, array( '370', '230' ) );
					}
				}
				?>
			</div>
		</div>
	</div>
	<?php
} elseif ( has_post_thumbnail() ) {
	$provider->show_thumb();
} else {
	?>
	<!-- Placeholder Section -->
	<div class="listing-img-wrapper">
		<div class="list-img-slide">
			<div class="click">
				<img src="<?php echo CLASIFY_CLASSIFIED_PLUGIN_ASSETS . '/img/placeholder_light.png'; ?>" alt="<?php esc_attr_e( 'Placeholder', 'clasify-classified-plugin' ); ?>">
			</div>
		</div>
	</div>
	<?php
}
