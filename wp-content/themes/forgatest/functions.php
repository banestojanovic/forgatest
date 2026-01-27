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

add_action( 'wp_enqueue_scripts', 'ft_enqueue_vite_assets' );
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

if ( ! function_exists( 'pbt_init_sidebars' ) ) {
	function pbt_init_sidebars(): void {
//		register_sidebar( [
//			'name'          => esc_html__( 'Main Sidebar', 'forgatest' ),
//			'id'            => 'main-sidebar',
//			'description'   => esc_html__( 'Add widgets here to appear in your main sidebar.', 'forgatest' ),
//			'before_widget' => '<section id="%1$s" class="widget %2$s">',
//			'after_widget'  => '</section>',
//			'before_title'  => '<h2 class="widget-title">',
//			'after_title'   => '</h2>',
//		] );
	}

	add_action( 'widgets_init', 'pbt_init_sidebars' );
}

if ( ! function_exists( 'pbt_theme_setup' ) ) {
	function ft_theme_support(): void {
		add_theme_support( 'woocommerce' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'custom-logo' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'html5', [
			'comment-list',
			'comment-form',
			'search-form',
			'gallery',
			'caption',
		] );

		// Register navigation men
		register_nav_menus( [
			'primary' => __( 'Primary Menu', 'forgatest' ),
		] );
	}

	add_action( 'after_setup_theme', 'ft_theme_support' );
}

if ( ! function_exists( 'pbt_menu_builder' ) ) {
	function pbt_menu_builder( $menu_id = '' ): array {
		$menu  = pbt_get_nav_menu_items_by_location( $menu_id );
		$items = [];
		$tree  = [];

		if ( ! $menu ) {
			return [];
		}

		foreach ( $menu as $item ) {
			$items[ $item->ID ] = [
				'ID'       => url_to_postid( $item->url ),
				'title'    => $item->title,
				'url'      => $item->url,
				'children' => [],
				'parent'   => $item->menu_item_parent,
			];
		}

		foreach ( $items as $id => &$item ) {
			if ( $item['parent'] && isset( $items[ $item['parent'] ] ) ) {
				$items[ $item['parent'] ]['children'][] = &$item;
			} else {
				$tree[] = &$item;
			}
		}
		unset( $item );

		$remove_parent = function ( &$item ) use ( &$remove_parent ) {
			unset( $item['parent'] );
			foreach ( $item['children'] as &$child ) {
				$remove_parent( $child );
			}
		};
		foreach ( $tree as &$item ) {
			$remove_parent( $item );
		}

		return $tree;
	}
}


if ( ! function_exists( 'pbt_get_nav_menu_items_by_location' ) ) {
	function pbt_get_nav_menu_items_by_location( $location, $args = [] ): false|array {

		$locations = get_nav_menu_locations();

		if ( empty( $locations[ $location ] ) ) {
			return false;
		}

		$object = wp_get_nav_menu_object( $locations[ $location ] );

		return wp_get_nav_menu_items( $object->name, $args );
	}
}