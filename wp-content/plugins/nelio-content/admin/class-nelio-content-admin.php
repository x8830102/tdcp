<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 *
 * @SuppressWarnings( PHPMD.ExcessiveClassComplexity )
 */
class Nelio_Content_Admin {

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
	 * @var    string $plugin_name
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
	 * @access private
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
	 * Includes all required classes.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function add_admin_menus() {

		if ( ! defined( 'DOING_AJAX' ) ) {
			$admin_menus = new Nelio_Content_Admin_Menus();
			$admin_menus->define_admin_hooks();
		}//end if

	}//end add_admin_menus()

	/**
	 * Registers all the library scripts that are used throughout the admin
	 * script.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function register_styles() {

		// Dashicons from WordPress 4.5.
		wp_register_style(
			'dashicons-wp45',
			NELIO_CONTENT_ADMIN_URL . '/lib/dashicons/css/dashicons.css',
			array(),
			'4.5.0',
			'all'
		);

		// Select2's default stylesheet.
		wp_register_style(
			'ncselect2',
			NELIO_CONTENT_ADMIN_URL . '/lib/ncselect2/css/ncselect2.min.css',
			array(),
			'4.0.1',
			'all'
		);

		// Atwho style (to tweak the appearance of mention search).
		wp_register_style(
			'atwho',
			NELIO_CONTENT_ADMIN_URL . '/lib/atwho/css/atwho.min.css',
			array(),
			'1.5.4',
			'all'
		);

	}//end register_styles()

	/**
	 * Enqueue the stylesheets for the admin area.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function enqueue_styles() {

		// These styles can only be included if we're on a Nelio Content's page.
		if ( ! $this->is_a_nelio_content_page() ) {
			return;
		}//end if

		$this->register_styles();

		$settings = Nelio_Content_Settings::instance();

		if ( $this->is_current_screen( $settings->get( 'calendar_post_types' ) ) && ! nc_use_editorial_calendar_only() ) {

			// Featured Images Meta Box script.
			wp_enqueue_style(
				'nelio-content-post',
				NELIO_CONTENT_ADMIN_URL . '/css/post.min.css',
				array( 'dashicons-wp45', 'ncselect2', 'wp-jquery-ui-dialog', 'atwho' ),
				$this->version,
				'all'
			);

		}//end if

		if ( $this->is_current_screen( '_nc-post-with-featured-image' ) && ! nc_use_editorial_calendar_only() ) {

			// Featured Images Meta Box script.
			wp_enqueue_style(
				'nelio-content-featured-images',
				NELIO_CONTENT_ADMIN_URL . '/css/featured-image.min.css',
				array( 'dashicons-wp45' ),
				$this->version,
				'all'
			);

		}//end if

		if ( $this->is_current_screen( '_nc-calendar-page' ) ) {

			// Load Fullcalendar's default stylesheet.
			wp_enqueue_style(
				'nelio-content-calendar',
				NELIO_CONTENT_ADMIN_URL . '/css/calendar.min.css',
				array( 'dashicons-wp45', 'ncselect2', 'wp-jquery-ui-dialog', 'atwho' ),
				$this->version,
				'all'
			);

			if ( nc_use_editorial_calendar_only() ) {
				wp_enqueue_style(
					'nelio-content-calendar-only',
					NELIO_CONTENT_ADMIN_URL . '/css/calendar-only.min.css',
					array( 'nelio-content-calendar' ),
					$this->version,
					'all'
				);
			}//end if

		}//end if

		if ( $this->is_current_screen( '_nc-feeds' ) ) {

			// Load feeds page's default stylesheet.
			wp_enqueue_style(
				'nelio-content-feeds',
				NELIO_CONTENT_ADMIN_URL . '/css/feeds.min.css',
				array( 'dashicons-wp45', 'ncselect2', 'wp-jquery-ui-dialog' ),
				$this->version,
				'all'
			);

		}//end if

		if ( $this->is_current_screen( '_nc-analytics' ) ) {

			// Load analytics page's default stylesheet.
			wp_enqueue_style(
				'nelio-content-analytics',
				NELIO_CONTENT_ADMIN_URL . '/css/analytics.min.css',
				array( 'dashicons-wp45', 'ncselect2', 'wp-jquery-ui-dialog', 'atwho' ),
				$this->version,
				'all'
			);

		}//end if

		if ( $this->is_current_screen( '_nc-account' ) ) {

			// Load account page's default stylesheet.
			wp_enqueue_style(
				'nelio-content-account',
				NELIO_CONTENT_ADMIN_URL . '/css/account.min.css',
				array( 'dashicons-wp45', 'ncselect2', 'wp-jquery-ui-dialog' ),
				$this->version,
				'all'
			);

		}//end if

		if ( $this->is_current_screen( '_nc-settings' ) ) {

			// Load setting page's default stylesheet.
			wp_enqueue_style(
				'nelio-content-settings',
				NELIO_CONTENT_ADMIN_URL . '/css/settings.min.css',
				array( 'dashicons-wp45', 'ncselect2', 'wp-jquery-ui-dialog', 'wp-pointer', 'atwho' ),
				$this->version,
				'all'
			);

		}//end if

	}//end enqueue_styles()

	/**
	 * Enqueues stylesheet reset-defaults.css.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function enqueue_wp_default_style_fix() {

		if ( apply_filters( 'nelio_content_enqueue_wp_default_style_fix', true ) ) {

			// Load setting page's default stylesheet.
			wp_enqueue_style(
				'nelio-content-wp-style-fix',
				NELIO_CONTENT_ADMIN_URL . '/css/reset-defaults.min.css',
				array(),
				$this->version,
				'all'
			);

		}//end if

	}//end enqueue_wp_default_style_fix()

	/**
	 * Registers all the library scripts that are used throughout the admin
	 * script.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function register_scripts() {

		// Register DebugJS library.
		wp_register_script(
			'debugjs',
			NELIO_CONTENT_ADMIN_URL . '/lib/debug/js/debug.min.js',
			array(),
			'2.2.0'
		);

		// Register Select 2 script.
		wp_register_script(
			'ncselect2',
			NELIO_CONTENT_ADMIN_URL . '/lib/ncselect2/js/ncselect2.full.min.js',
			array( 'jquery' ),
			'4.0.1'
		);

		wp_register_script(
			'ncselect2-i18n',
			NELIO_CONTENT_ADMIN_URL . '/lib/ncselect2/js/i18n.js',
			array( 'ncselect2' ),
			'4.0.1'
		);

		// Register Moment JS, a library required by our calendar.
		wp_register_script(
			'ncmoment-js',
			NELIO_CONTENT_ADMIN_URL . '/lib/momentjs/js/moment-with-locales.min.js',
			array(),
			'2.12.0nelio'
		);
		wp_register_script(
			'ncmoment-timezone-js',
			NELIO_CONTENT_ADMIN_URL . '/lib/momentjs/js/moment-timezone.min.js',
			array( 'ncmoment-js' ),
			'0.5.3nelio'
		);

		// Latinize library.
		wp_register_script(
			'latinize',
			NELIO_CONTENT_ADMIN_URL . '/lib/latinize/js/latinize.min.js',
			array(),
			'2b7ee0c'
		);

		// Store library.
		wp_register_script(
			'ncstore-js',
			NELIO_CONTENT_ADMIN_URL . '/lib/storejs/js/store.min.js',
			array(),
			'1.3.20nelio'
		);

		// Twitter library.
		wp_register_script(
			'twitter-text',
			NELIO_CONTENT_ADMIN_URL . '/lib/twitter/js/twitter-text.min.js',
			array(),
			'3.0.1'
		);

		// Libraries for enhanced social message editor.
		wp_register_script(
			'caretjs',
			NELIO_CONTENT_ADMIN_URL . '/lib/caret/js/caret.min.js',
			array(),
			'0.2.1'
		);

		wp_register_script(
			'atwho',
			NELIO_CONTENT_ADMIN_URL . '/lib/atwho/js/atwho.min.js',
			array( 'caretjs' ),
			'1.5.4'
		);

		// Our backbone views.
		wp_register_script(
			'nelio-content-views',
			NELIO_CONTENT_ADMIN_URL . '/js/views.min.js',
			array( $this->plugin_name, 'backbone', 'underscore', 'ncmoment-timezone-js', 'jquery-ui-draggable', 'jquery-ui-droppable', 'jquery-ui-dialog' ),
			$this->version,
			true
		);

	}//end register_scripts()

	/**
	 * Enqueue the JavaScript for the admin area.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @SuppressWarnings( PHPMD.ExcessiveMethodLength )
	 */
	public function enqueue_scripts() {

		$this->register_scripts();

		// These admin JavaScripts can only be included if we're on a Nelio Content's page.
		if ( ! $this->is_a_nelio_content_page() ) {
			return;
		}//end if

		$settings = Nelio_Content_Settings::instance();

		wp_enqueue_script(
			$this->plugin_name,
			NELIO_CONTENT_ADMIN_URL . '/js/admin.min.js',
			array( 'jquery', 'backbone', 'underscore', 'debugjs', 'ncmoment-timezone-js', 'ncselect2-i18n', 'latinize', 'ncstore-js', 'jquery-ui-datepicker', 'jquery-ui-dialog', 'jquery-ui-tooltip', 'twitter-text', 'atwho' ),
			$this->version,
			false
		);

		wp_localize_script(
			$this->plugin_name,
			'NelioContent',
			$this->generate_js_object()
		);

		if ( $this->is_current_screen( '_nc-calendar-page' ) ) {

			// Enqueue media scripts for image selection.
			wp_enqueue_media();

			// Load calendar page customizations.
			wp_enqueue_script(
				'nelio-content-calendar-js',
				NELIO_CONTENT_ADMIN_URL . '/js/calendar.min.js',
				array( $this->plugin_name, 'nelio-content-views' ),
				$this->version,
				true
			);

		}//end if

		if ( $this->is_current_screen( '_nc-post-with-featured-image' ) && ! nc_use_editorial_calendar_only() ) {

			// Enqueue media scripts for image selection.
			wp_enqueue_media();

			// Featured Images Meta Box script.
			wp_enqueue_script(
				'nelio-content-featured-images',
				NELIO_CONTENT_ADMIN_URL . '/js/featured-image.min.js',
				array(),
				$this->version,
				true
			);

		}//end if

		if ( $this->is_current_screen( $settings->get( 'calendar_post_types' ) ) && ! nc_use_editorial_calendar_only() ) {

			// Load calendar page customizations.
			wp_enqueue_script(
				'nelio-content-post-js',
				NELIO_CONTENT_ADMIN_URL . '/js/post.min.js',
				array( $this->plugin_name, 'nelio-content-views' ),
				$this->version,
				true
			);

		}//end if

		if ( $this->is_current_screen( '_nc-feeds' ) ) {

			// Load feeds page customizations.
			wp_enqueue_script(
				'nelio-content-feeds-js',
				NELIO_CONTENT_ADMIN_URL . '/js/feeds.min.js',
				array( $this->plugin_name, 'nelio-content-views' ),
				$this->version,
				true
			);

		}//end if

		if ( $this->is_current_screen( '_nc-analytics' ) &&
				$settings->get( 'use_analytics' ) &&
				intval( get_option( 'nc_analytics_last_global_update' ) ) ) {

			// Enqueue media scripts for image selection.
			wp_enqueue_media();

			// Load analytics page customizations.
			wp_enqueue_script(
				'nelio-content-analytics-js',
				NELIO_CONTENT_ADMIN_URL . '/js/analytics.min.js',
				array( $this->plugin_name, 'nelio-content-views' ),
				$this->version,
				true
			);

		}//end if

		if ( $this->is_current_screen( '_nc-account' ) ) {

			// Load calendar page customizations.
			wp_enqueue_script(
				'nelio-content-account-js',
				NELIO_CONTENT_ADMIN_URL . '/js/account.min.js',
				array( $this->plugin_name ),
				$this->version,
				true
			);

		}//end if

		if ( $this->is_current_screen( '_nc-settings' ) ) {

			// Load Fullcalendar's default stylesheet.
			wp_enqueue_script(
				'nelio-content-settings-js',
				NELIO_CONTENT_ADMIN_URL . '/js/settings.min.js',
				array( $this->plugin_name, $settings->get_generic_script_name(), 'wp-pointer' ),
				$this->version,
				true
			);

			wp_localize_script(
				'nelio-content-settings-js',
				'NelioContentSettings',
				array(
					'isYoastSEOAvailable' => is_plugin_active( 'wordpress-seo/wp-seo.php' ) || is_plugin_active( 'wordpress-seo-premium/wp-seo-premium.php' ),
				)
			);

			if ( nc_use_editorial_calendar_only() ) {
				wp_dequeue_script( 'nelio-content-settings-js' );
			}//end if

		}//end if

	}//end enqueue_scripts()

