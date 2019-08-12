<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 *
 * @SuppressWarnings( PHPMD.ExcessiveClassComplexity )
 */
final class Nelio_Content {

	/**
	 * The single instance of this class.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    Nelio_Content
	 */
	protected static $_instance;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string
	 */
	protected $version;

	/**
	 * Whether site options where changed or not.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    boolean
	 */
	private $are_site_options_updated;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function __construct() {

		$this->plugin_name = 'nelio_content';
		$this->version = '1.6.17';
		$this->are_site_options_updated = false;

		$this->define_constants();
		$this->load_dependencies();
		$this->set_locale();

		if ( $this->is_request( 'admin' ) ) {

			$this->define_cleaner_hooks();

			if ( nc_get_site_id() ) {
				$this->define_admin_hooks();
			} else {
				$this->define_setup_hooks();
			}//end if

		}//end if

		if ( $this->is_request( 'frontend' ) ) {
			$this->define_public_hooks();
		}//end if

		$this->define_universal_hooks();
		$this->add_compat_fixes();

		/**
		 * Fires after (possibly) all dependencies are loaded and hooks are created.
		 *
		 * @since 1.0.0
		 */
		do_action( 'nelio_content_loaded' );

	}//end __construct()

	/**
	 * Cloning instances of this class is forbidden.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function __clone() {

		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'nelio-content' ), '1.0.0' ); // @codingStandardsIgnoreLine

	}//end __clone()


	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function __wakeup() {

		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'nelio-content' ), '1.0.0' ); // @codingStandardsIgnoreLine

	}//end __wakeup()


	/**
	 * Returns the single instance of this class.
	 *
	 * @return Nelio_Content the single instance of this class.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}//end if

		return self::$_instance;

	}//end instance()

	/**
	 * Defines the constants.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function define_constants() {

		define( 'NELIO_CONTENT_API_URL', 'https://api.neliocontent.com/v1' );
		define( 'NELIO_CONTENT_API_URL_WITHOUT_SNI', 'https://neliosoftware.com/proxy/content-api/v1' );

		define( 'NELIO_CONTENT_ADMIN_DIR', NELIO_CONTENT_DIR . '/admin' );
		define( 'NELIO_CONTENT_PUBLIC_DIR', NELIO_CONTENT_DIR . '/public' );
		define( 'NELIO_CONTENT_INCLUDES_DIR', NELIO_CONTENT_DIR . '/includes' );

		define( 'NELIO_CONTENT_ADMIN_URL', NELIO_CONTENT_URL . '/admin' );
		define( 'NELIO_CONTENT_INCLUDES_URL', NELIO_CONTENT_URL . '/includes' );
		define( 'NELIO_CONTENT_PUBLIC_URL', NELIO_CONTENT_URL . '/public' );

	}//end define_constants()

	/**
	 * What type of request is this?
	 *
	 * @param string $type Values can be: ajax, frontend, or admin.
	 *
	 * @return bool whether the request is of the specified type or not.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function is_request( $type ) {

		switch ( $type ) {
			case 'admin':
				return is_admin();
			case 'ajax':
				return defined( 'DOING_AJAX' );
			case 'cron':
				return defined( 'DOING_CRON' );
			case 'frontend':
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
			default:
				return false;
		}//end switch

	}//end is_request()

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function load_dependencies() {

		require_once NELIO_CONTENT_INCLUDES_DIR . '/class-nelio-content-autoloader.php';
		new Nelio_Content_Autoloader();

		require_once NELIO_CONTENT_INCLUDES_DIR . '/utils/nelio-content-api-errors.php';
		require_once NELIO_CONTENT_INCLUDES_DIR . '/utils/nelio-content-reference-functions.php';
		require_once NELIO_CONTENT_INCLUDES_DIR . '/utils/nelio-content-subscription-functions.php';

		Nelio_Content_Settings::instance();

		Nelio_Content_Install::init();
		Nelio_Content_Updater::init();

		if ( $this->is_request( 'admin' ) ) {
			Nelio_Content_Admin::set_plugin_info( $this->get_plugin_name(), $this->get_version() );
		}//end if

		if ( $this->is_request( 'frontend' ) ) {
			Nelio_Content_Public::set_plugin_info( $this->get_plugin_name(), $this->get_version() );
		}//end if

	}//end load_dependencies()

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Nelio_Content_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function set_locale() {

		$plugin_i18n = new Nelio_Content_i18n();
		$plugin_i18n->set_domain( $this->get_plugin_name() );

		add_action( 'plugins_loaded', array( $plugin_i18n, 'load_plugin_textdomain' ) );

	}//end set_locale()

	/**
	 * Adds the hooks required by the setup process.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function define_setup_hooks() {

		$setup = new Nelio_Content_Setup( $this->get_plugin_name(), $this->get_version() );
		add_action( 'wp_loaded', array( $setup, 'add_admin_menus' ) );
		add_action( 'admin_enqueue_scripts', array( $setup, 'enqueue_styles' ) );

		if ( $this->is_request( 'ajax' ) ) {

			$ajax_api = new Nelio_Content_Account_Ajax_API();
			add_action( 'wp_loaded', array( $ajax_api, 'register_ajax_callbacks' ) );

		}//end if

	}//end define_setup_hooks()

	/**
	 * Adds the hooks required by the cleaner object.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function define_cleaner_hooks() {

		$cleaner = new Nelio_Content_Cleaner( $this->get_plugin_name(), $this->get_version() );

		add_action( 'admin_enqueue_scripts', array( $cleaner, 'enqueue_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $cleaner, 'enqueue_scripts' ) );

		add_action( 'admin_footer', array( $cleaner, 'add_dialog_partials' ) );

		add_filter( 'plugin_action_links', array( $cleaner, 'add_cleaning_option' ), 10, 2 );
		add_filter( 'network_admin_plugin_action_links', array( $cleaner, 'add_cleaning_option' ), 10, 2 );

		add_action( 'wp_ajax_nelio_content_clean_and_deactivate', array( $cleaner, 'clean_database' ) );
		add_action( 'wp_ajax_nelio_content_cancel_account_clean_and_deactivate', array( $cleaner, 'clean_database_and_cancel_account' ) );

	}//end define_cleaner_hooks()

	/**
	 * Register the main hooks related to the admin area functionality of the plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function define_admin_hooks() {

		// If the plugin is configured, we can load everything else.
		$reference_post_type = Nelio_Content_Reference_Post_Type_Register::instance();
		$reference_post_type->define_admin_hooks();

		$notifications = Nelio_Content_Notifications::instance();
		$notifications->define_admin_hooks();

		$plugin_admin = Nelio_Content_Admin::instance();
		add_action( 'wp_loaded', array( Nelio_Content_Settings::instance(), 'define_admin_hooks' ) );
		add_action( 'wp_loaded', array( $plugin_admin, 'add_admin_menus' ) );

		add_action( 'admin_enqueue_scripts', array( $plugin_admin, 'enqueue_scripts' ), 99 );
		add_action( 'enqueue_block_editor_assets', array( $plugin_admin, 'enqueue_gutenberg_assets' ), 99 );

		add_action( 'admin_enqueue_scripts', array( $plugin_admin, 'enqueue_styles' ), 99 );
		add_action( 'admin_enqueue_scripts', array( $plugin_admin, 'enqueue_wp_default_style_fix' ), 99999 );

		if ( ! nc_use_editorial_calendar_only() ) {

			$plugin_admin->add_meta_boxes();

			$aux = new Nelio_Content_TinyMce_Extensions();
			$aux->define_admin_hooks();

			$aux = new Nelio_Content_External_Featured_Image_Admin();
			$aux->define_admin_hooks();

			$aux = Nelio_Content_Media_Menus::instance();
			$aux->set_plugin_info( $this->get_plugin_name(), $this->get_version() );
			$aux->define_admin_hooks();

			$aux = new Nelio_Content_Reshare_Bulk_Action();
			add_action( 'admin_init', array( $aux, 'define_admin_hooks' ) );

		}//end if

		if ( $this->is_request( 'ajax' ) ) {

			$ajax_api = new Nelio_Content_Account_Ajax_API();
			add_action( 'wp_loaded', array( $ajax_api, 'register_ajax_callbacks' ) );

			$ajax_api = new Nelio_Content_Generic_Ajax_API();
			add_action( 'wp_loaded', array( $ajax_api, 'register_ajax_callbacks' ) );

			$ajax_api = new Nelio_Content_Post_Ajax_API();
			add_action( 'wp_loaded', array( $ajax_api, 'register_ajax_callbacks' ) );

			$ajax_api = new Nelio_Content_Reference_Ajax_API();
			add_action( 'wp_loaded', array( $ajax_api, 'register_ajax_callbacks' ) );

			$ajax_api = new Nelio_Content_User_Ajax_API();
			add_action( 'wp_loaded', array( $ajax_api, 'register_ajax_callbacks' ) );

			$ajax_api = new Nelio_Content_Analytics_Ajax_API();
			add_action( 'wp_loaded', array( $ajax_api, 'register_ajax_callbacks' ) );

			$ajax_api = new Nelio_Content_Feeds_Ajax_API();
			add_action( 'wp_loaded', array( $ajax_api, 'register_ajax_callbacks' ) );

		}//end if

	}//end define_admin_hooks()

	/**
	 * Register the main hooks related to the public-facing functionality of the plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function define_public_hooks() {

		if ( ! nc_get_site_id() ) {
			return;
		}//end if

		$plugin_public = Nelio_Content_Public::instance();

		add_action( 'admin_bar_menu', array( $plugin_public, 'add_calendar_in_admin_bar' ), 99 );
		add_filter( 'the_content', array( $plugin_public, 'remove_share_blocks' ), 99 );

		$aux = new Nelio_Content_External_Featured_Image_Public();
		$aux->define_public_hooks();

	}//end define_public_hooks()

	/**
	 * Register some hooks that are supposed to run under all circumstances.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function define_universal_hooks() {

		add_action( 'wp_loaded', array( $this, 'maybe_add_missing_wordpress_functions' ) );

		if ( ! nc_get_site_id() ) {
			return;
		}//end if

		add_action( 'plugins_loaded', array( $this, 'add_settings_based_universal_hooks' ) );

		if ( nc_use_editorial_calendar_only() ) {
			return;
		}//end if

		add_action( 'init', array( $this, 'listen_to_site_changes' ) );
		add_action( 'save_post', array( $this, 'update_post_in_cloud' ) );
		add_filter( 'init', array( $this, 'add_custom_tags_to_post_content' ) );

		add_filter( 'cron_schedules', array( $this, 'add_cron_intervals' ) );

		$aux = Nelio_Content_Analytics_Helper::instance();
		add_action( 'wp_update_comment_count', array( $aux, 'update_comment_count' ), 10, 3 );
		add_action( 'init', array( $aux, 'manage_cron_tasks' ) );

		$aux = Nelio_Content_Auto_Sharer::instance();
		add_action( 'init', array( $aux, 'manage_cron_tasks' ) );
		add_action( 'init', array( $aux, 'add_auto_timeline_hooks' ) );

		$aux = Nelio_Content_Notifications::instance();
		$aux->define_universal_hooks();

		// Events for resynchronizing posts with our cloud.
		add_action( 'nc_update_post_in_cloud', array( $this, 'update_post_in_cloud' ) );

	}//end define_universal_hooks()

	/**
	 * This function adds some additional hooks that depend on the current
	 * settings, which are only available after plugins_loaded.
	 *
	 * @since  1.1.10
	 * @access public
	 */
	public function add_settings_based_universal_hooks() {

		$settings = Nelio_Content_Settings::instance();

		if ( $settings->get( 'use_custom_post_statuses' ) ) {
			$aux = Nelio_Content_Post_Statuses::instance();
			$aux->define_hooks();
		}//end if

		if ( $settings->get( 'use_ics_subscription' ) ) {
			$aux = Nelio_Content_Calendar_Helper::instance();
			$aux->define_hooks();
		}//end if

		if ( nc_use_editorial_calendar_only() ) {
			return;
		}//end if

		$post_types = $settings->get( 'calendar_post_types' );
		foreach ( $post_types as $post_type ) {
			add_action( 'publish_' . $post_type, array( $this, 'update_post_in_cloud' ) );
		}//end foreach

	}//end add_settings_based_universal_hooks()

