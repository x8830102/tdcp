<?php
/**
 * Class for extending TinyMCE.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * Class for extending TinyMCE.
 *
 * Defines some methods for adding new buttons and so on.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.2.0
 *
 * @SuppressWarnings( PHPMD.ExcessiveClassComplexity )
 */
class Nelio_Content_TinyMce_Extensions {

	/**
	 * Adds TinyMCE-related hooks.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function define_admin_hooks() {

		add_filter( 'mce_external_plugins', array( $this, 'add_tinymce_plugin' ) );
		add_filter( 'mce_buttons', array( $this, 'add_tinymce_toolbar_buttons' ) );

		add_filter( 'tiny_mce_before_init', array( $this, 'add_custom_tags' ) );

	}//end define_admin_hooks()

	/**
	 * Adds a TinyMCE plugin compatible JS file to TinyMCE.
	 *
	 * @param array $plugin_array Array of registered TinyMCE plugins.
	 *
	 * @return array Modified array of registered TinyMCE plugins.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function add_tinymce_plugin( $plugin_array ) {

		if ( $this->can_tinymce_be_extended() ) {
			$aux = Nelio_Content::instance();
			$plugin_array['nelio_content'] = NELIO_CONTENT_ADMIN_URL . '/js/tinymce.min.js?version=' . $aux->get_version();
		}//end if
		return $plugin_array;

	}//end add_tinymce_plugin()

	/**
	 * Adds all buttons in the TinyMCE's toolbar.
	 *
	 * @param array $buttons Array of registered TinyMCE buttons.
	 *
	 * @return array Modified array of registered TinyMCE buttons.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function add_tinymce_toolbar_buttons( $buttons ) {

		if ( $this->can_tinymce_be_extended() ) {
			array_push( $buttons, 'nelio_content' );
		}//end if

		return $buttons;

	}//end add_tinymce_toolbar_buttons()

	/**
	 * Adds the tag ncshare (as well as its required classes and styles) in TinyMCE.
	 *
	 * @param array $options list of options.
	 *
	 * @return array modified list of options.
	 *
	 * @since  1.3.4
	 * @access public
	 */
	public function add_custom_tags( $options ) {

		$options = $this->append_value( $options, 'custom_elements', '~ncshare' );
		$options = $this->append_value( $options, 'extended_valid_elements', 'ncshare[class]' );

		$options = $this->append_value( $options, 'content_style', 'ncshare { background:#ffffaa; }' );
		$options = $this->append_value( $options, 'content_style', 'ncshare ncshare { background:#ffff66; }' );

		$options = $this->append_value( $options, 'content_style', 'ncshare.nc-has-caret { background:#ffff66; }' );
		$options = $this->append_value( $options, 'content_style', 'ncshare.nc-has-caret.nc-first { background:#ffee00; box-shadow: 0 0 0 3px #ffee00; }' );
		$options = $this->append_value( $options, 'content_style', 'ncshare.nc-has-caret.nc-first { background:#ffee00; box-shadow: 0 0 0 3px #ffee00; }' );

		$options = $this->append_value( $options, 'content_style', 'ncshare.nc-has-caret ncshare { background:transparent; }' );

		return $options;

	}//end add_custom_tags()

	/**
	 * Helper function for adding a certain <key,value> pair in an options array.
	 *
	 * @param array  $options the array of options.
	 * @param string $key     a certain key.
	 * @param string $value   a new value to append to the given key.
	 *
	 * @return array the new array of options.
	 *
	 * @since  1.3.4
	 * @access private
	 */
	private function append_value( $options, $key, $value ) {

		if ( ! isset( $options[ $key ] ) || empty( $options[ $key ] ) ) {
			$options[ $key ] = '';
		} else if ( in_array( $key, array( 'content_style' ) ) ) {
			$options[ $key ] .= ' ';
		} else {
			$options[ $key ] .= ',';
		}//end if
		$options[ $key ] .= $value;

		return $options;

	}//end append_value()

	/**
	 * Returns whether TinyMCE can be extended or not.
	 *
	 * TinyMCE can be extended if the current user can manage the plugin and the
	 * post type we're editing is a post type used in the calendar.
	 *
	 * @return boolean whether TinyMCE can be extended or not.
	 *
	 * @since  1.2.0
	 * @access private
	 */
	private function can_tinymce_be_extended() {

		if ( ! function_exists( 'get_current_screen' ) ) {
			return false;
		}//end if

		$screen = get_current_screen();
		$settings = Nelio_Content_Settings::instance();
		if ( ! in_array( $screen->id, $settings->get( 'calendar_post_types' ) ) ) {
			return false;
		}//end if

		return true;

	}//end can_tinymce_be_extended()

}//end class

