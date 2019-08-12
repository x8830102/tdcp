<?php
/**
 * This file defines the notifications meta box.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     Antonio Villegas <antonio.villegas@neliosoftware.com>
 * @since      1.4.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * This class defines the notifications meta box.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     Antonio Villegas <antonio.villegas@neliosoftware.com>
 * @since      1.4.2
 */
class Nelio_Content_Notifications_Meta_Box implements Nelio_Content_Meta_Box {

	/**
	 * Initializes the meta box.
	 *
	 * @since  1.4.2
	 * @access public
	 */
	public function init() {

		add_action( 'add_meta_boxes', array( $this, 'add' ) );
		add_action( 'save_post', array( $this, 'save' ) );

	}//end init()

	// @Implements
	public function add() { // @codingStandardsIgnoreLine

		$settings = Nelio_Content_Settings::instance();

		if ( ! $settings->get( 'use_notifications' ) ) {
			return;
		}//end if

		$post_types = $settings->get( 'calendar_post_types' );

		foreach ( $post_types as $post_type ) {

			add_meta_box(
				'nelio-content-notifications',
				__( 'Notifications', 'nelio-content' ),
				array( $this, 'display' ),
				$post_type,
				'side',
				'default',
				array(
					'__block_editor_compatible_meta_box' => true,
				)
			);

		}//end foreach

	}//end add()

	/** . @SuppressWarnings( PHPMD.UnusedLocalVariable ) */
	// @Implements
	public function display( $post ) { // @codingStandardsIgnoreLine

		$container_name = 'nelio-content-notifications-container';
		include NELIO_CONTENT_ADMIN_DIR . '/views/partials/loading-container.php';

		include_once NELIO_CONTENT_ADMIN_DIR . '/views/partials/notifications/underscore-templates.php';
		include_once NELIO_CONTENT_ADMIN_DIR . '/views/partials/meta-box-error.php';

	}//end display()

	// @Implements
	public function save( $post_id ) { // @codingStandardsIgnoreLine

		$helper = Nelio_Content_Post_Helper::instance();

		if ( ! isset( $_REQUEST['_nc-notifications-meta-box'] ) ) {
			$helper->add_default_followers( $post_id );
			return;
		}//end if

		if ( wp_is_post_revision( $post_id) || wp_is_post_autosave( $post_id ) ) {
			return;
		}//end if

		$followers = array();
		if ( isset( $_REQUEST['nc_followers'] ) && ! empty( $_REQUEST['nc_followers'] ) ) {
			$followers = explode( ',', $_REQUEST['nc_followers'] );
		}//end if

		$helper->save_post_followers( $post_id, $followers );

	}//end save()

}//end class
