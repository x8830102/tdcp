<?php
/**
 * The file that defines an autoloader class, for automatically loading classes.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.4.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * Nelio Content's class autoloader.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/lib/settings
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.4.5
 */
class Nelio_Content_Autoloader {

	/**
	 * The Constructor.
	 *
	 * @since  1.4.5
	 * @access public
	 */
	public function __construct() {

		if ( function_exists( '__autoload' ) ) {
			spl_autoload_register( '__autoload' );
		}//end if

		spl_autoload_register( array( $this, 'autoload' ) );

	}//end __construct()

	/**
	 * Loads a class file. Returns whether the file has been successfully loaded.
	 *
	 * @param string $path The class file to be loaded.
	 *
	 * @return bool Whether the file has been successfully loaded.
	 *
	 * @since  1.4.5
	 * @access private
	 */
	private function load_file( $path ) {

		if ( $path && is_readable( $path ) ) {
			include_once( $path );
			return true;
		}//end if

		return false;

	}//end load_file()

	/**
	 * Auto-load Nelio_Content classes on demand to reduce memory consumption.
	 *
	 * @param string $class The class to be loaded.
	 *
	 * @since  1.4.5
	 * @access public
	 */
	public function autoload( $class ) {

		if ( strpos( $class, 'Nelio_Content_' ) !== 0 ) {
			return;
		}//end if

		$dictionary = array();
		$dictionary['Nelio_Content_Account_Ajax_API'] = NELIO_CONTENT_DIR . '/admin/class-nelio-content-account-ajax-api.php';
		$dictionary['Nelio_Content_Admin_Menus'] = NELIO_CONTENT_DIR . '/admin/class-nelio-content-admin-menus.php';
		$dictionary['Nelio_Content_Admin'] = NELIO_CONTENT_DIR . '/admin/class-nelio-content-admin.php';
		$dictionary['Nelio_Content_Analytics_Ajax_API'] = NELIO_CONTENT_DIR . '/admin/class-nelio-content-analytics-ajax-api.php';
		$dictionary['Nelio_Content_Calendar_Post_Type_Setting'] = NELIO_CONTENT_DIR . '/admin/class-nelio-content-calendar-post-type-setting.php';
		$dictionary['Nelio_Content_Cleaner'] = NELIO_CONTENT_DIR . '/admin/class-nelio-content-cleaner.php';
		$dictionary['Nelio_Content_Editorial_Comments_Meta_Box'] = NELIO_CONTENT_DIR . '/admin/class-nelio-content-editorial-comments-meta-box.php';
		$dictionary['Nelio_Content_Editorial_Tasks_Meta_Box'] = NELIO_CONTENT_DIR . '/admin/class-nelio-content-editorial-tasks-meta-box.php';
		$dictionary['Nelio_Content_External_Featured_Image_Admin'] = NELIO_CONTENT_DIR . '/admin/class-nelio-content-external-featured-image-admin.php';
		$dictionary['Nelio_Content_Featured_Image_Meta_Box'] = NELIO_CONTENT_DIR . '/admin/class-nelio-content-featured-image-meta-box.php';
		$dictionary['Nelio_Content_Feeds_Ajax_API'] = NELIO_CONTENT_DIR . '/admin/class-nelio-content-feeds-ajax-api.php';
		$dictionary['Nelio_Content_Generic_Ajax_API'] = NELIO_CONTENT_DIR . '/admin/class-nelio-content-generic-ajax-api.php';
		$dictionary['Nelio_Content_Google_Analytics_Setting'] = NELIO_CONTENT_DIR . '/admin/class-nelio-content-google-analytics-setting.php';
		$dictionary['Nelio_Content_ICS_Calendar_Setting'] = NELIO_CONTENT_DIR . '/admin/class-nelio-content-ics-calendar-setting.php';
		$dictionary['Nelio_Content_Link_Meta'] = NELIO_CONTENT_DIR . '/admin/class-nelio-content-link-meta.php';
		$dictionary['Nelio_Content_Links_Meta_Box'] = NELIO_CONTENT_DIR . '/admin/class-nelio-content-links-meta-box.php';
		$dictionary['Nelio_Content_Media_Menus'] = NELIO_CONTENT_DIR . '/admin/class-nelio-content-media-menus.php';
		$dictionary['Nelio_Content_Notifications_Meta_Box'] = NELIO_CONTENT_DIR . '/admin/class-nelio-content-notifications-meta-box.php';
		$dictionary['Nelio_Content_Post_Ajax_API'] = NELIO_CONTENT_DIR . '/admin/class-nelio-content-post-ajax-api.php';
		$dictionary['Nelio_Content_Post_Analysis_Meta_Box_Partial'] = NELIO_CONTENT_DIR . '/admin/class-nelio-content-post-analysis-meta-box-partial.php';
		$dictionary['Nelio_Content_Post_Helper'] = NELIO_CONTENT_DIR . '/admin/class-nelio-content-post-helper.php';
		$dictionary['Nelio_Content_Reference_Ajax_API'] = NELIO_CONTENT_DIR . '/admin/class-nelio-content-reference-ajax-api.php';
		$dictionary['Nelio_Content_Reshare_Bulk_Action'] = NELIO_CONTENT_DIR . '/admin/class-nelio-content-reshare-bulk-action.php';
		$dictionary['Nelio_Content_Setup'] = NELIO_CONTENT_DIR . '/admin/class-nelio-content-setup.php';
		$dictionary['Nelio_Content_Social_Media_Meta_Box'] = NELIO_CONTENT_DIR . '/admin/class-nelio-content-social-media-meta-box.php';
		$dictionary['Nelio_Content_TinyMce_Extensions'] = NELIO_CONTENT_DIR . '/admin/class-nelio-content-tinymce-extensions.php';
		$dictionary['Nelio_Content_User_Ajax_API'] = NELIO_CONTENT_DIR . '/admin/class-nelio-content-user-ajax-api.php';
		$dictionary['Nelio_Content_User_Helper'] = NELIO_CONTENT_DIR . '/admin/class-nelio-content-user-helper.php';
		$dictionary['Nelio_Content_Meta_Box'] = NELIO_CONTENT_DIR . '/admin/interface-nelio-content-meta-box.php';
		$dictionary['Nelio_Content_Analytics_Helper'] = NELIO_CONTENT_DIR . '/includes/class-nelio-content-analytics-helper.php';
		$dictionary['Nelio_Content_Auto_Sharer'] = NELIO_CONTENT_DIR . '/includes/class-nelio-content-auto-sharer.php';
		$dictionary['Nelio_Content_Calendar_Helper'] = NELIO_CONTENT_DIR . '/includes/class-nelio-content-calendar-helper.php';
		$dictionary['Nelio_Content_External_Featured_Image_Helper'] = NELIO_CONTENT_DIR . '/includes/class-nelio-content-external-featured-image-helper.php';
		$dictionary['Nelio_Content_Notifications'] = NELIO_CONTENT_DIR . '/includes/class-nelio-content-notifications.php';
		$dictionary['Nelio_Content_Post_Statuses'] = NELIO_CONTENT_DIR . '/includes/class-nelio-content-post-statuses.php';
		$dictionary['Nelio_Content_Reference'] = NELIO_CONTENT_DIR . '/includes/class-nelio-content-reference.php';
		$dictionary['Nelio_Content_Settings'] = NELIO_CONTENT_DIR . '/includes/class-nelio-content-settings.php';
		$dictionary['Nelio_Content_Activator'] = NELIO_CONTENT_DIR . '/includes/utils/class-nelio-content-activator.php';
		$dictionary['Nelio_Content_Deactivator'] = NELIO_CONTENT_DIR . '/includes/utils/class-nelio-content-deactivator.php';
		$dictionary['Nelio_Content_i18n'] = NELIO_CONTENT_DIR . '/includes/utils/class-nelio-content-i18n.php';
		$dictionary['Nelio_Content_Install'] = NELIO_CONTENT_DIR . '/includes/utils/class-nelio-content-install.php';
		$dictionary['Nelio_Content_Reference_Post_Type_Register'] = NELIO_CONTENT_DIR . '/includes/utils/class-nelio-content-reference-post-type-register.php';
		$dictionary['Nelio_Content_Updater'] = NELIO_CONTENT_DIR . '/includes/utils/class-nelio-content-updater.php';
		$dictionary['Nelio_Content_External_Featured_Image_Public'] = NELIO_CONTENT_DIR . '/public/class-nelio-content-external-featured-image-public.php';
		$dictionary['Nelio_Content_Public'] = NELIO_CONTENT_DIR . '/public/class-nelio-content-public.php';

		if ( ! isset( $dictionary[ $class ] ) ) {
			return;
		}//end if

		$this->load_file( $dictionary[ $class ] );

	}//end autoload()

}//end class
