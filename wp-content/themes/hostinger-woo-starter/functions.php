<?php
/**
 * Theme functions for Hostinger Woo Starter.
 *
 * @package Hostinger_Woo_Starter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'HWS_VERSION', '1.0.0' );

/**
 * Register theme support and navigation areas.
 */
function hws_setup() {
	load_theme_textdomain( 'hostinger-woo-starter', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo', array( 'height' => 80, 'width' => 240, 'flex-width' => true ) );
	add_theme_support( 'html5', array( 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'align-wide' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/editor.css' );

	add_theme_support(
		'woocommerce',
		array(
			'thumbnail_image_width' => 480,
			'single_image_width'    => 900,
			'product_grid'          => array(
				'default_rows'    => 3,
				'min_rows'        => 1,
				'max_rows'        => 6,
				'default_columns' => 3,
				'min_columns'     => 1,
				'max_columns'     => 4,
			),
		)
	);
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'hostinger-woo-starter' ),
			'footer'  => __( 'Footer Menu', 'hostinger-woo-starter' ),
		)
	);
}
add_action( 'after_setup_theme', 'hws_setup' );

/**
 * Enqueue front-end assets.
 */
function hws_enqueue_assets() {
	wp_enqueue_style( 'hws-style', get_template_directory_uri() . '/assets/css/theme.css', array(), HWS_VERSION );
	wp_enqueue_script( 'hws-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array(), HWS_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'hws_enqueue_assets' );

/**
 * Register widget areas.
 */
function hws_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Footer Widgets', 'hostinger-woo-starter' ),
			'id'            => 'footer-widgets',
			'description'   => __( 'Add footer widgets here.', 'hostinger-woo-starter' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'hws_widgets_init' );

/**
 * Add a cart link to the primary menu when WooCommerce is active.
 *
 * @param string $items Menu HTML.
 * @param object $args  Menu arguments.
 * @return string
 */
function hws_add_cart_link_to_menu( $items, $args ) {
	if ( 'primary' !== $args->theme_location || ! class_exists( 'WooCommerce' ) ) {
		return $items;
	}

	$cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
	$items     .= sprintf(
		'<li class="menu-item hws-cart-link"><a href="%1$s">%2$s <span class="hws-cart-count">%3$d</span></a></li>',
		esc_url( wc_get_cart_url() ),
		esc_html__( 'Cart', 'hostinger-woo-starter' ),
		absint( $cart_count )
	);

	return $items;
}
add_filter( 'wp_nav_menu_items', 'hws_add_cart_link_to_menu', 10, 2 );
