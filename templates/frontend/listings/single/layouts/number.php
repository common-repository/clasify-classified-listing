<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$provider = CCP()->front->listing_provider;

if($args['id'] == 'clasify_classified_plugin_pricing'){
	$value    = $provider->get_meta_data( $args['id'], get_the_ID() );
	$value    = CCP()->common->formatting->cl_currency_filter( CCP()->common->formatting->cl_format_amount( $value ) );
}else{
	$value    = $provider->get_meta_data( $args['id'], get_the_ID() );
}

if ( ! empty( $value ) ) {
	echo '<div class="table-container"><div class="table-cell heading">' . esc_html( $args['name'] ) . '</div><div class="table-cell">' . esc_html( $value ) . '</div></div>';
}
