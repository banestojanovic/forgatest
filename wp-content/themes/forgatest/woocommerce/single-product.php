<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * @package     ForgaTest
 * @subpackage  ForgaCommerce
 * @version     1.6.4
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header( 'shop' ); ?>

<?php while ( have_posts() ) : the_post(); ?>

    <?php
    global $product;
    $product = wc_get_product( get_the_ID() );
    ?>

    <div class="product-container container mx-auto py-6 max-xl:px-4">
        <?php
        /**
         * @hooked woocommerce_show_product_sale_flash - 10
         * @hooked woocommerce_show_product_images - 20
         */
        do_action( 'woocommerce_before_single_product' ); ?>

        <article id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>
            <?php wc_get_template_part( 'content', 'single-product' ); ?>
        </article>

        <?php do_action( 'woocommerce_after_single_product' ); ?>
    </div>

<?php endwhile; ?>

<?php get_footer( 'shop' );