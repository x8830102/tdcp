<?php
/**
 * This class contains all account-related AJAX callbacks.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * This class implements several admin AJAX callbacks.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */
class Nelio_Content_Account_Ajax_API {

	/**
	 * Registers all callbacks.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function register_ajax_callbacks() {

		// The account API should be available to administrators only.
		if ( ! nc_is_current_user( 'administrator' ) ) {
			return;
		}//end if

		add_action( 'wp_ajax_nelio_content_get_account', array( $this, 'get_account' ) );

		add_action( 'wp_ajax_nelio_content_start_free_version', array( $this, 'start_free_version' ) );
		add_action( 'wp_ajax_nelio_content_use_license', array( $this, 'use_license' ) );
		add_action( 'wp_ajax_nelio_content_upgrade', array( $this, 'upgrade' ) );
		add_action( 'wp_ajax_nelio_content_cancel_subscription', array( $this, 'cancel_subscription' ) );
		add_action( 'wp_ajax_nelio_content_uncancel_subscription', array( $this, 'uncancel_subscription' ) );

	}//end register_ajax_callbacks()

	/**
	 * This AJAX endpoint returns the account information.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function get_account() {

		$settings = Nelio_Content_Settings::instance();
		$data = array(
			'method'    => 'GET',
			'timeout'   => 30,
			'sslverify' => ! $settings->get( 'uses_proxy' ),
			'headers'   => array(
				'Authorization' => 'Bearer ' . nc_generate_api_auth_token(),
				'accept'        => 'application/json',
				'content-type'  => 'application/json',
			),
		);

		$url = nc_get_api_url( '/site/' . nc_get_site_id(), 'wp' );
		$response = wp_remote_request( $url, $data );

		// If the response is an error, leave.
		nc_maybe_send_error_json( $response );

		// Update subscription information with response.
		$site_info = json_decode( $response['body'], true );
		nc_update_subscription_information_with_site_object( $site_info );

		// Regenerate the account result and send it to the JS.
		$account = $this->create_account_object();

		wp_send_json( $account );

	}//end get_account()

	/**
	 * This AJAX endpoint configures the plugin so that the user can use the free version.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function start_free_version() {

		$this->check_sni_compatibility();

		$settings = Nelio_Content_Settings::instance();
		$data = array(
			'method'    => 'POST',
			'timeout'   => 30,
			'sslverify' => ! $settings->get( 'uses_proxy' ),
			'headers'   => array(
				'accept'       => 'application/json',
				'content-type' => 'application/json',
			),
			'body' => wp_json_encode( array(
				'url'      => home_url(),
				'timezone' => nc_get_timezone(),
				'language' => nc_get_language(),
			)	),
		);

		$url = nc_get_api_url( '/site', 'wp' );
		$response = wp_remote_request( $url, $data );

		// If the response is an error, leave.
		nc_maybe_send_error_json( $response );

		// Update site ID and subscription information.
		$site_info = json_decode( $response['body'], true );

		if ( ! isset( $site_info['id'] ) ) {
			header( 'HTTP/1.1 500 Internal Server Error' );
			wp_send_json( _x( 'Response from Nelio Content\'s API couldn\'t be processed.', 'error', 'nelio-content' ) );
		}//end if

		update_option( 'nc_site_id', $site_info['id'] );
		update_option( 'nc_api_secret', $site_info['secret'] );

		nc_update_subscription_information_with_site_object( $site_info );

		// Regenerate the account result and send it to the JS.
		$account = $this->create_account_object();

		$aux = new Nelio_Content_External_Featured_Image_Admin();
		$aux->set_efi_mode();

		/**
		 * Fires once a free account has been created.
		 *
		 * @since 1.1.1
		 */
		do_action( 'nelio_content_free_account_created' );

