<?php
/**
 * AJAX Functions.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Get AJAX URL
*/
function newsletterglue_get_ajax_url() {
	$scheme = defined( 'FORCE_SSL_ADMIN' ) && FORCE_SSL_ADMIN ? 'https' : 'admin';

	$current_url = newsletterglue_get_current_page_url();
	$ajax_url    = admin_url( 'admin-ajax.php', $scheme );

	if ( preg_match( '/^https/', $current_url ) && ! preg_match( '/^https/', $ajax_url ) ) {
		$ajax_url = preg_replace( '/^http/', 'https', $ajax_url );
	}

	return apply_filters( 'newsletterglue_get_ajax_url', $ajax_url );
}

/**
 * API connection.
 */
function newsletterglue_ajax_connect_api() {

	check_ajax_referer( 'newsletterglue-ajax-nonce', 'security' );

	if ( ! current_user_can( 'manage_newsletterglue' ) ) {
		wp_die( -1 );
	}

	// Get service.
	$service = isset( $_POST['service'] ) ? sanitize_text_field( $_POST['service'] ) : '';

	if ( ! in_array( $service, array_keys( newsletterglue_get_supported_providers() ) ) ) {
		wp_die( -1 );
	}

	include_once NGL_PLUGIN_DIR . 'includes/integrations/' . $service . '/init.php';

	$classname 	= 'NGL_' . ucfirst( $service );
	$api		= new $classname();
	$result 	= $api->add_integration();

	wp_send_json( $result );

	die();
}
add_action( 'wp_ajax_newsletterglue_ajax_connect_api', 'newsletterglue_ajax_connect_api' );
add_action( 'wp_ajax_nopriv_newsletterglue_ajax_connect_api', 'newsletterglue_ajax_connect_api' );

/**
 * Remove API Integration.
 */
function newsletterglue_ajax_remove_api() {

	check_ajax_referer( 'newsletterglue-ajax-nonce', 'security' );

	if ( ! current_user_can( 'manage_newsletterglue' ) ) {
		wp_die( -1 );
	}

	// Get service.
	$service = isset( $_POST['service'] ) ? sanitize_text_field( $_POST['service'] ) : '';

	if ( ! in_array( $service, array_keys( newsletterglue_get_supported_providers() ) ) ) {
		wp_die( -1 );
	}

	include_once NGL_PLUGIN_DIR . 'includes/integrations/' . $service . '/init.php';

	$classname 	= 'NGL_' . ucfirst( $service );
	$api		= new $classname();
	$result 	= $api->remove_integration();

	wp_send_json( $result );

	die();
}
add_action( 'wp_ajax_newsletterglue_ajax_remove_api', 'newsletterglue_ajax_remove_api' );
add_action( 'wp_ajax_nopriv_newsletterglue_ajax_remove_api', 'newsletterglue_ajax_remove_api' );

/**
 * Reset a sent newsletter.
 */
function newsletterglue_ajax_reset_newsletter() {

	check_ajax_referer( 'newsletterglue-ajax-nonce', 'security' );

	if ( ! current_user_can( 'manage_newsletterglue' ) ) {
		wp_die( -1 );
	}

	$post_id = isset( $_REQUEST[ 'post_id' ] ) ? absint( $_REQUEST[ 'post_id' ] ) : '';

	if ( $post_id ) {
		newsletterglue_reset_newsletter( $post_id );
	}

	die();
}
add_action( 'wp_ajax_newsletterglue_ajax_reset_newsletter', 'newsletterglue_ajax_reset_newsletter' );
add_action( 'wp_ajax_nopriv_newsletterglue_ajax_reset_newsletter', 'newsletterglue_ajax_reset_newsletter' );

/**
 * Removes a notice.
 */
