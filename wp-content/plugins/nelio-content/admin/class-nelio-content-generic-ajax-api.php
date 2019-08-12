<?php
/**
 * The plugin uses several AJAX calls. This class implements some AJAX
 * callbacks required by our plugin, which did not fit in any other
 * class (such as, for instance, post-related or reference-related
 * calls).
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * This class implements several admin AJAX callbacks.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */
class Nelio_Content_Generic_Ajax_API {

	/**
	 * Registers all callbacks.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function register_ajax_callbacks() {

		add_action( 'wp_ajax_nelio_content_get_users', array( $this, 'get_users' ) );
		add_action( 'wp_ajax_nelio_content_get_api_auth_token', array( $this, 'get_api_auth_token' ) );
		add_action( 'wp_ajax_nelio_content_reset_last_day_with_reshares', array( $this, 'reset_last_day_with_reshares' ) );
		add_action( 'wp_ajax_nelio_content_update_profiles_availability', array( $this, 'update_profiles_availability' ) );
		add_action( 'wp_ajax_nelio_content_upload_attachment', array( $this, 'upload_attachment' ) );

		add_action( 'wp_ajax_nelio_content_pause_message_publication', array( $this, 'pause_message_publication' ) );
		add_action( 'wp_ajax_nelio_content_reset_auto_social_messages', array( $this, 'reset_auto_social_messages' ) );

	}//end register_ajax_callbacks()

	/**
	 * This AJAX endpoint updates the information of a given reference.
	 *
	 * As a response, it returns the following:
	 *
	 *  * array of users, each compatible with User Backbone model.
	 *
	 * Possible `$_REQUEST` params:
	 *
	 *  * array $users Required. A list of user IDs.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function get_users() {

		// Retrieve and sanitize the list of user ids.
		$user_ids = array();
		if ( isset( $_REQUEST['users'] ) && is_array( $_REQUEST['users'] ) ) { // Input var okay.
			$user_ids = array_map( 'absint', $_REQUEST['users'] ); // Input var okay.
		}//end if

		// Result variable.
		$result = array();

		// Args for avatar.
		$args = array(
			'size'    => 60,
			'default' => 'blank',
		);

		// Query the users and save them to the result variable.
		$wp_users = get_users( array(
			'blog_id' => $GLOBALS['blog_id'],
			'include' => $user_ids,
		) );

		foreach ( $wp_users as $wp_user ) {

			$data = $wp_user->data;
			array_push( $result, array(
				'id'       => absint( $data->ID ),
				'email'    => $data->user_email,
				'name'     => $data->display_name,
				'photo'    => nc_get_avatar_url( $data->user_email, $args ),
				'editLink' => get_edit_user_link( $data->ID ),
				'role'     => nc_get_user_role( $wp_user ),
			) );

		}//end foreach

		// Send the result.
		wp_send_json( $result );

	}//end get_users()

	/**
	 * This AJAX endpoint returns a new token for accessing the API.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function get_api_auth_token() {

		wp_send_json( nc_generate_api_auth_token() );

	}//end get_api_auth_token()

	/**
	 * This function saves an option for remembering whether there are social
	 * profiles linked to Nelio Content or not.
	 *
	 * @since  1.2.3
	 * @access public
	 */
	public function update_profiles_availability() {

		// Retrieve and sanitize the value.
		$has_profiles = array();
		if ( ! isset( $_REQUEST['value'] ) ) { // Input var okay.
			return;
		}//end if

		$has_profiles = trim( sanitize_text_field( wp_unslash( $_REQUEST['value'] ) ) ) === 'yes';
		update_option( 'nc_has_social_profiles', $has_profiles );

	}//end update_profiles_availability()

	/**
	 * This AJAX endpoint modifies the last scheduled day option. This way, when
	 * the reshare cron re-runs, future days will be rescheduled with new social
	 * messages.
	 *
	 * @since  1.3.0
	 * @access public
	 */
	public function reset_last_day_with_reshares() {

		$now = time();
		$today = date( 'Y-m-d', $now );
		update_option( 'nc_reshare_last_day', $today );

	}//end reset_last_day_with_reshares()

