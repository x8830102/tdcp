<?php
/**
 * This partial is used for rendering the connected social profiles in the settings page.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/partials/social-profiles
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

/**
 * List of vars used in this partial:
 *
 * None.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

// Include underscore templates for this page.
include_once( NELIO_CONTENT_ADMIN_DIR . '/views/partials/social-profile-settings/connect-facebook-profile-dialog.php' );
include_once( NELIO_CONTENT_ADMIN_DIR . '/views/partials/social-profile-settings/connect-googleplus-profile-dialog.php' );
include_once( NELIO_CONTENT_ADMIN_DIR . '/views/partials/social-profile-settings/connect-linkedin-profile-dialog.php' );

include NELIO_CONTENT_ADMIN_DIR . '/views/partials/social-profile-settings/social-profile-settings.php';
include NELIO_CONTENT_ADMIN_DIR . '/views/partials/social-profile-settings/connected-social-profile.php';
include NELIO_CONTENT_ADMIN_DIR . '/views/partials/social-profile-settings/social-automations-walkthrough-pointers.php';

?>

<div id="social-profile-settings-container" class="nc-social-profile-settings"></div>

