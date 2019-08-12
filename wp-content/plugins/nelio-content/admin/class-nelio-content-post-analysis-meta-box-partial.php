<?php
/**
 * This file defines the class for rendering the post analysis partial in the
 * Publish meta box.
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
 * This class includes the partials for rendering the post analysis block in
 * the Publish meta box.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */
class Nelio_Content_Post_Analysis_Meta_Box_Partial {

	/**
	 * Initializes this meta box.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function init() {

		add_action( 'post_submitbox_misc_actions', array( $this, 'display' ), 99 );
		add_action( 'add_meta_boxes', array( $this, 'add_gutenberg_meta_box' ), 1 );

	}//end init()

	/**
	 * This function prints a container div and includes the underscore template
	 * rendering the post analysis block in the Publish meta box.
	 *
	 * @since 1.0.0
	 */
	public function display( $post ) {

		// Add post analysis only in relevant post types.
		if ( ! $this->is_calendar_post_type_screen() ) {
			return;
		}//end if

		echo '<div class="nelio-content-post-analysis-container misc-pub-section"></div>';
		include_once NELIO_CONTENT_ADMIN_DIR . '/views/partials/post-analysis.php';
		include_once NELIO_CONTENT_ADMIN_DIR . '/views/partials/meta-box-error.php';

	}//end display()


	public function add_gutenberg_meta_box() { // @codingStandardsIgnoreLine

		if ( ! $this->is_post_analysis_enabled() ) {
			return;
		}//end if

		$settings = Nelio_Content_Settings::instance();
		$post_types = $settings->get( 'calendar_post_types' );

		foreach ( $post_types as $post_type ) {

			add_meta_box(
				'nelio-content-quality-analysis',
				_x( 'Quality Analysis', 'text', 'nelio-content' ),
				array( $this, 'display' ),
				$post_type,
				'side',
				'default',
				array(
					'__block_editor_compatible_meta_box' => true,
				)
			);

		}//end foreach

	}//end add_gutenberg_meta_box()

	/**
	 * Returns whether the current screen is the edit screen of a post type
	 * controlled by Nelio Content.
	 *
	 * @return boolean whether the current screen is the edit screen of a post
	 *                 type controlled by Nelio Content.
	 *
	 * @since  1.1.1
	 * @access private
	 */
	private function is_calendar_post_type_screen() {

		$settings = Nelio_Content_Settings::instance();
		$calendar_post_types = $settings->get( 'calendar_post_types' );
		$screen = get_current_screen();
		return in_array( $screen->id, $calendar_post_types );

	}//end is_calendar_post_type_screen()

	private function is_post_analysis_enabled() {

		$settings = Nelio_Content_Settings::instance();
		return nc_is_current_user( $settings->get( 'qa_required_role' ) );

	}//end is_post_analysis_enabled()

}//end class
