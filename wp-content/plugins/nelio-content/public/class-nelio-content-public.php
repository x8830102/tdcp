<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/public
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/public
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */
class Nelio_Content_Public {

	/**
	 * The single instance of this class.
	 *
	 * @since  1.3.4
	 * @access protected
	 * @var    Nelio_Content
	 */
	protected static $_instance;

	/**
	 * The ID of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	private function __construct() {

		// Nothing to be done.
		;

	}//end __construct()

	/**
	 * Cloning instances of this class is forbidden.
	 *
	 * @since  1.3.4
	 * @access public
	 */
	public function __clone() {

		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'nelio-content' ), '1.0.0' ); // @codingStandardsIgnoreLine

	}//end __clone()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since  1.3.4
	 * @access public
	 */
	public function __wakeup() {

		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'nelio-content' ), '1.0.0' ); // @codingStandardsIgnoreLine

	}//end __wakeup()

	/**
	 * Returns the single instance of this class.
	 *
	 * @return Nelio_Content_Public the single instance of this class.
	 *
	 * @since  1.3.4
	 * @access public
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}//end if

		return self::$_instance;

	}//end instance()

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name  The name of the plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since 1.3.4
	 */
	public static function set_plugin_info( $plugin_name, $version ) {

		$instance = self::instance();
		$instance->plugin_name = $plugin_name;
		$instance->version = $version;

	}//end set_plugin_info()

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style(
			$this->plugin_name,
			NELIO_CONTENT_PUBLIC_URL . '/css/public.css',
			array(),
			$this->version,
			'all'
		);

	}//end enqueue_styles()

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script(
			$this->plugin_name,
			NELIO_CONTENT_PUBLIC_URL . '/js/public.js',
			array( 'jquery' ),
			$this->version,
			false
		);

	}//end enqueue_scripts()

	/**
	 * Adds the Calendar entry in the admin bar (under the "Site" menu) and
	 * shortcuts to all blogs' calendars in a multisite installation (under the
	 * "My Sites" menu).
	 *
	 * @since  1.0.3
	 * @access public
	 */
	public function add_calendar_in_admin_bar() {

		if ( ! nc_is_current_user( 'contributor' ) ) {
			return;
		}//end if

		global $wp_admin_bar;

		$wp_admin_bar->add_node( array(
			'parent' => 'site-name',
			'id'     => 'nelio-content-calendar',
			'title'  => _x( 'Calendar', 'text (menu)', 'nelio-content' ),
			'href'   => admin_url( 'admin.php?page=nelio-content' ),
		) );

		if ( is_multisite() ) {

			$original_blog_id = get_current_blog_id();

			// Add this option for each blog in "My Sites" where current user has
			// access to the calendar.
			foreach ( (array) $wp_admin_bar->user->blogs as $blog ) {

				switch_to_blog( $blog->userblog_id );

				if ( ! $this->is_calendar_available() ) {
					continue;
				}//end if

				$wp_admin_bar->add_node( array(
					'parent' => 'blog-' . get_current_blog_id(),
					'id'     => 'nelio-content-calendar-blog-' . get_current_blog_id(),
					'title'  => _x( 'Calendar', 'text (menu)', 'nelio-content' ),
					'href'   => admin_url( 'admin.php?page=nelio-content' ),
				) );

			}//end foreach

			switch_to_blog( $original_blog_id );

		}//end if

	}//end add_calendar_in_admin_bar()

	/**
	 * Strips all ncshare tags from the content.
	 *
	 * @param string $content The original content.
	 *
	 * @return string The content with all `ncshare` tagsstripped.
	 *
	 * @since  1.3.4
	 * @access public
	 */
	public function remove_share_blocks( $content ) {

		$content = preg_replace( '/<.?ncshare[^>]*>/', '', $content );
		return $content;

	}//end remove_share_blocks()

	/**
	 * Returns whether the current user can access Nelio Content's calendar.
	 *
	 * @since  1.0.5
	 * @access public
	 */
	private function is_calendar_available() {

		if ( ! nc_is_current_user( 'contributor' ) ) {
			return false;
		}//end if

		return true;

	}//end is_calendar_available()

}//end class