function newsletterglue_ajax_remove_notice() {
	if ( ! current_user_can( 'manage_newsletterglue' ) ) {
		wp_die( -1 );
	}

	$key = isset( $_REQUEST[ 'key' ] ) ? absint( $_REQUEST[ 'key' ] ) : '';

	newsletterglue_remove_notice( $key );

	die();

}
add_action( 'wp_ajax_newsletterglue_ajax_remove_notice', 'newsletterglue_ajax_remove_notice' );
add_action( 'wp_ajax_nopriv_newsletterglue_ajax_remove_notice', 'newsletterglue_ajax_remove_notice' );

/**
 * Test a newsletter.
 */
function newsletterglue_ajax_test_email() {

	check_ajax_referer( 'newsletterglue-ajax-nonce', 'security' );

	if ( ! current_user_can( 'manage_newsletterglue' ) ) {
		wp_die( -1 );
	}

	$post_id = isset( $_REQUEST[ 'post_id' ] ) ? absint( $_REQUEST[ 'post_id' ] ) : '';

	// Save newsletter data.
	newsletterglue_save_data( $post_id, $_REQUEST );

	// Send it.
	$response = newsletterglue_send( $post_id, true );

	wp_send_json( $response );

	die();
}
add_action( 'wp_ajax_newsletterglue_ajax_test_email', 'newsletterglue_ajax_test_email' );
add_action( 'wp_ajax_nopriv_newsletterglue_ajax_test_email', 'newsletterglue_ajax_test_email' );

/**
 * Verify email address.
 */
function newsletterglue_ajax_verify_email() {

	check_ajax_referer( 'newsletterglue-ajax-nonce', 'security' );

	if ( ! current_user_can( 'manage_newsletterglue' ) ) {
		wp_die( -1 );
	}

	$email 		= isset( $_REQUEST[ 'email' ] ) ? $_REQUEST[ 'email' ] : '';
	$service 	= isset( $_REQUEST['service'] ) ? sanitize_text_field( $_REQUEST['service'] ) : '';

	if ( ! in_array( $service, array_keys( newsletterglue_get_supported_providers() ) ) ) {
		wp_die( -1 );
	}

	include_once NGL_PLUGIN_DIR . 'includes/integrations/' . $service . '/init.php';

	$classname 	= 'NGL_' . ucfirst( $service );
	$api		= new $classname();
	$result 	= $api->verify_email( $email );

	wp_send_json( $result );

}
add_action( 'wp_ajax_newsletterglue_ajax_verify_email', 'newsletterglue_ajax_verify_email' );
add_action( 'wp_ajax_nopriv_newsletterglue_ajax_verify_email', 'newsletterglue_ajax_verify_email' );

/**
 * Save Field.
 */
function newsletterglue_ajax_save_field() {

	check_ajax_referer( 'newsletterglue-ajax-nonce', 'security' );

	if ( ! current_user_can( 'manage_newsletterglue' ) ) {
		wp_die( -1 );
	}

	$id 	= isset( $_REQUEST[ 'id' ] ) ? str_replace( 'ngl_', '', $_REQUEST[ 'id' ] ) : '';
	$value 	= isset( $_REQUEST['value'] ) ? trim( $_REQUEST['value'] ) : '';
	$app 	= newsletterglue_default_connection();

	$options = get_option( 'newsletterglue_options' );
	$result  = array();

	if ( $id == 'credits' ) {

		update_option( 'newsletterglue_credits', $value );

	} else if ( $id == 'custom_css' ) {

		update_option( 'newsletterglue_css', $value );

	} else if ( $id == 'from_email' ) {

		include_once NGL_PLUGIN_DIR . 'includes/integrations/' . $app . '/init.php';

		$classname 	= 'NGL_' . ucfirst( $app );
		$api		= new $classname();
		$result 	= $api->verify_email( $value );

		if ( isset( $result['success'] ) ) {
			$options[ $app ][ $id ] = $value;
		}

	} else {

		if ( trim( $value ) ) {

			$options[ $app ][ $id ] = $value;

		} else {

			$result[ 'failed' ] = __( 'This cannot be empty', 'newsletter-glue' );

		}

	}

	update_option( 'newsletterglue_options', $options );

	wp_send_json( $result );

	die();
}
add_action( 'wp_ajax_newsletterglue_ajax_save_field', 'newsletterglue_ajax_save_field' );
add_action( 'wp_ajax_nopriv_newsletterglue_ajax_save_field', 'newsletterglue_ajax_save_field' );