	/**
	 * Enqueue the JavaScript for the Gutenberg editor.
	 *
	 * @since  1.6.3
	 * @access public
	 *
	 * @SuppressWarnings( PHPMD.ExcessiveMethodLength )
	 */
	public function enqueue_gutenberg_assets() {

		wp_enqueue_script(
			'nelio-content-editor',
			NELIO_CONTENT_ADMIN_URL . '/js/gutenberg.min.js',
			[ 'wp-i18n', 'wp-blocks', 'wp-edit-post', 'wp-element', 'wp-editor', 'wp-components', 'wp-data', 'wp-plugins', 'wp-edit-post', 'wp-api', 'wp-wordcount', 'nelio-content-post-js' ],
			$this->version,
			true
		);

		if ( function_exists( 'wp_set_script_translations' ) ) {
			wp_set_script_translations( 'nelio-content-editor', 'nelio-content' );
		}//end if

	}//end enqueue_gutenberg_assets()

	/**
	 * Callback for adding meta boxes in the post editor page.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function add_meta_boxes() {

		if ( ! nc_get_site_id() ) {
			return;
		}//end if

		/**
		 * Fires before Nelio Content adds its own meta boxes.
		 *
		 * @since 1.0.0
		 */
		do_action( 'nelio_content_pre_add_meta_boxes' );

