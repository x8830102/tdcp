<?php
/**
 * This file contains a class with notifications-related functions.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     Antonio Villegas <antonio.villegas@neliosoftware.com>
 * @since      1.4.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * This class implements notifications-related functions.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes
 * @author     Antonio Villegas <antonio.villegas@neliosoftware.com>
 * @since      1.4.2
 */
class Nelio_Content_Notifications {

	/**
	 * The single instance of this class.
	 *
	 * @since  1.4.2
	 * @access protected
	 * @var    Nelio_Content_Notifications
	 */
	protected static $_instance;

	/**
	 * Cloning instances of this class is forbidden.
	 *
	 * @since  1.4.2
	 * @access public
	 */
	public function __clone() {

		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'nelio-content' ), '1.0.0' ); // @codingStandardsIgnoreLine

	}//end __clone()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since  1.4.2
	 * @access public
	 */
	public function __wakeup() {

		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'nelio-content' ), '1.0.0' ); // @codingStandardsIgnoreLine

	}//end __wakeup()

	/**
	 * Returns the single instance of this class.
	 *
	 * @return Nelio_Content_Notifications the single instance of this class.
	 *
	 * @since  1.4.2
	 * @access public
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}//end if

		return self::$_instance;

	}//end instance()

	/**
	 * Adds the proper hooks.
	 *
	 * @since  1.4.2
	 * @access public
	 */
	public function define_admin_hooks() {

		add_action( 'plugins_loaded', array( $this, 'maybe_add_notification_hooks_in_admin' ) );

	}//end define_admin_hooks()

	/**
	 * Adds the proper hooks.
	 *
	 * @since  1.4.6
	 * @access public
	 */
	public function maybe_add_notification_hooks_in_admin() {

		$settings = Nelio_Content_Settings::instance();
		if ( ! $settings->get( 'use_notifications' ) ) {
			return;
		}//end if

		// Editorial comments change actions.
		add_action( 'nelio_content_create_editorial_comment', array( $this, 'maybe_send_comment_creation_notification' ) );

		// Editorial tasks change actions.
		add_action( 'nelio_content_create_editorial_task', array( $this, 'maybe_send_task_creation_notification' ) );
		add_action( 'nelio_content_status_change_editorial_task', array( $this, 'maybe_send_task_status_change_notification' ) );

		// Users change actions.
		add_action( 'delete_user',  array( $this, 'delete_user_action' ) );

	}//end maybe_add_notification_hooks_in_admin()

	/**
	 * Adds the proper hooks.
	 *
	 * @since  1.6.5
	 * @access public
	 */
	public function define_universal_hooks() {

		add_action( 'plugins_loaded', array( $this, 'maybe_add_notification_hooks_that_should_always_be_available' ) );

	}//end define_universal_hooks()

	/**
	 * Adds the proper hooks.
	 *
	 * @since  1.6.5
	 * @access public
	 */
	public function maybe_add_notification_hooks_that_should_always_be_available() {

		$settings = Nelio_Content_Settings::instance();
		if ( ! $settings->get( 'use_notifications' ) ) {
			return;
		}//end if

		// Post status change actions.
		// save_post_followers() is hooked into transition_post_status so we can
		// ensure followers are properly saved before sending notifications.
		// See https://core.trac.wordpress.org/ticket/19074
		add_action( 'transition_post_status', array( $this, 'save_post_followers' ), 0, 3 );
		add_action( 'transition_post_status', array( $this, 'maybe_send_post_status_change_notification' ), 10, 3 );

	}//end maybe_add_notification_hooks_that_should_always_be_available()

	/**
	 * Called when post is saved. Handles saving of post followers.
	 *
	 * @param string  $new_status New post status.
	 * @param string  $old_status Old post status.
	 * @param WP_Post $post       The post.
	 *
	 * @since  1.4.2
	 * @access public
	 */
	public function save_post_followers( $new_status, $old_status, $post ) {

		if ( wp_is_post_revision( $post) || wp_is_post_autosave( $post ) ) {
			return;
		}//end if

		$settings = Nelio_Content_Settings::instance();
		$supported_post_types = $settings->get( 'calendar_post_types' );
		if ( ! in_array( $post->post_type, $supported_post_types ) ) {
			return;
		}//end if

		$helper = Nelio_Content_Post_Helper::instance();

		if ( ! isset( $_REQUEST['_nc-notifications-meta-box'] ) ) {
			$helper->add_default_followers( $post->ID );
			return;
		}//end if

		$followers = array();
		if ( isset( $_REQUEST['nc_followers'] ) && ! empty( $_REQUEST['nc_followers'] ) ) {
			$followers = explode( ',', $_REQUEST['nc_followers'] );
		}//end if

		$old_followers = get_post_meta( $post->ID, '_nc_following_users', false );
		$helper->save_post_followers( $post->ID, $followers );
		$current_followers = get_post_meta( $post->ID, '_nc_following_users', false );

		$new_followers = $this->get_new_followers( $old_followers, $current_followers );
		$new_followers = $this->get_email_address( $new_followers );
		if ( count( $new_followers ) ) {
			$this->maybe_send_following_post_notification( $new_followers, $post );
		}//end if

	}//end save_post_followers()

	private function get_new_followers( $old, $current ) {

		return array_filter( $current, function( $follower ) use ( $old ) {
			return ! in_array( $follower, $old, true );
		});

	}//end get_new_followers()

	/**
	 * Called when post is saved. Handles notification of post followers.
	 *
	 * @param string  $new_status New post status.
	 * @param string  $old_status Old post status.
	 * @param WP_Post $post       The post.
	 *
	 * @since  1.4.2
	 * @access public
	 */
	public function maybe_send_post_status_change_notification( $new_status, $old_status, $post ) {

		// Kill switch for notification
		if ( ! apply_filters( 'nelio_content_notification_status_change', $new_status, $old_status, $post ) ||
				 ! apply_filters( "nelio_content_notification_{$post->post_type}_status_change", $new_status, $old_status, $post ) ) {
			return false;
		}//end if

		$settings = Nelio_Content_Settings::instance();
		$supported_post_types = $settings->get( 'calendar_post_types' );
		if ( ! in_array( $post->post_type, $supported_post_types ) ) {
			return;
		}//end if

		// No need to notify if it's a revision, auto-draft, or if post status
		// wasn't changed.
		$ignored_statuses = apply_filters( 'nelio_content_notification_ignored_statuses', array( $old_status, 'inherit', 'auto-draft' ), $post->post_type );

		if ( in_array( $new_status, $ignored_statuses ) ) {
			return;
		}//end if

		$email = $this->get_post_status_change_email_data( $new_status, $old_status, $post );
		$this->send_email( 'status-change', $post, $email->subject, $email->message );

	}//end maybe_send_post_status_change_notification()

	/**
	 * Notifies the given recipients that they're following this post.
	 *
	 * @param array   $recipients The recipients to notify.
	 * @param WP_Post $post       The post.
	 *
	 * @since  1.6.8
	 * @access private
	 */
	private function maybe_send_following_post_notification( $recipients, $post ) {

		// Kill switch for notification
		if ( ! apply_filters( 'nelio_content_notification_following_post', $post ) ||
				 ! apply_filters( "nelio_content_notification_{$post->post_type}_following_post", $post ) ) {
			return false;
		}//end if

		$settings = Nelio_Content_Settings::instance();
		$supported_post_types = $settings->get( 'calendar_post_types' );
		if ( ! in_array( $post->post_type, $supported_post_types ) ) {
			return;
		}//end if

		// No need to notify if it's a revision or an auto-draft.
		$ignored_statuses = apply_filters( 'nelio_content_notification_ignored_statuses', array( 'inherit', 'auto-draft' ), $post->post_type );
		if ( in_array( $new_status, $ignored_statuses ) ) {
			return;
		}//end if

		$email = $this->get_post_following_email_data( $post );

		$subject = apply_filters( 'nelio_content_notification_send_email_subject', $email->subject, 'following-post', $post );
		$message = apply_filters( 'nelio_content_notification_send_email_message', $email->message, 'following-post', $post );
		$message_headers = apply_filters( 'nelio_content_notification_send_email_message_headers', $message_headers, 'following-post', $post );

		wp_mail( $recipients, $subject, $message, $message_headers );

	}//end maybe_send_following_post_notification()

	/**
	 * Called when editorial comment is created. Handles notification of post
	 * followers.
	 *
	 * @param object  $comment Editorial comment.
	 *
	 * @since  1.4.2
	 * @access public
	 */
	public function maybe_send_comment_creation_notification( $comment ) {

		$post = get_post( $comment->post_id );

		// Kill switch for notification.
		if ( ! apply_filters( 'nelio_content_notification_editorial_comment', $comment, $post ) ) {
			return false;
		}//end if

		$current_user = wp_get_current_user();

		// Set user to follow post, but make it filterable.
		if ( apply_filters( 'nelio_content_notification_auto_subscribe_current_user', true, 'comment' ) ) {
			nc_add_post_meta_once( $post->ID, '_nc_following_users', (int) $current_user->ID );
		}//end if

		// Set the post author to follow the post but make it filterable.
		if ( apply_filters( 'nelio_content_notification_auto_subscribe_post_author', true, 'comment' ) ) {
			nc_add_post_meta_once( $post->ID, '_nc_following_users', (int) $post->post_author );
		}//end if

		$email = $this->get_comment_in_post_email_data( $post, $comment );

		$this->send_email( 'comment', $post, $email->subject, $email->message );

	}//end maybe_send_comment_creation_notification()

	/**
	 * Called when editorial task is created. Handles notification of post or task
	 * followers.
	 *
	 * @param object  $task Editorial task.
	 *
	 * @since  1.4.2
	 * @access public
	 */
	public function maybe_send_task_creation_notification( $task ) {

		$post = get_post( $task->post_id );

		// Kill switch for notification.
		if ( ! apply_filters( 'nelio_content_notification_editorial_task', $task, $post ) ) {
			return false;
		}//end if

		$current_user = wp_get_current_user();

		// Set user to follow post, but make it filterable.
		if ( $post && apply_filters( 'nelio_content_notification_auto_subscribe_current_user', true, 'task' ) ) {
			nc_add_post_meta_once( $post->ID, '_nc_following_users', (int) $current_user->ID );
		}//end if

		// Set the post author to follow the post but make it filterable.
		if ( $post && apply_filters( 'nelio_content_notification_auto_subscribe_post_author', true, 'task' ) ) {
			nc_add_post_meta_once( $post->ID, '_nc_following_users', (int) $post->post_author );
		}//end if

		$email = $this->get_task_creation_email_data( $task, $post );

		$this->send_task_email( 'task-creation', $task, $post, $email->subject, $email->message );

	}//end maybe_send_task_creation_notification()

	/**
	 * Called when editorial task is updated. Handles notification of post or task
	 * followers.
	 *
	 * @param object  $task Editorial task.
	 *
	 * @since  1.4.2
	 * @access public
	 */
	public function maybe_send_task_status_change_notification( $task ) {

		$post = get_post( $task->post_id );

		// Kill switch for notification.
		if ( ! apply_filters( 'nelio_content_notification_editorial_task', $task, $post ) ) {
			return false;
		}//end if

		$current_user = wp_get_current_user();

		// Set user to follow post, but make it filterable.
		if ( $post && apply_filters( 'nelio_content_notification_auto_subscribe_current_user', true, 'task' ) ) {
			nc_add_post_meta_once( $post->ID, '_nc_following_users', (int) $current_user->ID );
		}//end if

		// Set the post author to follow the post but make it filterable.
		if ( $post && apply_filters( 'nelio_content_notification_auto_subscribe_post_author', true, 'task' ) ) {
			nc_add_post_meta_once( $post->ID, '_nc_following_users', (int) $post->post_author );
		}//end if

		$email = $this->get_task_completed_email_data( $task, $post );

		$this->send_task_email( 'task-completed', $task, $post, $email->subject, $email->message );

	}//end maybe_send_task_status_change_notification()

	/**
	 * Removes users that are deleted from receiving future notifications (i.e.
	 * makes them unfollow posts FOREVER!)
	 *
	 * @param $id int ID of the user
	 *
	 * @since  1.4.2
	 * @access public
	 */
	public function delete_user_action( $id ) {

		if( ! $id ) {
			return;
		}//end if

		global $wpdb;
		return $wpdb->delete(
			$wpdb->postmeta,
			array(
				'meta_key'=> '_nc_following_users',
				'meta_value' => $id
			)
		);

	}//end delete_user_action()

	/**
	 * Sends an email notification.
	 *
	 * @param  string  $action          Notification action.
	 * @param  WP_Post $post            Post related to the notification.
	 * @param  string  $subject         Email subject.
	 * @param  string  $message         Message contents.
	 * @param  mixed   $message_headers Additional headers.
	 *
	 * @return boolean Whether the email contents were sent successfully.
	 *
	 * @since 1.4.2
	 * @access private
	 */
	private function send_email( $action, $post, $subject, $message, $message_headers = '' ) {

		$to = $this->get_notification_recipients( $post );
		if ( empty( $to ) ) {
			return;
		}//end if

		$subject = apply_filters( 'nelio_content_notification_send_email_subject', $subject, $action, $post );
		$message = apply_filters( 'nelio_content_notification_send_email_message', $message, $action, $post );
		$message_headers = apply_filters( 'nelio_content_notification_send_email_message_headers', $message_headers, $action, $post );

		return wp_mail( $to, $subject, $message, $message_headers );

	}//end send_email()

	/**
	 * Sends an email notification related to a task.
	 *
	 * @param  string  $action          Notification action.
	 * @param  object  $task            Task related to the notification.
	 * @param  WP_Post $post            Post related to the notification.
	 * @param  string  $subject         Email subject.
	 * @param  string  $message         Message contents.
	 * @param  mixed   $message_headers Additional headers.
	 *
	 * @return boolean Whether the email contents were sent successfully.
	 *
	 * @since 1.4.2
	 * @access private
	 */
	private function send_task_email( $action, $task, $post, $subject, $message, $message_headers = '' ) {

		$recipients = $this->get_task_notification_recipients( $task, $post );
		$subject = apply_filters( 'nelio_content_task_notification_send_email_subject', $subject, $action, $task );
		$message = apply_filters( 'nelio_content_task_notification_send_email_message', $message, $action, $task );
		$message_headers = apply_filters( 'nelio_content_task_notification_send_email_message_headers', $message_headers, $action, $task );

		return wp_mail( $recipients, $subject, $message, $message_headers );

	}//end send_task_email()

	/**
	 * Returns a list of recipients for a given post.
	 *
	 * @param WP_Post $post The post.
	 *
	 * @return array recipients to receive notification.
	 *
	 * @since 1.4.2
	 * @access private
	 */
	private function get_notification_recipients( $post ) {

		$post_id = $post->ID;
		if ( ! $post_id ) {
			return;
		}//end if

		$recipients = $this->get_following_users( $post_id, 'user_email' );
		$current_user_email = wp_get_current_user()->user_email;

		// Filter any duplicates.
		$recipients = array_unique( $recipients );

		// Process the recipients for this email to be sent.
		foreach ( $recipients as $key => $user_email ) {

			// Get rid of empty email entries.
			if ( empty( $recipients[ $key ] ) ) {
				unset( $recipients[ $key ] );
			}//end if

			// Don't send the email to the current user unless we've explicitly
			// indicated they should receive it.
			if ( false === apply_filters( 'nelio_content_notification_email_current_user', false ) &&
					 $current_user_email == $user_email ) {
				unset( $recipients[ $key ] );
			}//end if
		}//end foreach

		// Filter to allow further modification of recipients
		$recipients = apply_filters( 'nelio_content_notification_recipients', $recipients, $post );

		return $recipients;

	}//end get_notification_recipients()

	/**
	 * Returns a list of recipients for a given task.
	 *
	 * @param object  $task The task.
	 * @param WP_Post $post The post.
	 *
	 * @return array recipients to receive notification.
	 *
	 * @since 1.4.2
	 * @access private
	 */
	private function get_task_notification_recipients( $task, $post ) {

		$recipients = array();
		if ( $post ) {
			$recipients = $this->get_notification_recipients( $post );
		}//end if

		$assigner = get_userdata( $task->assigner_id );
		$assignee = get_userdata( $task->assignee_id );

		if ( $assigner && is_user_member_of_blog( $assigner->ID ) ) {
			array_push( $recipients, $assigner->user_email );
		}//end if

		if ( $assignee && is_user_member_of_blog( $assignee->ID ) ) {
			array_push( $recipients, $assignee->user_email );
		}//end if

		$current_user_email = wp_get_current_user()->user_email;

		// Filter any duplicates.
		$recipients = array_unique( $recipients );

		// Process the recipients for this email to be sent.
		foreach ( $recipients as $key => $user_email ) {

			// Get rid of empty email entries.
			if ( empty( $recipients[ $key ] ) ) {
				unset( $recipients[ $key ] );
			}//end if

			// Don't send the email to the current user unless we've explicitly
			// indicated they should receive it.
			if ( false === apply_filters( 'nelio_content_notification_email_current_user', false ) &&
					 $current_user_email == $user_email ) {
				unset( $recipients[ $key ] );
			}//end if
		}//end foreach

		// Filter to allow further modification of recipients
		$recipients = apply_filters( 'nelio_content_task_notification_recipients', $recipients, $task );

		return $recipients;

	}//end get_task_notification_recipients()

	/**
	 * Gets a list of the users following the specified post.
	 *
	 * @param int    $post_id The ID of the post
	 * @param string $return The field to return
	 *
	 * @return array $users Users following the specified posts
	 *
	 * @since 1.4.2
	 * @access private
	 */
	private function get_following_users( $post_id, $return = 'user_login' ) {

		// Get following users for the post.
		$users = get_post_meta( $post_id, '_nc_following_users', false );

		// Don't have any following users.
		if ( ! is_array( $users ) ) {
			return array();
		}//end if

		// if just want user_login, return as is
		if ( 'id' === $return ) {
			return $users;
		}//end if

		$result = array();
		foreach ( $users as $user_id ) {

			$user = get_userdata( $user_id );

			if ( ! $user || ! is_user_member_of_blog( $user->ID ) ) {
				continue;
			}//end if

			switch ( $return ) {
				case 'user_login':
					array_push( $result, $user->user_login );
					break;
				case 'user_email':
					array_push( $result, $user->user_email );
					break;
			}//end switch

		}//end foreach

		return $result;

	}//end get_following_users()

	private function get_email_address( $user_ids ) {

		if ( ! is_array( $user_ids ) ) {
			return array();
		}//end if

		return array_filter( array_map( function( $user_id ) {

			$user = get_userdata( $user_id );
			if ( ! $user || ! is_user_member_of_blog( $user->ID ) ) {
				return false;
			} else {
				return $user->user_email;
			}//end if

		}, $user_ids ) );

	}//end get_email_address()

	/**
	 * Gets the subject and message data of the email to send when a new comment
	 * is created in a post.
	 *
	 * @param WP_Post $post    The post.
	 * @param object  $comment The comment.
	 *
	 * @return object $email Email data, including subject and message.
	 *
	 * @since 1.4.2
	 * @access private
	 */
	private function get_comment_in_post_email_data( $post, $comment ) {

		$post_id = $post->ID;
		$post_type = get_post_type_object( $post->post_type )->labels->singular_name;
		$post_title = ! empty( $post->post_title ) ? $post->post_title : _x( '(no title)', 'text', 'nelio-content' );

		$current_user = wp_get_current_user();
		$current_user_name = $current_user->display_name;
		$current_user_email = $current_user->user_email;

		$blog_name = get_option( 'blogname' );

		$current_date = mysql2date( get_option( 'date_format' ), $comment->date );
		$current_time = mysql2date( get_option( 'time_format' ), $comment->date );

		$subject = sprintf(
			/* translators: 1: blog name, 2: post title */
			_x( '[%1$s] New Editorial Comment: "%2$s"', 'text', 'nelio-content' ),
			$blog_name, $post_title
		);

		$message = sprintf(
			/* translators: 1: post id, 2: post title, 3. post type */
			_x( 'A new editorial comment was added to %3$s #%1$s "%2$s"', 'text', 'nelio-content' ),
			$post_id, $post_title, $post_type
		) . "\r\n\r\n";

		$message .= sprintf(
			/* translators: 1: comment author, 2: author email, 3: date, 4: time */
			_x( '%1$s (%2$s) said on %3$s at %4$s:', 'text', 'nelio-content' ),
			$current_user_name, $current_user_email, $current_date, $current_time
		) . "\r\n";

		$message .= "\r\n" . $comment->comment . "\r\n";
		$message .= $this->get_email_footer( $post );

		$email = new stdClass();
		$email->subject = $subject;
		$email->message = $message;

		return $email;

	}//end get_comment_in_post_email_data()

	/**
	 * Gets the subject and message data of the email to send when a post changes
	 * its status.
	 *
	 * @param string  $new_status New post status.
	 * @param string  $old_status Old post status.
	 * @param WP_Post $post       The post.
	 *
	 * @return object $email Email data, including subject and message.
	 *
	 * @since 1.4.2
	 * @access private
	 */
	private function get_post_status_change_email_data( $new_status, $old_status, $post ) {

		$status_helper = Nelio_Content_Post_Statuses::instance();

		$post_id = $post->ID;
		$post_type = get_post_type_object( $post->post_type )->labels->singular_name;
		$post_title = ! empty( $post->post_title ) ? $post->post_title : _x( '(no title)', 'text', 'nelio-content' );
		$post_author = get_userdata( $post->post_author );

		$blog_name = get_option( 'blogname' );

		$current_user = wp_get_current_user();
		if ( 0 !== $current_user->ID ) {
			/* translators: 1: user name, 2. user email */
			$username_and_email = sprintf( _x( '%1$s (%2$s)', 'text', 'nelio-content' ), $current_user->display_name, $current_user->user_email );
		} else {
			$username_and_email = _x( 'WordPress Scheduler', 'text', 'nelio-content' );
		}//end if

		$message = '';

		// Email subject and first line of body.
		// Set message subjects according to what action is being taken on the Post.
		if ( 'new' === $old_status || 'auto-draft' === $old_status ) {

			$subject = sprintf(
				/* translators: 1: site name, 2: post type, 3. post title */
				_x( '[%1$s] New %2$s Created: "%3$s"', 'text', 'nelio-content' ),
				$blog_name, $post_type, $post_title
			);

			$message .= sprintf(
				/* translators: 1: post type, 2: post id, 3. post title, 4. user name */
				_x( 'A new %1$s (#%2$s "%3$s") was created by %4$s', 'text', 'nelio-content' ),
				$post_type, $post_id, $post_title, $username_and_email
			) . "\r\n";

		} else if ( 'trash' === $new_status ) {

			$subject = sprintf(
				/* translators: 1: site name, 2: post type, 3. post title */
				_x( '[%1$s] %2$s Trashed: "%3$s"', 'text', 'nelio-content' ),
				$blog_name, $post_type, $post_title
			);

			$message .= sprintf(
				/* translators: 1: post type, 2: post id, 3. post title, 4. user name */
				_x( '%1$s #%2$s "%3$s" was moved to the trash by %4$s', 'text', 'nelio-content' ),
				$post_type, $post_id, $post_title, $username_and_email
			) . "\r\n";

		} else if ( 'trash' === $old_status ) {

			$subject = sprintf(
				/* translators: 1: site name, 2: post type, 3. post title */
				_x( '[%1$s] %2$s Restored (from Trash): "%3$s"', 'text', 'nelio-content' ),
				$blog_name, $post_type, $post_title
			);

			$message .= sprintf(
				/* translators: 1: post type, 2: post id, 3. post title, 4. user name */
				_x( '%1$s #%2$s "%3$s" was restored from trash by %4$s', 'text', 'nelio-content' ),
				$post_type, $post_id, $post_title, $username_and_email
			) . "\r\n";

		} else if ( 'future' === $new_status ) {

			$subject = sprintf(
				/* translators: 1: site name, 2: post type, 3. post title */
				_x( '[%1$s] %2$s Scheduled: "%3$s"', 'text', 'nelio-content' ),
				$blog_name, $post_type, $post_title
			);

			$message .= sprintf(
				/* translators: 1: post type, 2: post id, 3. post title, 4. user name, 5. scheduled date  */
				_x( '%1$s #%2$s "%3$s" was scheduled by %4$s. It will be published on %5$s', 'text', 'nelio-content' ),
				$post_type, $post_id, $post_title, $username_and_email, $this->get_scheduled_datetime( $post )
			) . "\r\n";

		} else if ( 'publish' === $new_status ) {

			$subject = sprintf(
				/* translators: 1: site name, 2: post type, 3. post title */
				_x( '[%1$s] %2$s Published: "%3$s"', 'text', 'nelio-content' ),
				$blog_name, $post_type, $post_title
			);

			$message .= sprintf(
				/* translators: 1: post type, 2: post id, 3. post title, 4. user name */
				_x( '%1$s #%2$s "%3$s" was published by %4$s', 'text', 'nelio-content' ),
				$post_type, $post_id, $post_title, $username_and_email
			) . "\r\n";

		} else if ( 'publish' === $old_status ) {

			$subject = sprintf(
				/* translators: 1: site name, 2: post type, 3. post title */
				_x( '[%1$s] %2$s Unpublished: "%3$s"', 'text', 'nelio-content' ),
				$blog_name, $post_type, $post_title
			);

			$message .= sprintf(
				/* translators: 1: post type, 2: post id, 3. post title, 4. user name */
				_x( '%1$s #%2$s "%3$s" was unpublished by %4$s', 'text', 'nelio-content' ),
				$post_type, $post_id, $post_title, $username_and_email
			) . "\r\n";

		} else {

			$subject = sprintf(
				/* translators: 1: site name, 2: post type, 3. post title */
				_x( '[%1$s] %2$s Status Changed for "%3$s"', 'text', 'nelio-content' ),
				$blog_name, $post_type, $post_title
			);

			$message .= sprintf(
				/* translators: 1: post type, 2: post id, 3. post title, 4. user name */
				_x( 'Status was changed for %1$s #%2$s "%3$s" by %4$s', 'text', 'nelio-content'),
				$post_type, $post_id, $post_title, $username_and_email
			) . "\r\n";

		}//end if

		$message .= sprintf(
			/* translators: 1: date, 2: time, 3: timezone */
			_x( 'This action was taken on %1$s at %2$s %3$s', 'text', 'nelio-content' ),
			date_i18n( get_option( 'date_format' ) ),
			date_i18n( get_option( 'time_format' ) ),
			get_option( 'timezone_string' )
		) . "\r\n";

		// Email body
		$friendly_old_status = $status_helper->get_post_status_friendly_name( $old_status );
		$friendly_new_status = $status_helper->get_post_status_friendly_name( $new_status );

		$message .= "\r\n";

		$message .= sprintf(
			/* translators: 1: old status, 2: new status */
			_x( '%1$s => %2$s', 'text', 'nelio-content' ),
			$friendly_old_status, $friendly_new_status
		);
		$message .= "\r\n\r\n";

		$message .= "--------------------\r\n\r\n";

		/* translators: post type */
		$message .= sprintf( _x( '== %s Details ==', 'title', 'nelio-content' ), $post_type ) . "\r\n";
		/* translators: post title */
		$message .= sprintf( _x( 'Title: %s', 'text', 'nelio-content' ), $post_title ) . "\r\n";

		if ( ! empty( $post_author ) ) {

			$message .= sprintf(
				/* translators: 1: author name, 2: author email */
				_x( 'Author: %1$s (%2$s)', 'text', 'nelio-content' ),
				$post_author->display_name, $post_author->user_email
			) . "\r\n";

		}//end if

		$message .= $this->get_email_footer( $post );

		$email = new stdClass();
		$email->subject = $subject;
		$email->message = $message;

		return $email;

	}//end get_post_status_change_email_data()

	/**
	 * Gets the subject and message data of the email to send when a post changes
	 * its status.
	 *
	 * @param WP_Post $post The post.
	 *
	 * @return object $email Email data, including subject and message.
	 *
	 * @since 1.4.2
	 * @access private
	 */
	private function get_post_following_email_data( $post ) {

		$status_helper = Nelio_Content_Post_Statuses::instance();

		$post_id = $post->ID;
		$post_type = get_post_type_object( $post->post_type )->labels->singular_name;
		$post_title = ! empty( $post->post_title ) ? $post->post_title : _x( '(no title)', 'text', 'nelio-content' );
		$post_author = get_userdata( $post->post_author );

		$blog_name = get_option( 'blogname' );

		$current_user = wp_get_current_user();
		if ( 0 !== $current_user->ID ) {
			/* translators: 1: user name, 2. user email */
			$username_and_email = sprintf( _x( '%1$s (%2$s)', 'text', 'nelio-content' ), $current_user->display_name, $current_user->user_email );
		} else {
			$username_and_email = _x( 'WordPress Scheduler', 'text', 'nelio-content' );
		}//end if

		$subject = sprintf(
			/* translators: 1: site name, 2: post type, 3. post title */
			_x( '[%1$s] You\'re now watching %2$s "%3$s"', 'text', 'nelio-content' ),
			$blog_name, $post_type, $post_title
		);

		$message = sprintf(
			/* translators: 1: post type, 2: post title */
			_x( 'You\'re now watching %1$s "%2$s".', 'text', 'nelio-content' ),
			$post_type, $post_title
		) . "\r\n\r\n";

		$message .= sprintf(
			/* translators: 1: date, 2: time, 3: timezone */
			_x( 'This action was taken on %1$s at %2$s %3$s', 'text', 'nelio-content' ),
			date_i18n( get_option( 'date_format' ) ),
			date_i18n( get_option( 'time_format' ) ),
			get_option( 'timezone_string' )
		) . "\r\n\r\n";

		$message .= "--------------------\r\n\r\n";

		/* translators: post type */
		$message .= sprintf( _x( '== %s Details ==', 'title', 'nelio-content' ), $post_type ) . "\r\n";
		/* translators: post title */
		$message .= sprintf( _x( 'Title: %s', 'text', 'nelio-content' ), $post_title ) . "\r\n";

		if ( ! empty( $post_author ) ) {

			$message .= sprintf(
				/* translators: 1: author name, 2: author email */
				_x( 'Author: %1$s (%2$s)', 'text', 'nelio-content' ),
				$post_author->display_name, $post_author->user_email
			) . "\r\n";

		}//end if

		$message .= sprintf(
			/* translators: post status */
			_x( 'Status: %s', 'text', 'nelio-content' ),
			$status_helper->get_post_status_friendly_name( $post->post_status )
		) . "\r\n";

		$message .= $this->get_email_footer( $post );

		$email = new stdClass();
		$email->subject = $subject;
		$email->message = $message;

		return $email;

	}//end get_post_following_email_data()

	/**
	 * Gets the subject and message data of the email to send when a new task
	 * is created.
	 *
	 * @param object  $task    The task.
	 * @param WP_Post $post    The post.
	 * @param object  $comment The comment.
	 *
	 * @return object Email data, including subject and message.
	 *
	 * @since 1.4.2
	 * @access private
	 */
	private function get_task_creation_email_data( $task, $post ) {

		if ( $post ) {
			$post_id = $post->ID;
			$post_type = get_post_type_object( $post->post_type )->labels->singular_name;
			$post_title = ! empty( $post->post_title ) ? $post->post_title : _x( '(no title)', 'text', 'nelio-content' );
		}//end if

		$current_user = wp_get_current_user();
		$current_user_name = $current_user->display_name;
		$current_user_email = $current_user->user_email;

		$blog_name = get_option( 'blogname' );

		if ( $post ) {

			$subject = sprintf(
				/* translators: 1: blog name, 2: post title */
				_x( '[%1$s] New Editorial Task in "%2$s"', 'text', 'nelio-content' ),
				$blog_name, $post_title
			);

			$message = sprintf(
				/* translators: 1: post id, 2: post title, 3. post type */
				_x( 'A new editorial task was added to %3$s #%1$s "%2$s".', 'text', 'nelio-content' ),
				$post_id, $post_title, $post_type
			) . "\r\n\r\n";

		} else {

			/* translators: blog name */
			$subject = sprintf( _x( '[%s] New Editorial Task', 'text', 'nelio-content' ), $blog_name );
			$message = _x( 'A new editorial task was added.', 'text', 'nelio-content' ) . "\r\n\r\n";

		}//end if

		$message .= sprintf(
			/* translators: 1: task author, 2: task author email */
			_x( '%1$s (%2$s) created the following task:', 'text', 'nelio-content' ),
			$current_user_name, $current_user_email
		) . "\r\n\r\n";

		$message .= $this->get_task_information( $task );
		$message .= $this->get_email_footer( $post );

		$email = new stdClass();
		$email->subject = $subject;
		$email->message = $message;

		return $email;

	}//end get_task_creation_email_data()

	/**
	 * Gets the subject and message data of the email to send when a new task
	 * is completed.
	 *
	 * @param object  $task    The task.
	 * @param WP_Post $post    The post.
	 * @param object  $comment The comment.
	 *
	 * @return object Email data, including subject and message.
	 *
	 * @since 1.4.2
	 * @access private
	 */
	private function get_task_completed_email_data( $task, $post ) {

		if ( $post ) {
			$post_id = $post->ID;
			$post_type = get_post_type_object( $post->post_type )->labels->singular_name;
			$post_title = ! empty( $post->post_title ) ? $post->post_title : _x( '(no title)', 'text', 'nelio-content' );
		}//end if

		$current_user = wp_get_current_user();
		$current_user_name = $current_user->display_name;
		$current_user_email = $current_user->user_email;

		$blog_name = get_option( 'blogname' );

		if ( $post ) {

			$subject = sprintf(
				/* translators: 1: blog name, 2: post title */
				_x( '[%1$s] Editorial Task Completed in "%2$s"', 'text', 'nelio-content' ),
				$blog_name, $post_title
			);

			$message = sprintf(
				/* translators: 1: post id, 2: post title, 3. post type */
				_x( 'An editorial task was completed in %3$s #%1$s "%2$s".', 'text', 'nelio-content' ),
				$post_id, $post_title, $post_type
			) . "\r\n\r\n";

		} else {

			$subject = sprintf(
				/* translators: blog name */
				_x( '[%s] Editorial Task Completed', 'text', 'nelio-content' ),
				$blog_name
			);
			$message = _x( 'An editorial task was completed.', 'text', 'nelio-content' ) . "\r\n\r\n";

		}//end if

		$message .= sprintf(
			/* translators: 1: task author, 2: task author email */
			_x( '%1$s (%2$s) completed the following task:', 'text', 'nelio-content' ),
			$current_user_name, $current_user_email
		) . "\r\n\r\n";

		$message .= $this->get_task_information( $task );
		$message .= $this->get_email_footer( $post );

		$email = new stdClass();
		$email->subject = $subject;
		$email->message = $message;

		return $email;

	}//end get_task_completed_email_data()

	private function get_task_information( $task ) {

		$assignee = get_userdata( $task->assignee_id );
		$assignee_name = $assignee->display_name;
		$assignee_email = $assignee->user_email;

		$assigner = get_userdata( $task->assigner_id );
		$assigner_name = $assigner->display_name;
		$assigner_email = $assigner->user_email;

		/* translators: a task description */
		$info = " - " . sprintf( _x( 'Task: %s', 'text', 'nelio-content' ), $task->task ) . "\r\n";
		/* translators: 1: user name, 2: user email */
		$info .= " - " . sprintf( _x( 'Assignee: %1$s (%2$s)', 'text', 'nelio-content' ), $assignee_name, $assignee_email ) . "\r\n";
		/* translators: 1: user name, 2: user email */
		$info .= " - " . sprintf( _x( 'Assigner: %1$s (%2$s)', 'text', 'nelio-content' ), $assigner_name, $assigner_email ) . "\r\n";

		if ( $task->date_due ) {
			$task_due_date = mysql2date( get_option( 'date_format' ), $task->date_due );
			$task_due_time = mysql2date( get_option( 'time_format' ), $task->date_due );
			/* translators: a date */
			$info .= " - " . sprintf( _x( 'Due Date: %s', 'text', 'nelio-content' ), $task_due_date ) . "\r\n";
		}//end if

		return $info;

	}//end get_task_information()

	/**
	 * XXX
	 *
	 * @param WP_Post $post The post.
	 *
	 * @return string Email footer
	 *
	 * @since 1.4.2
	 * @access private
	 */
	private function get_email_footer( $post = false ) {

		$blog_name = get_option( 'blogname' );
		$blog_url = get_bloginfo( 'url' );
		$admin_url = admin_url( '/' );

		$footer = '';

		if ( $post ) {

			$post_title = ! empty( $post->post_title ) ? $post->post_title : _x( '(no title)', 'text', 'nelio-content' );
			$edit_link = htmlspecialchars_decode( get_edit_post_link( $post->ID ) );
			$post_type_labels = get_post_type_object( $post->post_type )->labels;

			if ( 'publish' !== $post->post_status ) {
				$view_link = add_query_arg( array( 'preview' => 'true' ), wp_get_shortlink( $post->ID ) );
			} else {
				$view_link = htmlspecialchars_decode( get_permalink( $post->ID ) );
			}//end if

			$footer .= "\r\n";
			$footer .= _x( '== Actions ==', 'title', 'nelio-content' ) . "\r\n";
			/* translators: 1: the "Edit" command, as in "Edit Post", 2: the edit link */
			$footer .= sprintf( _x( '%1$s: %2$s', 'command (edit)', 'nelio-content' ), $post_type_labels->edit_item, $edit_link ) . "\r\n";
			/* translators: 1: the "View" command, as in "View Post", 2: the view link */
			$footer .= sprintf( _x( '%1$s: %2$s', 'command (view)', 'nelio-content' ), $post_type_labels->view_item, $view_link ) . "\r\n";

			$footer .= "\r\n--------------------\r\n";
			/* translators: a post title */
			$footer .= sprintf( _x( 'You are receiving this email because you are subscribed to "%s".', 'user', 'nelio-content' ), $post_title );

		} else {

			$footer .= "\r\n--------------------\r\n";
			/* translators: a blog URL */
			$footer .= sprintf( _x( 'You are receiving this email because you are registered to %s.', 'user', 'nelio-content' ), $blog_url );

		}//end if

		$footer .= "\r\n\r\n";
		$footer .= $blog_name ." | ". $blog_url . " | " . $admin_url . "\r\n";

		return $footer;

	}//end get_email_footer()

	/**
	 * Gets a simple phrase containing the formatted date and time that the post
	 * is scheduled for.
	 *
	 * @param  WP_Post $post The post.
	 *
	 * @return string  $scheduled_datetime The scheduled datetime in human-readable
	 *                                     format.
	 *
	 * @since 1.4.2
	 * @access public
	 *
	 */
	public function get_scheduled_datetime( $post ) {

		$scheduled_timestatmp = strtotime( $post->post_date );

		$date = date_i18n( get_option( 'date_format' ), $scheduled_timestatmp );
		$time = date_i18n( get_option( 'time_format' ), $scheduled_timestatmp );

		/* translators: 1: post scheduled date, 2: post scheduled time */
		return sprintf( _x( '%1$s at %2$s', 'text', 'nelio-content' ), $date, $time );

	}//end get_scheduled_datetime()

}//end class