	/**
	 * Includes several files that implement functions for solving compatibility issues.
	 *
	 * @since  1.0.5
	 * @access private
	 */
	private function add_compat_fixes() {

		require_once NELIO_CONTENT_INCLUDES_DIR . '/compat/divi.php';
		require_once NELIO_CONTENT_INCLUDES_DIR . '/compat/pagefrog.php';
		require_once NELIO_CONTENT_INCLUDES_DIR . '/compat/wpml.php';

	}//end add_compat_fixes()

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return string The name of the plugin.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function get_plugin_name() {

		return $this->plugin_name;

	}//end get_plugin_name()

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return string The version number of the plugin.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function get_version() {

		return $this->version;

	}//end get_version()

	/**
	 * Fixes compatibility issues with older WordPress versions.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function maybe_add_missing_wordpress_functions() {

		require_once NELIO_CONTENT_INCLUDES_DIR . '/utils/nelio-content-missing-wordpress-functions.php';

	}//end maybe_add_missing_wordpress_functions()

	/**
	 * Callback function used whenever the post is saved. It updates the info in AWS.
	 *
	 * @param integer $post_id the ID of the post being saved.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function update_post_in_cloud( $post_id ) {

		// If it's a revision or an autosave, do nothing.
		if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
			return;
		}//end if

		// If we don't have social profiles, we don't need to know anything about the posts.
		if ( ! get_option( 'nc_has_social_profiles' ) ) {
			return;
		}//end if

		// Only sync posts whose type is controlled by the plugin.
		$settings = Nelio_Content_Settings::instance();
		if ( ! in_array( get_post_type( $post_id ), $settings->get( 'calendar_post_types' ), true ) ) {
			return;
		}//end if

		// If everything's OK, save it.
		$post_helper = Nelio_Content_Post_Helper::instance();
		$post = $post_helper->post_to_aws_json( $post_id );
		if ( empty( $post ) ) {
			return;
		}//end if

		if ( ! $post_helper->has_post_changed_since_last_sync( $post_id ) ) {
			return;
		}//end if

		$attempts = get_post_meta( $post_id, '_nc_cloud_sync_attempts', true );
		if ( empty( $attempts ) ) {
			$attempts = 0;
		}//end if
		++$attempts;

		$synched = $this->sync_post_in_cloud( $post_id, $post );
		if ( ! $synched && 3 >= $attempts ) {
			update_post_meta( $post_id, '_nc_cloud_sync_attempts', $attempts );
			wp_schedule_single_event( time() + 30, 'nc_update_post_in_cloud', array( $post_id ) );
		} else {
			delete_post_meta( $post_id, '_nc_cloud_sync_attempts' );
			$post_helper->mark_post_as_synched( $post_id );
		}//end if

	}//end update_post_in_cloud()

	/**
	 * Updates post information in AWS.
	 *
	 * @param integer $post_id the ID of the post being saved.
	 * @param array   $post    the JSON file that AWS expects.
	 *
	 * @return boolean whether the post was updated in the cloud or not.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	private function sync_post_in_cloud( $post_id, $post ) {

		$settings = Nelio_Content_Settings::instance();
		$data = array(
			'method'    => 'PUT',
			'timeout'   => 30,
			'sslverify' => ! $settings->get( 'uses_proxy' ),
			'headers'   => array(
				'Authorization' => 'Bearer ' . nc_generate_api_auth_token(),
				'accept'        => 'application/json',
				'content-type'  => 'application/json',
			),
			'body'      => wp_json_encode( $post ),
		);

		$url = sprintf(
			nc_get_api_url( '/site/%s/post/%s', 'wp' ),
			nc_get_site_id(), $post_id
		);
		$response = wp_remote_request( $url, $data );

		if ( is_wp_error( $response ) ) {
			return false;
		}//end if

		if ( ! isset( $response['response'] )
			|| ! isset( $response['response']['code'] )
			|| 200 !== $response['response']['code'] ) {
			return false;
		}//end if

		return true;

	}//end sync_post_in_cloud()

	/**
	 * Adds the filters and actions required for syncing site information.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function listen_to_site_changes() {

		add_filter( 'pre_update_option_gmt_offset', array( $this, 'on_site_option_updated' ), 10, 2 );
		add_filter( 'pre_update_option_timezone_string', array( $this, 'on_site_option_updated' ), 10, 2 );
		add_filter( 'pre_update_option_WPLANG', array( $this, 'on_site_option_updated' ), 10, 2 );
		add_filter( 'pre_update_option_home', array( $this, 'on_site_option_updated' ), 10, 2 );

		add_action( 'shutdown', array( $this, 'maybe_sync_site' ), 10, 2 );

	}//end listen_to_site_changes()

	/**
	 * Controls whether a relevant site option has been updated, so that we can sync it in AWS.
	 *
	 * @param mixed $new_value the new value of an option.
	 * @param mixed $old_value the old value of an option.
	 *
	 * @return mixed the new value of the option.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function on_site_option_updated( $new_value, $old_value ) {

		if ( $new_value !== $old_value ) {
			$this->are_site_options_updated = true;
		}//end if
		return $new_value;

	}//end on_site_option_updated()

	/**
	 * Updates site options in AWS.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function maybe_sync_site() {

		if ( ! $this->are_site_options_updated ) {
			return;
		}//end if

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
				'url'      => home_url(),
				'timezone' => nc_get_timezone(),
				'language' => nc_get_language(),
			) ),
		);

		$url = nc_get_api_url( '/site/' . nc_get_site_id(), 'wp' );
		wp_remote_request( $url, $data );

	}//end maybe_sync_site()

	/**
	 * Add custom cron intervals.
	 *
	 * @param array $schedules List of schedules.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function add_cron_intervals( $schedules ) {

		$schedules['nc_four_hours'] = array(
			'interval' => 60 * 60 * 4,
			'display'  => esc_html_x( 'Every Four Hours (Nelio Content)', 'text', 'nelio-content' ),
		);
		return $schedules;

	}//end add_cron_intervals()

	/**
	 * Adds the ncshare tag to the list of valid tags in post content.
	 *
	 * @since  1.3.4
	 * @access public
	 */
	public function add_custom_tags_to_post_content() {

		global $allowedposttags;
		$allowedposttags['ncshare'] = array( 'class' => true ); // @codingStandardsIgnoreLine

	}//end add_custom_tags_to_post_content()

}//end class

// @codingStandardsIgnoreStart
/**
 * Returns the single instance of the Nelio_Content class.
 *
 * @return Nelio_Content The single instance of the Nelio_Content class.
 *
 * @since 1.0.0
 */
function Nelio_Content() {
	return Nelio_Content::instance();
}//end Nelio_Content()
// @codingStandardsIgnoreEnd