		$meta_box = new Nelio_Content_Featured_Image_Meta_Box();
		$meta_box->init();

		$meta_box = new Nelio_Content_Social_Media_Meta_Box();
		$meta_box->init();

		$meta_box = new Nelio_Content_Links_Meta_Box();
		$meta_box->init();

		$meta_box = new Nelio_Content_Post_Analysis_Meta_Box_Partial();
		$meta_box->init();

		$meta_box = new Nelio_Content_Editorial_Tasks_Meta_Box();
		$meta_box->init();

		$meta_box = new Nelio_Content_Editorial_Comments_Meta_Box();
		$meta_box->init();

		$meta_box = new Nelio_Content_Notifications_Meta_Box();
		$meta_box->init();

		/**
		 * Fires after Nelio Content has added its own meta boxes.
		 *
		 * @since 1.0.0
		 */
		do_action( 'nelio_content_add_meta_boxes' );

	}//end add_meta_boxes()

	/**
	 * Returns whether the current screen is one of the specified screens.
	 *
	 * @param string|array $screen_options a single screen name or a list (array)
	 *                            of screen names. The options can either be an
	 *                            actual screen name (such as `nelio-content_page_nelio-content`),
	 *                            or a beautifed version of Nelio's screens
	 *                            (`calendar-page`).
	 *
	 * @return boolean whether the current screen is one of the specified screens.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @SuppressWarnings( PHPMD.CyclomaticComplexity )
	 */
	public function is_current_screen( $screen_options ) {

		$screen = get_current_screen();
		$post_types = get_post_types();

		if ( ! is_array( $screen_options ) ) {
			$screen_options = array( $screen_options );
		}//end if

		$simplified_screen_id = preg_replace( '/^.*_page_/', '', $screen->id );

		foreach ( $screen_options as $screen_option ) {

			// If the screen option matches the current screen ID, we return true.
			if ( $screen_option === $screen->id ) {
				return true;
			}//end if

			// If they don't, we check if the screen option is "beautified".
			switch ( $screen_option ) {

				case '_nc-calendar-page':
					return 'nelio-content' === $simplified_screen_id;

				case '_nc-feeds':
					return 'nelio-content-feeds' === $simplified_screen_id;

				case '_nc-analytics':
					return 'nelio-content-analytics' === $simplified_screen_id;

				case '_nc-account':
					return 'nelio-content-account' === $simplified_screen_id;

				case '_nc-settings':
					return 'nelio-content-settings' === $simplified_screen_id;

				case '_nc-post-with-featured-image':
					return in_array( $screen->id, $post_types, true ) && post_type_supports( $screen->id, 'thumbnail' );

			}//end switch

		}//end foreach

		return false;

	}//end is_current_screen()

	/**
	 * Returns whether the current screen is the new/edit screen of a post type.
	 *
	 * @return boolean whether the current screen the new/edit screen of a post type.
	 *
	 * @since  1.1.3
	 * @access public
	 */
	public function is_edit_screen() {

		$screen = get_current_screen();
		$post_types = get_post_types();
		return in_array( $screen->id, $post_types, true );

	}//end is_edit_screen()

	/**
	 * Returns whether the current screen is a Nelio Content's page or not.
	 *
	 * @return boolean whether the current screen is a Nelio Content's page or not.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function is_a_nelio_content_page() {

		$screen = get_current_screen();
		$settings = Nelio_Content_Settings::instance();

		if ( strpos( $screen->id, 'page_nelio-content' ) !== false ) {
			return true;
		}//end if

		if ( $this->is_current_screen( $settings->get( 'calendar_post_types' ) ) ) {
			return true;
		}//end if

		$post_types = get_post_types();
		if ( in_array( $screen->id, $post_types, true ) && post_type_supports( $screen->id, 'thumbnail' ) ) {
			return true;
		}//end if

		return false;

	}//end is_a_nelio_content_page()

	/**
	 * Returns a JavaScript object with all the data required by our plugin.
	 *
	 * @return array a JavaScript object with all the data required by our plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function generate_js_object() {

		$limits = nc_get_site_limits();
		$settings = Nelio_Content_Settings::instance();

		$subscription = nc_get_subscription();
		if ( $subscription ) {
			$subscription = $subscription['plan'];
		} else {
			$subscription = 'none';
		}//end if

		$result = array(
			'apiAuthToken'      => nc_generate_api_auth_token(),
			'apiUri'            => nc_get_api_url( '/site/' . nc_get_site_id(), 'browser' ),
			'blogUrl'           => get_home_url(),
			'categories'        => $this->get_root_categories(),
			'collections'       => array(), // This object is populated by the JS.
			'hasSocialProfiles' => get_option( 'nc_has_social_profiles' ),
			'helpers'           => array(), // This object is populated by the JS.
			'i18n'              => $this->get_i18n_object(),
			'subscription'      => $subscription,
			'models'            => array(), // This object is populated by the JS.
			'networkMetas'      => include( NELIO_CONTENT_ADMIN_DIR . '/data/network-metas.php' ),
			'postTypes'         => $this->get_calendar_post_types(),
			'productsUri'       => nc_get_api_url( '/fastspring/products', 'browser' ),
			'profiles'          => false,   // This Backbone collection is populated by the JS.
			'userId'            => get_current_user_id(),
			'users'             => $this->get_current_user(),
			'version'           => $this->version,
			'views'             => array(), // This object is populated by the JS.
			'wpBlogId'          => get_current_blog_id(),
			'postAnalysis'      => array(
				'enabled'              => nc_is_current_user( $settings->get( 'qa_required_role' ) ),
				'isYoastSeoIntegrated' => $settings->get( 'qa_is_yoast_seo_integrated' ),
				'minPostLength'        => absint( $settings->get( 'qa_min_word_count' ) ),
			),
			'analytics' => array(
				'gaViewId' => $settings->get( 'google_analytics_view' ),
			),
			'misc' => array(
				'maxProfiles'                     => $limits['maxProfiles'],
				'maxProfilesPerNetwork'           => $limits['maxProfilesPerNetwork'],
				'usesCustomAutomationFrequencies' => $settings->get( 'use_custom_automation_frequencies' ),
			),
			'pages' => array(
				'account'               => admin_url( 'admin.php?page=nelio-content-account' ),
				'settings'              => admin_url( 'admin.php?page=nelio-content-settings' ),
				'socialProfileSettings' => admin_url( 'admin.php?page=nelio-content-settings&tab=social-profiles' ),
			),
			'tmp' => array(),
		);

		if ( ! $this->is_edit_screen() ) {

			/**
			 * Last published post cannot be loaded on post.php, because the loop for
			 * retrieving said post "breaks" the editor.
			 *
			 * @see https://core.trac.wordpress.org/ticket/18408
			 */
			$newest_post = $this->get_latest_published_post();
			if ( ! empty( $newest_post ) ) {
				$result['lastPublishedPost'] = $newest_post;
			}//end if

		}//end if

		$post_helper = Nelio_Content_Post_Helper::instance();
		$result['misc']['nonReferenceDomains'] = $post_helper->get_non_reference_domains();

		if ( nc_use_editorial_calendar_only() ) {
			$result['useEditorialCalendarOnly'] = true;
		}//end if

		return $result;

	}//end generate_js_object()

	/**
	 * Returns the latest published post, or false if there's none.
	 *
	 * This object is used in the calendar page, during social message creation.
	 *
	 * WARNING: Because of https://core.trac.wordpress.org/ticket/18408, this
	 * function doesn't work on `post.php`. However, we don't need the latest
	 * post in that page (this post is only needed in the calendar page), so
	 * the function contains a save guard that prevents a nested loop from
	 * running when in said page.
	 *
	 * @return array the latest published post, or false if there's none.
	 *
	 * @since  1.0.0
	 * @access private
	 *
	 * @SuppressWarnings( PHPMD.ShortVariableName )
	 */
	private function get_latest_published_post() {

		// Save guard for https://core.trac.wordpress.org/ticket/18408.
		$settings = Nelio_Content_Settings::instance();
		if ( $this->is_current_screen( $settings->get( 'calendar_post_types' ) ) ) {
			return false;
		}//end if

		// Get latest published post.
		$lp_query = new WP_Query( array(
			'posts_per_page' => 1,
			'post_status'    => 'publish',
		) );

		// If there are no published posts, leave.
		if ( ! $lp_query->have_posts() ) {
			wp_reset_postdata();
			return false;
		}//end if

		// If there's one, return the result.
		$lp_query->the_post();
		$post_helper = Nelio_Content_Post_Helper::instance();
		$result = $post_helper->post_to_json( get_the_ID() );

		// Reset lp_query.
		wp_reset_postdata();

		return $result;

	}//end get_latest_published_post()

	/**
	 * Returns the list of categories that have no parents.
	 *
	 * @return array the list of categories that have no parents.
	 *
	 * @since  1.3.0
	 * @access private
	 *
	 * @SuppressWarnings( PHPMD.ShortVariableName, PHPMD.ExcessiveMethodLength )
	 */
	private function get_root_categories() {

		$result = array();

		$categories = get_categories( array( 'hide_empty' => false ) );
		foreach ( $categories as $cat ) {
			if ( $cat->parent > 0 ) {
				continue;
			}//end if
			array_push( $result, array(
				'id'    => $cat->term_id,
				'name'  => $cat->slug,
				'label' => $cat->name,
			)	);
		}//end foreach

		return $result;

	}//end get_root_categories()

	/**
	 * Returns an i18n object, which is basically a set of pairs {key, i18n string}.
	 *
	 * @return array i18n object, which is basically a set of pairs {key, i18n string}.
	 *
	 * @since  1.0.0
	 * @access private
	 *
	 * @SuppressWarnings( PHPMD.ShortVariableName, PHPMD.ExcessiveMethodLength )
	 */
	private function get_calendar_post_types() {

		$result = array();

		$settings = Nelio_Content_Settings::instance();
		$ptnames = $settings->get( 'calendar_post_types' );
		foreach ( $ptnames as $ptname ) {

			$post_type = get_post_type_object( $ptname );
			if ( ! $post_type || is_wp_error( $post_type ) ) {
				continue;
			}//end if

			$labels = $post_type->labels;
			array_push( $result, array(
				'name'   => $ptname,
				'labels' => array(
					'plural'     => $labels->name,
					'singular'   => $labels->singular_name,
					'addTitle'   => $labels->add_new_item,
					'addAction'  => $labels->add_new_item,
					'allItems'   => $labels->all_items,
					'editTitle'  => $labels->edit_item,
					'editAction' => $labels->edit_item,
					'viewAction' => $labels->view_item,
				),
				'supportsFeaturedImage' => post_type_supports( $ptname, 'thumbnail' ),
			) );

		}//end foreach

		return $result;

	}//end get_calendar_post_types()

	/**
	 * Returns an i18n object, which is basically a set of pairs {key, i18n string}.
	 *
	 * @return array i18n object, which is basically a set of pairs {key, i18n string}.
	 *
	 * @since  1.0.0
	 * @access private
	 *
	 * @SuppressWarnings( PHPMD.ShortVariableName, PHPMD.ExcessiveMethodLength )
	 */
	private function get_i18n_object() {

		return include( NELIO_CONTENT_ADMIN_DIR . '/data/i18n.php' );

	}//end get_i18n_object()

	/**
	 * Returns a list with the current user only.
	 *
	 * @return array a list with the current user only.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function get_current_user() {

		$aux = Nelio_Content_User_Helper::instance();
		return array( $aux->user_to_json( wp_get_current_user() ) );

	}//end get_current_user()

	/**
	 * Helper function used for sorting an array of users.
	 *
	 * @param object $a A user.
	 * @param object $b Another user.
	 *
	 * @return integer Returns < 0 if str1 is less than str2; > 0 if str1 is greater than str2, and 0 if they are equal.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @SuppressWarnings( PHPMD.ShortVariableName )
	 */
	public function compare_users( $a, $b ) {

		return strcasecmp( $a['name'], $b['name'] );

	}//end compare_users()

}//end class
