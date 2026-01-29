<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * EXAMPLE: Customizing WooCommerce Single Product Page
 */

// 1. Add-to-cart Handling
// We don't need to write code for simple vs variable products.
// WooCommerce's native function detects the product type and loads the right form.
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );

// 2. Injecting Custom Code Example
// We "hook" our custom function into the summary at priority 25 (before the button)
// We can use callbacks or anonymous functions.
add_action( 'woocommerce_single_product_summary', function () {
	echo '<div class="custom-message mb-4 text-blue-600">' . esc_html__( 'Free shipping over $50!', 'forgatest' ) . '</div>';
}, 25 );

// 3. Removing elements you don't want
// If you want to move the Meta (SKU/Categories) elsewhere:
// This will move it to the bottom of the product page.
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
add_action( 'woocommerce_after_single_product', 'woocommerce_template_single_meta', 10 );