<?php
/**
 * This file includes all the underscore templates for managing feeds.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/partials/social-templates
 * @author     Antonio Villegas <antonio.villegas@neliosoftware.com>
 * @since      1.5.9
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
require_once NELIO_CONTENT_ADMIN_DIR . '/views/partials/feed-settings/feed.php';
require_once NELIO_CONTENT_ADMIN_DIR . '/views/partials/feed-settings/feed-settings.php';

?>

<div id="feed-settings-container" class="nc-feed-settings"></div>
