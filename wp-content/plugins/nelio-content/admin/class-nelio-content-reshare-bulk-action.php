<?php
/**
 * Functions for adding custom actions and bulk edit options in post list table.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.4.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * XXX
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.4.2
 */
class Nelio_Content_Reshare_Bulk_Action {

	/**
	 * Registers the requried hooks for adding the plugin's menu in the Dashboard.
	 *
	 * @since  1.4.2
	 * @access public
	 */
	public function define_admin_hooks() {

		add_action( 'admin_enqueue_scripts', array( $this, 'add_custom_style' ) );
		add_filter( 'manage_posts_columns', array( $this, 'add_column_for_excluded_reshare' ), 10, 2 );
		add_action( 'manage_posts_custom_column', array( $this, 'add_value_in_column_for_excluded_reshare' ), 10, 2 );
		add_filter( 'post_class', array( $this, 'maybe_add_class_for_excluded_reshare' ), 10, 3 );
		add_action( 'bulk_edit_custom_box', array( $this, 'maybe_add_bulk_edit_for_excluding_from_reshare' ), 10, 2 );


		add_action( 'save_post', array( $this, 'update_reshare_meta_on_post_save' ), 10, 2 );

	}//end define_admin_hooks()

	/**
	 * XXX
	 *
	 * @since  1.4.2
	 * @access public
	 */
	public function add_custom_style() {

		if ( ! $this->is_current_screen_a_relevant_list_page() ) {
			return;
		}//end if

		$custom_css = '';
		$custom_css .= '.column-nc_include_post_in_reshare { width: 10% !important; }';
		$custom_css .= '.nc-reshare { color:green; }';
		$custom_css .= '.nc-dont-reshare { color:red; }';

		wp_add_inline_style( 'list-tables', $custom_css );

	}//end add_custom_style()

	/**
	 * XXX
	 *
	 * @param array  $columns   XXX
	 * @param string $post_type XXX
	 *
	 * @since  1.4.2
	 * @access public
	 */
	public function add_column_for_excluded_reshare( $columns, $post_type ) {

		$settings = Nelio_Content_Settings::instance();
		$post_types = $settings->get( 'calendar_post_types' );

		if ( ! in_array( $post_type, $post_types, true ) ) {
			return $columns;
		}//end if

		$columns['nc_include_post_in_reshare'] = _x( 'Reshare', 'title', 'nelio-content' );
		return $columns;

	}//end add_column_for_excluded_reshare()

	/**
	 * XXX
	 *
	 * @param string $column  XXX
	 * @param int    $post_id XXX
	 *
	 * @since  1.4.2
	 * @access public
	 */
	public function add_value_in_column_for_excluded_reshare( $column, $post_id ) {

		if ( 'nc_include_post_in_reshare' !== $column ) {
			return;
		}//end if

		$aux = Nelio_Content_Post_Helper::instance();
		if ( $aux->is_post_excluded_from_reshare( $post_id ) ) {
			echo '<span class="nc-dont-reshare">';
			esc_html_e( 'No', 'nelio-content' );
			echo '</span>';
		} else {
			echo '<span class="nc-reshare">';
			esc_html_e( 'Yes', 'nelio-content' );
			echo '</span>';
		}//end if

	}//end add_value_in_column_for_excluded_reshare()

	/**
	 * XXX
	 *
	 * @param array $classes XXX
	 * @param array $class   XXX
	 * @param int   $post_id XXX
	 *
	 * @return array XXX
	 *
	 * @since  1.4.2
	 * @access public
	 */
	public function maybe_add_class_for_excluded_reshare( $classes, $class, $post_id ) {

		if ( ! $this->is_current_screen_a_relevant_list_page() ) {
			return $classes;
		}//end if

		$aux = Nelio_Content_Post_Helper::instance();
		if ( $aux->is_post_excluded_from_reshare( $post_id ) ) {
			array_push( $classes, 'nc-exclude-from-reshare' );
		}//end if

		return $classes;

	}//end maybe_add_class_for_excluded_reshare()

	/**
	 * XXX
	 *
	 * @param string $column    XXX
	 * @param string $post_type XXX
	 *
	 * @since  1.4.2
	 * @access public
	 */
	public function maybe_add_bulk_edit_for_excluding_from_reshare( $column, $post_type ) {

		if ( 'nc_include_post_in_reshare' !== $column ) {
			return;
		}//end if

		$settings = Nelio_Content_Settings::instance();
		$post_types = $settings->get( 'calendar_post_types' );
		if ( ! in_array( $post_type, $post_types, true ) ) {
			return;
		}//end if

		include NELIO_CONTENT_ADMIN_DIR . '/views/partials/bulk-reshare.php';

	}//end maybe_add_bulk_edit_for_excluding_from_reshare()

	/**
	 * XXX
	 *
	 * @param int $post_id XXX
	 *
	 * @since  1.4.2
	 * @access public
	 */
	public function update_reshare_meta_on_post_save( $post_id, $post ) {

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}//end if

		$settings = Nelio_Content_Settings::instance();
		$post_types = $settings->get( 'calendar_post_types' );
		if ( ! in_array( $post->post_type, $post_types, true ) ) {
			return;
		}//end if

		// does this user have permissions?
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}//end if

		$reshare = false;
		if ( isset( $_REQUEST['nc_reshare'] ) ) { // Input var ok.
			$reshare = sanitize_text_field( wp_unslash( $_REQUEST['nc_reshare'] ) ); // Input var ok.
		}//end if

		$aux = Nelio_Content_Post_Helper::instance();
		switch ( $reshare ) {

			case 'include':
				$aux->include_in_reshare( $post_id );
				break;

			case 'exclude':
				$aux->exclude_from_reshare( $post_id );
				break;

		}//end switch

	}//end update_reshare_meta_on_post_save()

	/**
	 * XXX
	 *
	 * @param int $post_id XXX
	 *
	 * @return boolean XXX
	 *
	 * @since  1.4.2
	 * @access private
	 */
	private function is_current_screen_a_relevant_list_page() {

		$screen = get_current_screen();
		$screen = $screen->id;

		if ( strpos( $screen, 'edit-' ) !== 0 ) {
			return false;
		}//end if

		$settings = Nelio_Content_Settings::instance();
		$post_types = $settings->get( 'calendar_post_types' );

		$screen = preg_replace( '/^edit-/', '', $screen );
		if ( ! in_array( $screen, $post_types, true ) ) {
			return false;
		}//end if

		return true;

	}//end is_current_screen_a_relevant_list_page()

}//end class
