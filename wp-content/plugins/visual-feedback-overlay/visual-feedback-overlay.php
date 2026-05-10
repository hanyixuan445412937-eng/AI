<?php
/**
 * Plugin Name: Visual Feedback Overlay
 * Description: Adds an admin-only front-end annotation layer for visual QA notes during WordPress/WooCommerce previews.
 * Version: 1.0.0
 * Author: AI Build Assistant
 * Requires at least: 6.4
 * Requires PHP: 8.1
 * Text Domain: visual-feedback-overlay
 * License: GPL-2.0-or-later
 *
 * @package Visual_Feedback_Overlay
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'VFO_VERSION', '1.0.0' );
define( 'VFO_OPTION', 'vfo_annotations' );

/**
 * Register REST routes for annotations.
 */
function vfo_register_routes() {
	register_rest_route(
		'vfo/v1',
		'/annotations',
		array(
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => 'vfo_get_annotations',
				'permission_callback' => 'vfo_can_annotate',
			),
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => 'vfo_create_annotation',
				'permission_callback' => 'vfo_can_annotate',
			),
			array(
				'methods'             => WP_REST_Server::DELETABLE,
				'callback'            => 'vfo_clear_annotations',
				'permission_callback' => 'vfo_can_annotate',
			),
		)
	);
}
add_action( 'rest_api_init', 'vfo_register_routes' );

/**
 * Check if current user can annotate.
 *
 * @return bool
 */
function vfo_can_annotate() {
	return current_user_can( 'edit_theme_options' );
}

/**
 * Return saved annotations for current URL path.
 *
 * @param WP_REST_Request $request Request object.
 * @return WP_REST_Response
 */
function vfo_get_annotations( WP_REST_Request $request ) {
	$path        = vfo_normalize_path( $request->get_param( 'path' ) );
	$annotations = get_option( VFO_OPTION, array() );

	return rest_ensure_response( isset( $annotations[ $path ] ) ? array_values( $annotations[ $path ] ) : array() );
}

/**
 * Create an annotation.
 *
 * @param WP_REST_Request $request Request object.
 * @return WP_REST_Response
 */
function vfo_create_annotation( WP_REST_Request $request ) {
	$path = vfo_normalize_path( $request->get_param( 'path' ) );
	$body = $request->get_json_params();

	$annotation = array(
		'id'        => wp_generate_uuid4(),
		'path'      => $path,
		'x'         => isset( $body['x'] ) ? min( 100, max( 0, (float) $body['x'] ) ) : 0,
		'y'         => isset( $body['y'] ) ? min( 100, max( 0, (float) $body['y'] ) ) : 0,
		'note'      => isset( $body['note'] ) ? sanitize_textarea_field( $body['note'] ) : '',
		'viewport'  => isset( $body['viewport'] ) ? sanitize_text_field( $body['viewport'] ) : '',
		'createdBy' => wp_get_current_user()->display_name,
		'createdAt' => current_time( 'mysql' ),
	);

	if ( '' === $annotation['note'] ) {
		return new WP_Error( 'vfo_empty_note', __( 'Annotation note cannot be empty.', 'visual-feedback-overlay' ), array( 'status' => 400 ) );
	}

	$annotations            = get_option( VFO_OPTION, array() );
	$annotations[ $path ][] = $annotation;
	update_option( VFO_OPTION, $annotations, false );

	return rest_ensure_response( $annotation );
}

/**
 * Clear annotations for current URL path.
 *
 * @param WP_REST_Request $request Request object.
 * @return WP_REST_Response
 */
function vfo_clear_annotations( WP_REST_Request $request ) {
	$path        = vfo_normalize_path( $request->get_param( 'path' ) );
	$annotations = get_option( VFO_OPTION, array() );
	unset( $annotations[ $path ] );
	update_option( VFO_OPTION, $annotations, false );

	return rest_ensure_response( array( 'cleared' => true ) );
}

/**
 * Normalize URL paths used as annotation buckets.
 *
 * @param string|null $path URL path.
 * @return string
 */
function vfo_normalize_path( $path ) {
	$path = is_string( $path ) ? wp_parse_url( $path, PHP_URL_PATH ) : '/';
	$path = $path ? sanitize_text_field( $path ) : '/';

	return '/' . ltrim( $path, '/' );
}

/**
 * Enqueue overlay assets only for capable logged-in users on front-end pages.
 */
function vfo_enqueue_assets() {
	if ( is_admin() || ! vfo_can_annotate() ) {
		return;
	}

	wp_enqueue_style( 'vfo-overlay', plugin_dir_url( __FILE__ ) . 'assets/css/overlay.css', array(), VFO_VERSION );
	wp_enqueue_script( 'vfo-overlay', plugin_dir_url( __FILE__ ) . 'assets/js/overlay.js', array(), VFO_VERSION, true );
	wp_localize_script(
		'vfo-overlay',
		'vfoSettings',
		array(
			'restUrl' => esc_url_raw( rest_url( 'vfo/v1/annotations' ) ),
			'nonce'   => wp_create_nonce( 'wp_rest' ),
			'path'    => vfo_normalize_path( isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '/' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'vfo_enqueue_assets' );
