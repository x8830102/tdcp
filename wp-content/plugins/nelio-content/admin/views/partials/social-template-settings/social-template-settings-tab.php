<?php
/**
 * This file includes all the underscore templates for managing social templates.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/partials/social-templates
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.3.0
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

include_once NELIO_CONTENT_ADMIN_DIR . '/views/partials/social-template-settings/social-template.php';
include_once NELIO_CONTENT_ADMIN_DIR . '/views/partials/social-template-settings/social-template-settings.php';

include_once NELIO_CONTENT_ADMIN_DIR . '/views/partials/users-and-profiles/user-selector-option.php';
include_once NELIO_CONTENT_ADMIN_DIR . '/views/partials/users-and-profiles/user-selector-selected-option.php';

include_once NELIO_CONTENT_ADMIN_DIR . '/views/partials/users-and-profiles/single-profile-selector-profile.php';
include_once NELIO_CONTENT_ADMIN_DIR . '/views/partials/users-and-profiles/single-profile-selector-selected-profile.php';
include_once NELIO_CONTENT_ADMIN_DIR . '/views/partials/users-and-profiles/single-profile-selector-target.php';
include_once NELIO_CONTENT_ADMIN_DIR . '/views/partials/social-template-settings/social-template-editor.php';

?>

<div id="social-template-settings-container" class="nc-social-template-settings"></div>

