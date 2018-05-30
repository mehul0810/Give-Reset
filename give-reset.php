<?php
/**
 * @package Give Reset Settings
 *
 * Plugin Name: Give - Reset Settings
 * Description: Get fresh install of Give plugin in just a single click!
 * Version: 1.0.0
 * Author: Mehul Gohil
 * Author URI: https://www.mehulgohil.in/
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Display Admin Menu in admin bar.
 *
 * @param object $wp_admin_bar WP Admin Bar.
 *
 * @since 1.0.0
 *
 * @return bool
 */
function give_reset_display_admin_menu_callback( $wp_admin_bar ) {

	// Bail Out, if user has not access to manage options.
	if ( ! current_user_can( 'manage_options' ) ) {
		return false;
	}

	// Add the main site admin menu item.
	$wp_admin_bar->add_menu( array(
		'id'     => 'give-reset-main',
		'href'   => false,
		'parent' => 'top-secondary',
		'title'  => sprintf(
			/* translators: %s Admin Bar Menu Title */
			'<i class="dashicons dashicons-give"></i> %s',
			__( 'Give', 'give-reset' )
		),
		'meta'   => array(
			'class' => '',
		),
	) );

	$wp_admin_bar->add_menu( array(
		'parent' => 'give-reset-main',
		'id'     => 'give-reset-core-settings',
		'href'   => admin_url( '?give_action=reset_core_settings' ),
		'title'  => __( 'Core Settings', 'give-reset' ),
		'meta'   => array(
			'class' => '',
		),
	) );

	return true;
}

add_action( 'admin_bar_menu', 'give_reset_display_admin_menu_callback' );

/**
 * Callback to reset core settings on clicking the button in admin bar.
 *
 * @since 1.0.0
 */
function give_reset_core_settings_action_callback() {

	// Backup some required settings.
	$success_page = give_get_option( 'success_page' );
	$failure_page = give_get_option( 'failure_page' );
	$history_page = give_get_option( 'history_page' );
	$base_country = give_get_option( 'base_country' );
	$base_state   = give_get_option( 'base_state' );

	// Delete Give Settings Option.
	delete_option( 'give_settings' );

	delete_option( 'give_version' );

	// Restart Give Installation Process.
	give_install();

	// Restore the required Give settings.
	if ( ! empty( $success_page ) ) {
		give_update_option( 'success_page', $success_page );
	}

	if ( ! empty( $failure_page ) ) {
		give_update_option( 'failure_page', $failure_page );
	}

	if ( ! empty( $history_page ) ) {
		give_update_option( 'history_page', $history_page );
	}

	if ( ! empty( $base_country ) ) {
		give_update_option( 'base_country', $base_country );
	}

	if ( ! empty( $base_state ) ) {
		give_update_option( 'base_state', $base_state );
	}

	wp_safe_redirect( esc_url_raw( admin_url( 'index.php?page=give-about' ) ) );
	give_die();
}
add_action( 'give_reset_core_settings', 'give_reset_core_settings_action_callback' );

