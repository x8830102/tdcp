<?php
/**
 * The config for woocommerce
 *
 * @version    2.1.6
 */
 
if (class_exists( 'woocommerce' )) {
	global $alterna_options;
	
	global $woocommerce_loop;
	$woocommerce_loop['columns'] = 4;
	
	if(isset($alterna_options['shop-per-page'])){

		add_filter( 'loop_shop_per_page', 'new_loop_shop_per_page', 20 );

		function new_loop_shop_per_page( $cols ) {
		  global $alterna_options;
		  // $cols contains the current number of products per page based on the value stored on Options -> Reading
		  // Return the number of products you wanna show per page.
		  $cols = $alterna_options['shop-per-page'];
		  return $cols;
		}

	}
	
	
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-slider' );
	
	// Ensure cart contents update when products are added to the cart via AJAX (place the following in functions.php)
	add_filter('woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment');
	 
	function woocommerce_header_add_to_cart_fragment( $fragments ) {
		global $woocommerce;
		ob_start();
		?>
		<a class="cart-contents" href="<?php echo $woocommerce->cart->get_cart_url(); ?>" title="<?php _e('View your shopping cart', 'alterna'); ?>"><?php echo sprintf(_n('%d item', '%d items', $woocommerce->cart->cart_contents_count, 'alterna'), $woocommerce->cart->cart_contents_count);?> - <?php echo $woocommerce->cart->get_cart_total(); ?></a>
		<?php
		$fragments['a.cart-contents'] = ob_get_clean();
		return $fragments;
	}
}