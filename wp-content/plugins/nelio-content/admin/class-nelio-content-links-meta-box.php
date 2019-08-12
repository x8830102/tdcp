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
class Nelio_Content_Links_Meta_Box implements Nelio_Content_Meta_Box {

	/**
	 * Initializes this meta box.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function init() {

		add_action( 'add_meta_boxes', array( $this, 'add' ) );

	}//end init()

	// @Implements
	public function add() { // @codingStandardsIgnoreLine

		$settings = Nelio_Content_Settings::instance();
		$post_types = $settings->get( 'calendar_post_types' );

		foreach ( $post_types as $post_type ) {

			add_meta_box(
				'nelio-content-links',
				_x( 'References', 'text', 'nelio-content' ),
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

	// @Implements
	/** . @SuppressWarnings( PHPMD.UnusedLocalVariable ) */
	public function display( $post ) { // @codingStandardsIgnoreLine

		$container_name = 'nelio-content-links-container';

		include NELIO_CONTENT_ADMIN_DIR . '/views/partials/loading-container.php';
		include_once NELIO_CONTENT_ADMIN_DIR . '/views/partials/links/broken-links/underscore-templates.php';
		include_once NELIO_CONTENT_ADMIN_DIR . '/views/partials/links/references/underscore-templates.php';
		include_once NELIO_CONTENT_ADMIN_DIR . '/views/partials/meta-box-error.php';

	}//end display()

	// @Implements
	public function save( $post_id ) { // @codingStandardsIgnoreLine
		// No need to save anything (this meta box works with the cloud).
	}//end save()

}//end class
