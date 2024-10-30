<?php
// Loading Script for media load
wp_enqueue_media();
$key = '';
?>
<div class="form-group col-md-12">
	<div class="mb_gal__container" data-key="<?php echo esc_attr( $key ); ?>">
		<label for="<?php echo 'add_' . esc_attr( $args['id'] ); ?>"><?php echo esc_html( $args['field_data']['name'] ); ?></label>
		<div data-field_id="<?php echo esc_attr( $args['field_data']['id'] ); ?>" id="<?php echo esc_attr( $args['field_data']['id'] ) . $key . '_cont'; ?>" class="components-responsive-wrapper">
			<img id="<?php echo esc_attr( $args['field_data']['id'] ) . $key; ?>" class="cl_mb_placeholder" src="<?php echo esc_url( CLASIFY_CLASSIFIED_PLUGIN_ASSETS . '/img/placeholder.png' ); ?>" alt="<?php esc_attr_e( 'Placeholder', 'clasify-classified-plugin' ); ?>">
		</div>
		<div class="button_control">
			<button data-name="<?php echo esc_attr( $args['id'] ); ?>" id="<?php echo esc_attr( $args['field_data']['id'] ) . $key; ?>" type="button" class="mb_btn mb_img_upload_btn"><?php echo esc_html__( 'Upload Images', 'clasify-classified-listing' ); ?></button>
			<button data-placeholder="<?php echo esc_attr( CLASIFY_CLASSIFIED_PLUGIN_ASSETS . '/img/placeholder.png' ); ?>" id="<?php echo esc_attr( $args['field_data']['id'] ) . $key; ?>" type="button" class="mb_btn cl_mb_clear_btn"><?php echo esc_html__( 'Clear', 'clasify-classified-listing' ); ?></button>
		</div>
	</div>
</div>
