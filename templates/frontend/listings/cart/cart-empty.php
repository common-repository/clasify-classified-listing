<li class="cart_item empty"><?php echo CCP()->front->cart->cl_empty_cart_message(); ?></li>
<?php if ( CCP()->front->tax->cl_use_taxes() ) : ?>
	<li class="cart_item cl-cart-meta cl_subtotal"><?php echo __( 'Subtotal:', 'clasify-classified-listing' ) . " <span class='subtotal'>" . CCP()->common->formatting->cl_currency_filter( CCP()->common->formatting->cl_format_amount( CCP()->front->cart->get_subtotal() ) ); ?></span></li>
	<li class="cart_item cl-cart-meta cl_cart_tax"><?php _e( 'Estimated Tax:', 'clasify-classified-listing' ); ?> <span class="cart-tax"><?php echo CCP()->common->formatting->cl_currency_filter( CCP()->common->formatting->cl_format_amount( CCP()->front->cart->get_tax() ) ); ?></span></li>
<?php endif; ?>
<li class="cart_item cl-cart-meta cl_total"><?php _e( 'Total:', 'clasify-classified-listing' ); ?> <span class="cart-total"><?php echo CCP()->common->formatting->cl_currency_filter( CCP()->common->formatting->cl_format_amount( CCP()->front->cart->get_total() ) ); ?></span></li>
<li class="cart_item cl_checkout"><a href="<?php echo cl_get_checkout_uri(); ?>"><?php _e( 'Checkout', 'clasify-classified-listing' ); ?></a></li>
