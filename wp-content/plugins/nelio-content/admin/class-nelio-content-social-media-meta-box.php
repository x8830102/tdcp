<?php
/**
 * This file defines the editorial comments meta box.
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
 * This class defines the editorial comments meta box.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */
class Nelio_Content_Social_Media_Meta_Box implements Nelio_Content_Meta_Box {

	/**
	 * Initializes this meta box.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function init() {

		add_action( 'add_meta_boxes', array( $this, 'add' ) );
		add_action( 'post_submitbox_start', array( $this, 'add_invisible_save_button' ) );
		add_action( 'save_post', array( $this, 'save' ) );

	}//end init()

	// @Implements
	public function add() { // @codingStandardsIgnoreLine

		$settings = Nelio_Content_Settings::instance();
		$post_types = $settings->get( 'calendar_post_types' );

		foreach ( $post_types as $post_type ) {

			add_meta_box(
				'nelio-content-social-media',
				_x( 'Social Media', 'text', 'nelio-content' ),
				array( $this, 'display' ),
				$post_type,
				'normal',
				'default',
				array(
					'__block_editor_compatible_meta_box' => true,
				)
			);

		}//end foreach

	}//end add()

	// @Implements
	/** . @SuppressWarnings( PHPMD.UnusedLocalVariable ) */
	public function display( $post ) { // @codingStandardsIgnoreLine

		$container_name = 'nelio-content-social-timeline-container';
		include NELIO_CONTENT_ADMIN_DIR . '/views/partials/loading-container.php';

		include_once NELIO_CONTENT_ADMIN_DIR . '/views/partials/social-message-editor/underscore-templates.php';
		include_once NELIO_CONTENT_ADMIN_DIR . '/views/partials/social-timeline/underscore-templates.php';
		include_once NELIO_CONTENT_ADMIN_DIR . '/views/partials/meta-box-error.php';

	}//end display()

	// @Implements
	public function save( $post_id ) { // @codingStandardsIgnoreLine

		if ( wp_is_post_revision( $post_id) || wp_is_post_autosave( $post_id ) ) {
			return;
		}//end if

		$settings = Nelio_Content_Settings::instance();
		$aux = Nelio_Content_Post_Helper::instance();

		if ( 'include-in-reshare' === $settings->get( 'auto_reshare_default_mode' ) ) {

			$excluded = isset( $_REQUEST['nc_excluded_from_reshare'] ) && 'on' === $_REQUEST['nc_excluded_from_reshare']; // Input var ok.

		} else {

			$included = isset( $_REQUEST['nc_included_in_reshare'] ) && 'on' === $_REQUEST['nc_included_in_reshare']; // Input var ok.
			$excluded = ! $included;

		}//end if

		if ( $excluded ) {
			$aux->exclude_from_reshare( $post_id );
		} else {
			$aux->include_in_reshare( $post_id );
		}//end if

	}//end save()

	/**
	 * This hook adds an extra (hidden) save button, so that the post can be saved
	 * when the user clicks on the button for generating social messages automatically.
	 *
	 * @since 1.3.4
	 */
	public function add_invisible_save_button() {

		printf(
			'<input id="nc-form-submit" type="submit" name"nc-save" value="%s" style="display:none;" />',
			esc_attr_x( 'Save', 'command', 'nelio-content' )
		);

	}//end add_invisible_save_button()

}//end class