		wp_send_json( $account );

	}//end start_free_version()

	/**
	 * This AJAX endpoint adds a license to the current setup.
	 *
	 * Accepted Request Parameters:
	 *
	 *  * `license`: the new license code to be used.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @SuppressWarnings( PHPMD.NPathComplexity )
	 */
	public function use_license() {

		// Retrieve and sanitize the license key.
		$license = '';
		if ( isset( $_REQUEST['license'] ) ) { // Input var okay.
			$license = trim( sanitize_text_field( wp_unslash( $_REQUEST['license'] ) ) ); // Input var okay.
		}//end if

		if ( empty( $license ) ) {
			header( 'HTTP/1.1 400 Bad Request' );
			wp_send_json( _x( 'License Key cannot be empty.', 'error', 'nelio-content' ) );
		}//end if

		if ( ! preg_match( '/^[a-zA-Z0-9$#]{21}$/', $license ) ) {
			header( 'HTTP/1.1 400 Bad Request' );
			wp_send_json( _x( 'Invalid License Key.', 'error', 'nelio-content' ) );
		}//end if

		$settings = Nelio_Content_Settings::instance();
		if ( nc_get_site_id() ) {

			$data = array(
				'method'    => 'POST',
				'timeout'   => 30,
				'sslverify' => ! $settings->get( 'uses_proxy' ),
				'headers'   => array(
					'Authorization' => 'Bearer ' . nc_generate_api_auth_token(),
					'accept'        => 'application/json',
					'content-type'  => 'application/json',
				),
				'body' => wp_json_encode( array(
					'license' => $license,
				)	),
			);

			$url = nc_get_api_url( '/site/' . nc_get_site_id() . '/subscription', 'wp' );

		} else {

			$this->check_sni_compatibility();

			$data = array(
				'method'    => 'POST',
				'timeout'   => 30,
				'sslverify' => ! $settings->get( 'uses_proxy' ),
				'headers'   => array(
					'accept'       => 'application/json',
					'content-type' => 'application/json',
				),
				'body' => wp_json_encode( array(
					'url'      => home_url(),
					'timezone' => nc_get_timezone(),
					'language' => nc_get_language(),
					'license'  => $license,
				)	),
			);

			$url = nc_get_api_url( '/site/subscription', 'wp' );

		}//end if

		$response = wp_remote_request( $url, $data );

		// If the response is an error, leave.
		nc_maybe_send_error_json( $response );

		// Update site ID and subscription information.
		$site_info = json_decode( $response['body'], true );

		if ( ! isset( $site_info['id'] ) ) {
			header( 'HTTP/1.1 500 Internal Server Error' );
			wp_send_json( _x( 'Response from Nelio Content\'s API couldn\'t be processed.', 'error', 'nelio-content' ) );
		}//end if

		// If this is a new site, let's save the ID and the secret.
		if ( ! nc_get_site_id() ) {
			update_option( 'nc_site_id', $site_info['id'] );
			update_option( 'nc_api_secret', $site_info['secret'] );
		}//end if

		nc_update_subscription_information_with_site_object( $site_info );

		// Regenerate the account result and send it to the JS.
		$account = $this->create_account_object();

		wp_send_json( $account );

	}//end use_license()

	/**
	 * This AJAX endpoint upgrades the subscription to the specified package.
	 *
	 * Accepted Request Parameters:
	 *
	 *  * `product`: the product identifier as defined in Fastspring.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function upgrade() {

		// Retrieve and sanitize the license key.
		$product = '';
		if ( isset( $_REQUEST['product'] ) ) { // Input var okay.
			$product = trim( sanitize_text_field( wp_unslash( $_REQUEST['product'] ) ) ); // Input var okay.
		}//end if

		$settings = Nelio_Content_Settings::instance();
		$data = array(
			'method'    => 'PUT',
			'timeout'   => 30,
			'sslverify' => ! $settings->get( 'uses_proxy' ),
			'headers'   => array(
				'Authorization' => 'Bearer ' . nc_generate_api_auth_token(),
				'accept'        => 'application/json',
				'content-type'  => 'application/json',
			),
			'body' => wp_json_encode( array(
				'product' => $product,
			)	),
		);

		$url = nc_get_api_url( '/site/' . nc_get_site_id() . '/subscription', 'wp' );
		$response = wp_remote_request( $url, $data );

		// If the response is an error, leave.
		nc_maybe_send_error_json( $response );

		// Update site ID and subscription information.
		$site_info = json_decode( $response['body'], true );

		if ( ! isset( $site_info['id'] ) ) {
			header( 'HTTP/1.1 500 Internal Server Error' );
			wp_send_json( _x( 'Response from Nelio Content\'s API couldn\'t be processed.', 'error', 'nelio-content' ) );
		}//end if

		// Update subscription.
		nc_update_subscription_information_with_site_object( $site_info );

		// Regenerate the account result and send it to the JS.
		$account = $this->create_account_object();

		wp_send_json( $account );

	}//end upgrade()

	/**
	 * This AJAX endpoint cancels the current subscription.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function cancel_subscription() {

		if ( ! nc_get_site_id() ) {
			header( 'HTTP/1.1 400 Bad Request' );
			wp_send_json( _x( 'Subscription cannot be canceled, because there\'s no account available.', 'error', 'nelio-content' ) );
		}//end if

		$settings = Nelio_Content_Settings::instance();
		$data = array(
			'method'    => 'DELETE',
			'timeout'   => 30,
			'sslverify' => ! $settings->get( 'uses_proxy' ),
			'headers'   => array(
				'Authorization' => 'Bearer ' . nc_generate_api_auth_token(),
				'accept'        => 'application/json',
				'content-type'  => 'application/json',
			),
		);

		$url = nc_get_api_url( '/site/' . nc_get_site_id() . '/subscription', 'wp' );
		$response = wp_remote_request( $url, $data );

		// If the response is an error, leave.
		nc_maybe_send_error_json( $response );

		// Update site ID and subscription information.
		$site_info = json_decode( $response['body'], true );

		if ( ! isset( $site_info['id'] ) ) {
			header( 'HTTP/1.1 500 Internal Server Error' );
			wp_send_json( _x( 'Response from Nelio Content\'s API couldn\'t be processed.', 'error', 'nelio-content' ) );
		}//end if

		update_option( 'nc_site_id', $site_info['id'] );
		nc_update_subscription_information_with_site_object( $site_info );

		// Regenerate the account result and send it to the JS.
		$account = $this->create_account_object();

		wp_send_json( $account );

	}//end cancel_subscription()

	/**
	 * This AJAX endpoint uncancels the current subscription.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function uncancel_subscription() {

		if ( ! nc_get_site_id() ) {
			header( 'HTTP/1.1 400 Bad Request' );
			wp_send_json( _x( 'Subscription cannot be reactivated, because there\'s no account available.', 'error', 'nelio-content' ) );
		}//end if

		$settings = Nelio_Content_Settings::instance();
		$data = array(
			'method'    => 'POST',
			'timeout'   => 30,
			'sslverify' => ! $settings->get( 'uses_proxy' ),
			'headers'   => array(
				'Authorization' => 'Bearer ' . nc_generate_api_auth_token(),
				'accept'        => 'application/json',
				'content-type'  => 'application/json',
			),
		);

		$url = nc_get_api_url( '/site/' . nc_get_site_id() . '/subscription/uncancel', 'wp' );
		$response = wp_remote_request( $url, $data );

		// If the response is an error, leave.
		nc_maybe_send_error_json( $response );

		// Update site ID and subscription information.
		$site_info = json_decode( $response['body'], true );

		if ( ! isset( $site_info['id'] ) ) {
			header( 'HTTP/1.1 500 Internal Server Error' );
			wp_send_json( _x( 'Response from Nelio Content\'s API couldn\'t be processed.', 'error', 'nelio-content' ) );
		}//end if

		update_option( 'nc_site_id', $site_info['id'] );
		nc_update_subscription_information_with_site_object( $site_info );

		// Regenerate the account result and send it to the JS.
		$account = $this->create_account_object();

		wp_send_json( $account );

	}//end uncancel_subscription()

	/**
	 * This AJAX endpoint configures the plugin so that the user can use the free version.
	 *
	 * @since  1.1.0
	 * @access private
	 */
	public function check_sni_compatibility() {

		$settings = Nelio_Content_Settings::instance();

		if ( $settings->get( 'uses_proxy' ) ) {
			return;
		}//end if

		$data = array(
			'method'    => 'GET',
			'timeout'   => 30,
			'sslverify' => true,
			'headers'   => array(
				'accept'       => 'application/json',
				'content-type' => 'application/json',
			),
		);

		$url = NELIO_CONTENT_API_URL . '/time';
		$response = wp_remote_request( $url, $data );

		if ( ! $response || is_wp_error( $response ) || 200 !== $response['response']['code'] ) {
			$aux = get_option( $settings->get_name(), array() );
			$aux['uses_proxy'] = true;
			update_option( $settings->get_name(), $aux );
		}//end if

	}//end check_sni_compatibility()

	/**
	 * This helper function creates an account object, ready to be used in our JS
	 * scripts.
	 *
	 * @return array an account object ready to be used in JavaScript.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	private function create_account_object() {

		$subscription = nc_get_subscription();

		if ( $subscription ) {

			return array(
				'creationDate'    => $subscription['creationDate'],
				'email'           => $subscription['email'],
				'firstname'       => $subscription['firstname'],
				'lastname'        => $subscription['lastname'],
				'photo'           => nc_get_avatar_url( $subscription['email'], array( 'default' => 'blank' ) ),
				'mode'            => $subscription['mode'],
				'license'         => $subscription['license'],
				'endDate'         => $subscription['endDate'],
				'nextChargeDate'  => $subscription['nextChargeDate'],
				'nextChargeTotal' => $subscription['nextChargeTotal'],
				'subscription'    => $subscription['plan'],
				'state'           => $subscription['state'],
			);

		} else {

			return array(
				'subscription' => 'none',
				'mode'         => 'regular',
			);

		}//end if

	}//end create_account_object()

}//end class
