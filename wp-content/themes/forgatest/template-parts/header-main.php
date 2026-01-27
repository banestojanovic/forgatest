<?php
/**
 * The template for displaying the main header.
 *
 * @package Carvetto
 * @version 1.0.0
 * @author pebas
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$logo = get_theme_mod( 'custom_logo' );
?>

<header class="bg-white border-b border-gray-100 shadow-app lg:mb-12">
    <div class="container mx-auto flex justify-between py-6 max-xl:px-4">
        <div>
            <a href="<?php echo esc_url( get_home_url( '/' ) ); ?>">
                <?php if ( ! empty( $logo ) ): ?>
                    <?php echo wp_get_attachment_image( $logo, 'full', false, array( 'class' => 'h-6 w-auto' ) ); ?>
                <?php else: ?>
                    <span><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
                <?php endif; ?>
            </a>
        </div>

        <nav>
            <div class="flex space-x-6 lg:hidden">
                <?php get_template_part( 'template-parts/main-nav-mobile' ); ?>
            </div>
            <div class="lg:flex space-x-6 hidden">
                <?php get_template_part( 'template-parts/main-nav' ); ?>
            </div>
        </nav>
    </div>
</header>
