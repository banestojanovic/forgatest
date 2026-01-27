<?php
/**
 * ForgaTest functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Forga
 * @subpackage ForgaCommerce
 * @since ForgaCommerce 1.0
 */

if ( ! function_exists( 'ft_is_vite_dev_server_running' ) ) {
	function ft_is_vite_dev_server_running(): bool {
		return defined( 'FT_VITE_DEV' ) && FT_VITE_DEV === true;
	}
}

add_action('wp_enqueue_scripts', 'ft_enqueue_vite_assets');
function ft_enqueue_vite_assets(): void {
	$is_dev = ft_is_vite_dev_server_running();
	if ( $is_dev ) {
		wp_enqueue_script_module( 'ft-vite-client', 'https://forgatest.test:5173/@vite/client', [], null );
		wp_enqueue_script_module( 'ft-vite-main', 'https://forgatest.test:5173/resources/index.ts', [ 'ft-vite-client' ], null );
	} else {
		wp_enqueue_style( 'ft-main-style', get_stylesheet_directory_uri() . '/dist/style.css', [], '', '' );
		wp_enqueue_script_module( 'ft-vite-main', get_stylesheet_directory_uri() . '/dist/index.js', [], null );
	}
}