<?php
/**
 * The template for displaying the main navigation with mouseover and unlimited nesting.
 *
 * @package Carvetto
 * @version 1.1.0
 * @author pebas
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
};

if ( ! function_exists( 'ft_render_menu_item' ) ) :
    function ft_render_menu_item( $item, $is_submenu = false ): string {
        $menu_item_active_class     = get_the_ID() == $item['ID'] ? 'text-app' : '';
        $sub_menu_item_active_class = get_the_ID() == $item['ID'] ? 'text-app' : '';
        $wrapper_class              = 'flex items-center text-base';
        if ( $is_submenu ) {
            $wrapper_class .= ' -mx-2 px-2 py-1.5 justify-between text-base rounded font-normal hover:bg-primary/3';
        }

        ob_start();
        ?>
        <div x-data="{ open: false }"
             @mouseenter="open = true"
             @mouseleave="open = false"
             class="relative inline-flex">

            <!-- Menu Item -->
            <div class="flex flex-col w-full">
                <a href="<?php echo esc_url( $item['url'] ); ?>"
                   class="<?php echo esc_attr( $menu_item_active_class . ' ' . $wrapper_class . ' ' . ( $is_submenu ? $sub_menu_item_active_class : '' ) ); ?>"
                   role="menuitem"

                <span><?php echo esc_html( $item['title'] ); ?></span>

                <?php if ( ! empty( $item['children'] ) ): ?>
                    <svg class="ml-1 size-3.5" xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                              d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                              clip-rule="evenodd"/>
                    </svg>
                <?php endif; ?>
                </a>
            </div>

            <!-- Submenu wrapper -->
            <?php if ( ! empty( $item['children'] ) ): ?>
                <div x-show="open"
                     x-transition
                     class="absolute <?php echo esc_attr( $is_submenu ? 'left-full top-0' : 'right-0 top-4 pt-4' ); ?> z-10 min-w-44 origin-top-right"
                     role="menu"
                     aria-orientation="vertical"
                     aria-labelledby="menu-button"
                     tabindex="-1">
                    <div class="rounded-md bg-white shadow-lg ring-1 ring-border focus:outline-none">
                        <div class="p-4 flex flex-col" role="none">
                            <?php foreach ( $item['children'] as $child ): ?>
                                <?php echo ft_render_menu_item( $child, true ); ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <?php

        return ob_get_clean();
    }
endif;

$menu = ft_menu_builder( 'primary' );

foreach ( $menu as $item ) {
    echo ft_render_menu_item( $item );
}
?>