/**
 * Get onboarding settings.
 */
function newsletterglue_ajax_get_onboarding_settings() {

	check_ajax_referer( 'newsletterglue-ajax-nonce', 'security' );

	if ( ! current_user_can( 'manage_newsletterglue' ) ) {
		wp_die( -1 );
	}

	$api 	= isset( $_REQUEST['api'] ) ? sanitize_text_field( $_REQUEST['api'] ) : '';

	if ( ! in_array( $api, array_keys( newsletterglue_get_supported_providers() ) ) ) {
		wp_die( -1 );
	}

	include_once NGL_PLUGIN_DIR . 'includes/integrations/' . $api . '/init.php';

	$classname 	= 'NGL_' . ucfirst( $api );
	$api		= new $classname();
	$api->connect();

	include NGL_PLUGIN_DIR . 'includes/admin/onboarding/views/mailchimp.php';

	die();
}
add_action( 'wp_ajax_newsletterglue_ajax_get_onboarding_settings', 'newsletterglue_ajax_get_onboarding_settings' );
add_action( 'wp_ajax_nopriv_newsletterglue_ajax_get_onboarding_settings', 'newsletterglue_ajax_get_onboarding_settings' );

/**
 * Send deactivation feedback.
 */
function newsletterglue_deactivate() {

	check_ajax_referer( 'newsletterglue-deactivate-nonce', 'security' );

	if ( ! current_user_can( 'manage_newsletterglue' ) ) {
		wp_die( 'No cheating, huh!' );
	}

	$email         = get_option( 'admin_email' );
	$_reason       = sanitize_text_field( wp_unslash( $_POST['reason'] ) );
	$reason_detail = sanitize_text_field( wp_unslash( $_POST['reason_detail'] ) );
	$feedback	   = sanitize_text_field( wp_unslash( $_POST['feedback'] ) );
	$reason        = '';

	if ( $_reason == '1' ) {
		$reason = 'I only needed the plugin for a short period';
	} elseif ( $_reason == '2' ) {
		$reason = 'I found a better plugin';
	} elseif ( $_reason == '3' ) {
		$reason = 'The plugin broke my site';
	} elseif ( $_reason == '4' ) {
		$reason = 'The plugin suddenly stopped working';
	} elseif ( $_reason == '5' ) {
		$reason = 'I no longer need the plugin';
	} elseif ( $_reason == '6' ) {
		$reason = 'It\'s a temporary deactivation. I\'m just debugging an issue.';
	} elseif ( $_reason == '7' ) {
		$reason = 'Other';
	}

	$fields = array(
        'email' 			=> $email,
        'website' 			=> get_site_url(),
        'action' 			=> 'Deactivate',
        'reason'  			=> $reason,
        'reason_detail'		=> $reason_detail,
		'feedback'			=> $feedback,
        'blog_language'     => get_bloginfo( 'language' ),
        'wordpress_version' => get_bloginfo( 'version' ),
        'php_version'       => PHP_VERSION,
        'plugin_version'    => NGL_VERSION,
        'plugin_name' 		=> 'Newsletter Glue',
	);

	$response = wp_remote_post( NGL_FEEDBACK_SERVER, array(
		'method'      => 'POST',
		'timeout'     => 5,
		'httpversion' => '1.0',
		'blocking'    => false,
		'headers'     => array(),
		'body'        => $fields,
	) );

	wp_die();

}
add_action( 'wp_ajax_newsletterglue_deactivate', 'newsletterglue_deactivate' );
add_action( 'wp_ajax_nopriv_newsletterglue_deactivate', 'newsletterglue_deactivate' );