	/**
	 * This AJAX endpoint uploads an image file to the media library setting its
	 * properties (caption, alt text, and description). Returns the URL of the
	 * new attachment in the proper thumbnail size requested.
	 *
	 * @since 1.4.7
	 * @access public
	 */
	public function upload_attachment() {

		if ( ! isset( $_REQUEST['file'] ) ) { // Input var okay.
			header( 'HTTP/1.1 400 Bad Request' );
			wp_send_json( _x( 'Request does not contain a valid file URL.', 'error', 'nelio-content' ) );
		}//end if

		if ( ! isset( $_REQUEST['postId'] ) ) { // Input var okay.
			header( 'HTTP/1.1 400 Bad Request' );
			wp_send_json( _x( 'Request does not contain a valid post ID.', 'error', 'nelio-content' ) );
		}//end if

		if ( ! isset( $_REQUEST['desc'] ) ) { // Input var okay.
			header( 'HTTP/1.1 400 Bad Request' );
			wp_send_json( _x( 'Request does not contain a valid description.', 'error', 'nelio-content' ) );
		}//end if

		if ( ! isset( $_REQUEST['thumbnail'] ) ) { // Input var okay.
			header( 'HTTP/1.1 400 Bad Request' );
			wp_send_json( _x( 'Request does not contain a valid thumbnail size.', 'error', 'nelio-content' ) );
		}//end if

		$file = $_REQUEST['file'];
		$post_id = $_REQUEST['postId'];
		$desc = $_REQUEST['desc'];
		$thumbnail = $_REQUEST['thumbnail'];

		// Upload the file in the WordPress media library.
		$attachment_id = media_sideload_image( $file, $post_id, $desc, 'id' );
		$attachment = array(
			'ID'           => $attachment_id,
			'post_content' => $desc,
			'post_excerpt' => $desc,
		);

		// Update the attachment into the database.
		wp_update_post( $attachment );
		update_post_meta( $attachment_id, '_wp_attachment_image_alt', $desc ); // Image Alt Text.

		// Return proper URL of the attachment thumbnail.
		$url = wp_get_attachment_image_url( $attachment_id, $thumbnail );
		wp_send_json( array(
			'url' => $url,
		 	'id'  => $attachment_id,
		) );
	}//end upload_attachment()

	/**
	 * This AJAX endpoint is used for pausing (or resuming) message publication in AWS .
	 *
	 * @since 1.5.15
	 * @access public
	 */
	public function pause_message_publication() {

		if ( ! isset( $_REQUEST['pause'] ) ) { // Input var okay.
			header( 'HTTP/1.1 400 Bad Request' );
			wp_send_json( _x( 'Request does not specify whether message publication should be paused or not.', 'error', 'nelio-content' ) );
		}//end if

		$pause = 0 !== absint( $_REQUEST['pause'] );

		// Note. Use error_logs for logging this function or you won't see anything.
		$data = array(
			'method'  => 'PUT',
			'timeout' => 30,
			'headers' => array(
				'Authorization' => 'Bearer ' . nc_generate_api_auth_token(),
				'accept'        => 'application/json',
				'content-type'  => 'application/json',
			),
			'body'    => wp_json_encode( array(
				'url'                        => home_url(),
				'timezone'                   => nc_get_timezone(),
				'language'                   => nc_get_language(),
				'isMessagePublicationPaused' => $pause,
			) ),
		);

		$url = nc_get_api_url( '/site/' . nc_get_site_id(), 'wp' );
		$result = wp_remote_request( $url, $data );

		if ( is_wp_error( $result ) ) {
			header( 'HTTP/1.1 500 Internal Server Error' );
			if ( $pause ) {
				wp_send_json( _x( 'Something went wrong when pausing the publication of social messages.', 'error', 'nelio-content' ) );
			} else {
				wp_send_json( _x( 'Something went wrong when resuming the publication of social messages.', 'error', 'nelio-content' ) );
			}//end if
		}//end if

		$result = json_decode( $result['body'] );
		wp_send_json( $result->isMessagePublicationPaused );

	}//end pause_message_publication()

	/**
	 * This AJAX endpoint is used for pausing (or resuming) message publication in AWS .
	 *
	 * @since 1.5.15
	 * @access public
	 */
	public function reset_auto_social_messages() {

		$sharer = Nelio_Content_Auto_Sharer::instance();
		$sharer->reset();
		wp_send_json( 'OK' );

	}//end reset_auto_social_messages()

}//end class
