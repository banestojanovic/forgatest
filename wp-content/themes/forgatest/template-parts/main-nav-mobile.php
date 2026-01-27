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
?>

<div x-data="{
        slideOverOpen: false
    }"
     class="relative z-50 w-auto h-auto">
    <button @click="slideOverOpen=true"
            class="inline-flex justify-center items-center">
        <span class="sr-only"><?php esc_html_e( 'Open menu', 'vroomfox' ); ?></span>
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round"
             stroke-linejoin="round" class="">
            <path d="M4 5h16"/>
            <path d="M4 12h16"/>
            <path d="M4 19h16"/>
        </svg>
    </button>
    <template x-teleport="body">
        <div x-show="slideOverOpen"
             @keydown.window.escape="slideOverOpen=false"
             class="relative z-[99]">
            <div x-show="slideOverOpen" x-transition.opacity.duration.600ms @click="slideOverOpen = false" class="fixed inset-0 bg-black/10"></div>
            <div class="overflow-hidden fixed inset-0">
                <div class="overflow-hidden absolute inset-0">
                    <!-- Remove the pt-11 from the element below, this was needed only for the demo -->
                    <div class="flex fixed inset-y-0 right-0 pl-10 max-w-full <?php echo esc_attr( is_user_logged_in() ? 'pt-11' : '' ); ?>">
                        <div
                                x-show="slideOverOpen"
                                @click.away="slideOverOpen = false"
                                x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
                                x-transition:enter-start="translate-x-full"
                                x-transition:enter-end="translate-x-0"
                                x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
                                x-transition:leave-start="translate-x-0"
                                x-transition:leave-end="translate-x-full"
                                class="w-screen max-w-md">
                            <div class="flex overflow-y-scroll flex-col py-5 h-full bg-white border-l shadow-lg border-neutral-100/70">
                                <div class="px-4 sm:px-5">
                                    <div class="flex justify-between items-start pb-1">
                                        <h2 class="text-xl font-medium leading-6 text-foreground" id="slide-over-title"><?php esc_html_e( 'Main menu', 'vroomfox' ) ?></h2>
                                        <div class="flex items-center ml-3 h-auto">
                                            <button @click="slideOverOpen=false"
                                                    class="flex absolute right-0 z-30 justify-center items-center px-3 py-2 mt-6 mr-5 space-x-1 text-xs font-medium uppercase rounded-md border border-neutral-200 text-neutral-600 hover:bg-neutral-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                                <span><?php esc_html_e( 'Close', 'vroomfox' ) ?></span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="relative flex-1 px-4 mt-5 sm:px-5">
                                    <div class="absolute inset-0 px-4 sm:px-5">

                                        <!-- menu navigation -->
                                        <?php
                                        wp_nav_menu( [
                                                'theme_location' => 'primary',
                                                'container'      => false,
                                                'menu_class'     => 'space-y-1.5',
                                                'walker'         => new class extends Walker_Nav_Menu {
                                                    function start_lvl( &$output, $depth = 0, $args = null ) {
                                                        $indent = str_repeat( "\t", $depth );
                                                        $output .= "\n$indent<ul class=\"pl-5 space-y-1.5\">\n";
                                                    }

                                                    function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
                                                        $indent      = ( $depth ) ? str_repeat( "\t", $depth ) : '';
                                                        $classes     = empty( $item->classes ) ? [] : (array) $item->classes;
                                                        $classes[]   = 'flex text-sm font-normal uppercase hover:text-app transition-colors px-1 py-1.5';
                                                        if (in_array( 'current-menu-item', $classes, true ) ) {
                                                            $classes[] = 'text-app';
                                                        }
                                                        $class_names = join( ' ', array_filter( $classes ) );
                                                        $output      .= "$indent<li>";
                                                        $output      .= '<a href="' . esc_attr( $item->url ) . '" class="' . esc_attr( $class_names ) . '">';
                                                        $output      .= apply_filters( 'the_title', $item->title, $item->ID );
                                                        $output      .= '</a>';
                                                    }

                                                    function end_el( &$output, $item, $depth = 0, $args = null ) {
                                                        $output .= "</li>\n";
                                                    }

                                                    function end_lvl( &$output, $depth = 0, $args = null ) {
                                                        $indent = str_repeat( "\t", $depth );
                                                        $output .= "$indent</ul>\n";
                                                    }
                                                }
                                        ] );
                                        ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
