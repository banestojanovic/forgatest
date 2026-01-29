<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'ft_is_vite_dev_server_running' ) ) {
	function ft_is_vite_dev_server_running(): bool {
		return defined( 'FT_VITE_DEV' ) && FT_VITE_DEV === true;
	}
}

if ( ! function_exists( 'ft_init_sidebars' ) ) {
	function ft_init_sidebars(): void {
	}

	add_action( 'widgets_init', 'ft_init_sidebars' );
}

if ( ! function_exists( 'ft_theme_setup' ) ) {
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

		register_nav_menus( [
			'primary' => __( 'Primary Menu', 'forgatest' ),
		] );
	}

	add_action( 'after_setup_theme', 'ft_theme_support' );
}

if ( ! function_exists( 'ft_menu_builder' ) ) {
	function ft_menu_builder( $menu_id = '' ): array {
		$menu  = ft_get_nav_menu_items_by_location( $menu_id );
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


if ( ! function_exists( 'ft_get_nav_menu_items_by_location' ) ) {
	function ft_get_nav_menu_items_by_location( $location, $args = [] ): false|array {

		$locations = get_nav_menu_locations();

		if ( empty( $locations[ $location ] ) ) {
			return false;
		}

		$object = wp_get_nav_menu_object( $locations[ $location ] );

		return wp_get_nav_menu_items( $object->name, $args );
	}
}

if ( ! function_exists( 'ft_enqueue_assets' ) ) {
	function ft_enqueue_assets(): void {
		$is_dev = ft_is_vite_dev_server_running();
		if ( $is_dev ) {
			wp_enqueue_script_module( 'ft-vite-client', 'https://forgatest.test:5173/@vite/client', [], null );
			wp_enqueue_script_module( 'ft-vite-main', 'https://forgatest.test:5173/resources/index.ts', [ 'ft-vite-client' ], null );
		} else {
			wp_enqueue_style( 'ft-main-style', get_stylesheet_directory_uri() . '/dist/style.css', [], '', '' );
			wp_enqueue_script_module( 'ft-vite-main', get_stylesheet_directory_uri() . '/dist/index.js', [], null );
		}
	}

	add_action( 'wp_enqueue_scripts', 'ft_enqueue_assets' );
}

if ( ! function_exists( 'ft_enqueue_admin_assets' ) ) {
	function ft_enqueue_admin_assets(): void {
	}

	add_action( 'admin_enqueue_scripts', 'ft_enqueue_admin_assets' );
}
