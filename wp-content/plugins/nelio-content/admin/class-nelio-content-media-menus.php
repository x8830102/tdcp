<?php
/**
 * This file defines the additional media menus.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     Antonio Villegas <antonio.villegas@neliosoftware.com>
 * @since      1.4.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * This class defines the additional menus included in Media Library Frame.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     Antonio Villegas <antonio.villegas@neliosoftware.com>
 * @since      1.4.6
 */
class Nelio_Content_Media_Menus {

	/**
	 * The single instance of this class.
	 *
	 * @since  1.4.6
	 * @access protected
	 * @var    Nelio_Content
	 */
	protected static $_instance;

	/**
	 * The ID of this plugin.
	 *
	 * @since  1.4.6
	 * @access private
	 * @var    string $plugin_name
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since  1.4.6
	 * @access private
	 * @var    string
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since  1.4.6
	 * @access private
	 */
	private function __construct() {

		// Nothing to be done.
		;

	}//end __construct()

	/**
	 * Cloning instances of this class is forbidden.
	 *
	 * @since  1.4.6
	 * @access public
	 */
	public function __clone() {

		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'nelio-content' ), '1.0.0' ); // @codingStandardsIgnoreLine

	}//end __clone()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since  1.4.6
	 * @access public
	 */
	public function __wakeup() {

		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'nelio-content' ), '1.0.0' ); // @codingStandardsIgnoreLine

	}//end __wakeup()

	/**
	 * Returns the single instance of this class.
	 *
	 * @return Nelio_Content_Media_Menus the single instance of this class.
	 *
	 * @since  1.4.6
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
	 * @since 1.4.6
	 */
	public static function set_plugin_info( $plugin_name, $version ) {

		$instance = self::instance();
		$instance->plugin_name = $plugin_name;
		$instance->version = $version;

	}//end set_plugin_info()

	/**
	 * Register admin hooks.
	 *
	 * @since  1.4.6
	 * @access public
	 */
	public function define_admin_hooks() {

		add_filter( 'media_upload_tabs', array( $this, 'add_new_media_tabs' ) );

		if ( nc_is_subscribed() ) {

			add_action( 'media_upload_unsplash', array( $this, 'add_unsplash_iframe' ) );
			add_action( 'media_upload_giphy', array( $this, 'add_giphy_iframe' ) );
			add_action( 'media_upload_pixabay', array( $this, 'add_pixabay_iframe' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		} else {

			add_action( 'media_upload_unsplash', array( $this, 'add_subscribe_iframe' ) );
			add_action( 'media_upload_giphy', array( $this, 'add_subscribe_iframe' ) );
			add_action( 'media_upload_pixabay', array( $this, 'add_subscribe_iframe' ) );

		}//end if

	}//end define_admin_hooks()

	/**
	 * Enqueue required scripts.
	 *
	 * @since  1.4.6
	 * @access public
	 */
	public function enqueue_scripts() {

		if ( ! $this->is_media_iframe() ) {
			return;
		}//end if

		wp_enqueue_script(
			'nelio_content_media_iframe',
			NELIO_CONTENT_ADMIN_URL . '/js/media.min.js',
			array( 'jquery', 'backbone', 'underscore' ),
			$this->version,
			true
		);

		wp_enqueue_script(
			'ncstore-js',
			NELIO_CONTENT_ADMIN_URL . '/lib/storejs/js/store.min.js',
			array(),
			$this->version,
			true
		);

		wp_localize_script(
			'nelio_content_media_iframe',
			'NelioContent',
			array(
				'helpers'      => array(),
				'collections'  => array(),
				'models'       => array(),
				'views'        => array(),
				'i18n'         => $this->get_i18n_object(),
				'apiAuthToken' => nc_generate_api_auth_token(),
				'searchApiUri' => nc_get_api_url( '/image/search', 'browser' ),
				'apiUri'       => nc_get_api_url( '/', 'browser' ),
			)
		);

	}//end enqueue_scripts()

	/**
	 * Enqueue required styles.
	 *
	 * @since  1.4.6
	 * @access public
	 */
	public function enqueue_styles() {

		if ( ! $this->is_media_iframe() ) {
			return;
		}//end if

		wp_enqueue_style(
			'nelio-content-media-iframe',
			NELIO_CONTENT_ADMIN_URL . '/css/media.min.css',
			array( 'media-views' ),
			$this->version,
			'all'
		);

	}//end enqueue_styles()

	/**
	 * Returns an i18n object, which is basically a set of pairs {key, i18n string}.
	 *
	 * @return array i18n object, which is basically a set of pairs {key, i18n string}.
	 *
	 * @since  1.4.7
	 * @access private
	 *
	 * @SuppressWarnings( PHPMD.ShortVariableName, PHPMD.ExcessiveMethodLength )
	 */
	private function get_i18n_object() {

		return include( NELIO_CONTENT_ADMIN_DIR . '/data/i18n.php' );

	}//end get_i18n_object()

	/**
	 * Add additional tabs to the default ones in the Media Library Frame.
	 *
	 * @param array $tabs Default tabs.
	 *
	 * @since  1.4.6
	 * @access public
	 */
	public function add_new_media_tabs( $tabs ) {
		$tabs['unsplash'] = _x( 'Insert from Unsplash', 'command', 'nelio-content' );
		$tabs['giphy'] = _x( 'Insert from Giphy', 'command', 'nelio-content' );
		$tabs['pixabay'] = _x( 'Insert from Pixabay', 'command', 'nelio-content' );
		return $tabs;
	}//end add_new_media_tabs()

	/**
	 * Render iframe for Unsplash.
	 *
	 * @since  1.4.6
	 * @access public
	 */
	public function add_unsplash_iframe() {

		wp_iframe( array( $this, 'add_unsplash_search_content' ) );

	}//end add_unsplash_iframe()

	/**
	 * Render iframe for Giphy.
	 *
	 * @since  1.4.6
	 * @access public
	 */
	public function add_giphy_iframe() {

		wp_iframe( array( $this, 'add_giphy_search_content' ) );

	}//end add_giphy_iframe()

	/**
	 * Render iframe for Pixabay.
	 *
	 * @since  1.4.7
	 * @access public
	 */
	public function add_pixabay_iframe() {

		wp_iframe( array( $this, 'add_pixabay_search_content' ) );

	}//end add_pixabay_iframe()

	/**
	 * Render iframe for when the user does not hold a subscription.
	 *
	 * @since  1.4.6
	 * @access public
	 */
	public function add_subscribe_iframe() {

		wp_iframe( array( $this, 'add_subscribe_content' ) );

	}//end add_subscribe_iframe()

	/**
	 * Render image search form for Unsplash.
	 *
	 * @since  1.4.6
	 * @access public
	 */
	public function add_unsplash_search_content() {

		$source = 'unsplash';
		include_once NELIO_CONTENT_ADMIN_DIR . '/views/partials/media/underscore-templates.php';

		echo '<div id="nc_media_container"></div>';
		echo '<input id="nc_current_media_tab" type="hidden" value="unsplash" />';

	}//end add_unsplash_search_content()

	/**
	 * Render image search form for Giphy.
	 *
	 * @since  1.4.6
	 * @access public
	 */
	public function add_giphy_search_content() {

		$source = 'giphy';
		include_once NELIO_CONTENT_ADMIN_DIR . '/views/partials/media/underscore-templates.php';

		echo '<div id="nc_media_container"></div>';
		echo '<input id="nc_current_media_tab" type="hidden" value="giphy" />';

	}//end add_giphy_search_content()

	/**
	 * Render image search form for Pixabay.
	 *
	 * @since  1.4.7
	 * @access public
	 */
	public function add_pixabay_search_content() {

		$source = 'pixabay';
		include_once NELIO_CONTENT_ADMIN_DIR . '/views/partials/media/underscore-templates.php';

		echo '<div id="nc_media_container"></div>';
		echo '<input id="nc_current_media_tab" type="hidden" value="pixabay" />';

	}//end add_pixabay_search_content()

	/**
	 * Render content for users who do not hold a subscription.
	 *
	 * @since  1.4.6
	 * @access public
	 */
	public function add_subscribe_content() {

		include_once NELIO_CONTENT_ADMIN_DIR . '/views/partials/media/subscribe.php';

	}//end add_subscribe_content()

	/**
	 * Check if we are on the Media Library iFrame.
	 *
	 * @since  1.4.6
	 * @access private
	 */
	private function is_media_iframe() {

		$screen = get_current_screen();
		return 'media-upload' === $screen->id;

	}//end is_media_iframe()

}//end class
