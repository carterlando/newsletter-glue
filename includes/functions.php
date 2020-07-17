<?php
/**
 * Functions.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Creates the admin menu links.
 */
function newsletterglue_get_supported_providers() {
	
	$providers = array(
		'mailchimp'		=> __( 'Mailchimp', 'newsletter-glue' ),
	);

	return apply_filters( 'newsletterglue_get_supported_providers', $providers );
}

/**
 * Get provider name (Service, or API name)
 */
function newsletterglue_get_name( $provider ) {

	$providers = newsletterglue_get_supported_providers();

	return isset( $providers[ $provider ] ) ? $providers[ $provider ] : '';
}

/**
 * Get the current page URL
 */
function newsletterglue_get_current_page_url() {
	global $wp;

	if ( get_option( 'permalink_structure' ) ) {

		$base = trailingslashit( home_url( $wp->request ) );

	} else {

		$base = add_query_arg( $wp->query_string, '', trailingslashit( home_url( $wp->request ) ) );
		$base = remove_query_arg( array( 'post_type', 'name' ), $base );

	}

	$scheme = is_ssl() ? 'https' : 'http';
	$uri    = set_url_scheme( $base, $scheme );

	if ( is_front_page() ) {
		$uri = home_url( '/' );
	}

	$uri = apply_filters( 'newsletterglue_get_current_page_url', $uri );

	return $uri;
}

/**
 * Update the campaign result data.
 */
function newsletterglue_add_campaign_data( $post_id, $subject = '', $result = '', $id = '' ) {

	$results   = ( array ) get_post_meta( $post_id, '_ngl_results', true );
	$time      = current_time( 'timestamp' );

	if ( $subject ) {
		$result[ 'subject' ] = $subject;
	}

	if ( $id ) {
		$result[ 'campaign_id' ] = $id;
	}

	// Add the result to post meta.
	if ( $result ) {

		$results[ $time ] = $result;

		update_post_meta( $post_id, '_ngl_results', $results );
		update_post_meta( $post_id, '_ngl_last_result', $result );

		// Store this as notice.
		if ( $result['type'] === 'error' ) {

			$result[ 'post_id' ] = $post_id;
			$result[ 'time' ]    = $time;

			newsletterglue_add_notice( $result );

		}

	}

}

/**
 * Get option.
 */
function newsletterglue_get_option( $option_id = '', $app = '' ) {

	$options = get_option( 'newsletterglue_options' );

	if ( isset( $options[ $app ][ $option_id ] ) ) {
		return $options[ $app ][ $option_id ];
	}

	return false;
}

/**
 * Get default from name.
 */
function newsletterglue_get_default_from_name() {

	$user_id 	= get_current_user_id();
	$first_name = get_user_meta( $user_id, 'first_name', true );
	$site_name  = get_bloginfo( 'name' );

	if ( $first_name ) {
		$from_name = sprintf( __( '%s from %s', 'newsletter-glue' ), $first_name, $site_name );
	} else {
		$from_name = $site_name;
	}

	return apply_filters( 'newsletterglue_get_default_from_name', $from_name );
}