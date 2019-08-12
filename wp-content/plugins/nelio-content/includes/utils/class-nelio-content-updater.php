<?php
/**
 * The file that includes some functions used for updating local information
 * after the plugin has been updated.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/utils
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * This class updates WordPress so that all information is working with the
 * current version of the plugin.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/utils
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */
class Nelio_Content_Updater {

	public static function init() {

		add_action( 'nelio_content_updated', array( __CLASS__, 'maybe_update_account_info' ), 10, 2 );

	}//end init()

	public static function maybe_update_account_info( $current_version, $previous_version ) {

		if ( version_compare( $previous_version, '1.5.0' ) > 0 ) {
			return;
		}//end if

		if ( ! nc_is_subscribed() ) {
			delete_option( 'nc_subscription' );
			return;
		}//end if

		nc_refresh_subscription();

	}//end maybe_update_account_info()

}//end class

