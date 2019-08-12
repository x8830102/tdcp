<?php
/**
 * This partial contains the list of available networks for which we can connect social profiles.
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

// URL to which our cloud has to redirect the user once a social profile has been successfully completed.
$redirect_url = add_query_arg( 'nc-page', 'connected-profile-callback', admin_url( 'admin.php' ) );

$settings = Nelio_Content_Settings::instance();

?>

<script type="text/template" id="_nc-social-profile-settings">

	[* if ( isLoadingSocialProfiles ) { *]

		<div class="nc-checking-available-profiles">
			<span class="spinner is-active"></span>
			<?php echo esc_html_x( 'Checking if there are any social profiles connected to Nelio Content&hellip;', 'text (social profile settings)', 'nelio-content' ); ?>
		</div><!-- .nc-checking-available-profiles -->

	[* } else if ( numOfConnectedProfiles > 0 ) { *]

		<h2 class="nc-title-with-select">

			<?php echo esc_html_x( 'Connected Profiles', 'text', 'nelio-content' ); ?>
			<span class="nc-connected-profile-counter"><span class="nc-count">[*= numOfConnectedProfiles *]</span><span class="nc-total">/[*= maxNumOfProfiles *]</span></span>

			<?php if ( ! $settings->get( 'use_custom_automation_frequencies' ) ) { ?>
				<select class="nc-auto-sharer-frequency">
					<option value="high"><?php echo esc_html_x( 'High Auto Publication Frequency', 'text (auto publication frequency)', 'nelio-content' ); ?></option>
					<option value="mid"><?php echo esc_html_x( 'Medium Auto Publication Frequency', 'text (auto publication frequency)', 'nelio-content' ); ?></option>
					<option value="low"><?php echo esc_html_x( 'Low Auto Publication Frequency', 'text (auto publication frequency)', 'nelio-content' ); ?></option>
				</select>
			<?php } ?>

			[* if ( numOfConnectedProfiles > 0 ) { *]
				<a class="nc-automations-walkthrough" href="#"><?php echo esc_html_x( 'Help', 'text', 'nelio-content' ); ?></a>
			[* } *]

		</h2>

		<p><?php
			echo esc_html_x( 'The following profiles can be managed by any author in your team:', 'text', 'nelio-content' );
		?></p>

		<div class="nc-connected-profiles"></div>

	[* } else { *]

		<h2><?php echo esc_html_x( 'Add Profiles', 'title', 'nelio-content' ); ?></h2>

		<p><?php
			echo esc_html_x( 'Connect your social media profiles to Nelio Content using the following buttons:', 'user', 'nelio-content' );
		?></p>

	[* } *]

	[* if ( ! isLoadingSocialProfiles ) { *]

		<div class="nc-networks">

			<?php

			// TWITTER.
			$warning = esc_attr_x( 'The maximum number of Twitter profiles you may configure has been reached.', 'text', 'nelio-content' );

			$network = 'twitter';
			$condition = 'isTwitterEnabled';
			$name = esc_attr_x( 'Add Twitter Profile', 'command', 'nelio-content' );
			$url = nc_get_api_url( '/connect/twitter', 'browser' );
			$url = add_query_arg( 'siteId', nc_get_site_id(), $url );
			$url = add_query_arg( 'creatorId', get_current_user_id(), $url );
			$url = add_query_arg( 'redirect', $redirect_url, $url );
			$url = add_query_arg( 'lang', nc_get_language(), $url );
			$url = esc_url( $url );
			include NELIO_CONTENT_ADMIN_DIR . '/views/partials/social-profile-settings/social-profile-icon.php';

			// FACEBOOK.
			$warning = esc_attr_x( 'The maximum number of Facebook profiles you may configure has been reached.', 'text', 'nelio-content' );

			$network = 'facebook-personal';
			$condition = 'isFacebookEnabled';
			$name = esc_attr_x( 'Add Facebook Profile', 'command', 'nelio-content' );
			$url = esc_url( '#' );
			include NELIO_CONTENT_ADMIN_DIR . '/views/partials/social-profile-settings/social-profile-icon.php';

			// LINKEDIN.
			$warning = esc_attr_x( 'The maximum number of LinkedIn profiles you may configure has been reached.', 'text', 'nelio-content' );

			$network = 'linkedin-personal';
			$condition = 'isLinkedInEnabled';
			$name = esc_attr_x( 'Add LinkedIn Profile', 'command', 'nelio-content' );
			include NELIO_CONTENT_ADMIN_DIR . '/views/partials/social-profile-settings/social-profile-icon.php';

			// INSTAGRAM.
			$warning = esc_attr_x( 'The maximum number of Instagram profiles you may configure has been reached.', 'text', 'nelio-content' );

			$network = 'instagram';
			$condition = 'isInstagramEnabled';
			$name = _x( 'Add Instagram Profile via Buffer Reminders', 'text', 'nelio-content' );
			$url = nc_get_api_url( '/connect/instagram', 'browser' );
			$url = add_query_arg( 'siteId', nc_get_site_id(), $url );
			$url = add_query_arg( 'creatorId', get_current_user_id(), $url );
			$url = add_query_arg( 'redirect', $redirect_url, $url );
			$url = add_query_arg( 'lang', nc_get_language(), $url );
			$url = esc_url( $url );
			include NELIO_CONTENT_ADMIN_DIR . '/views/partials/social-profile-settings/social-profile-icon.php';

			// PINTEREST.
			$warning = esc_attr_x( 'The maximum number of Pinterest profiles you may configure has been reached.', 'text', 'nelio-content' );

			$network = 'pinterest';
			$condition = 'isPinterestEnabled';
			$name = esc_attr_x( 'Add Pinterest Profile', 'command', 'nelio-content' );
			$url = nc_get_api_url( '/connect/pinterest', 'browser' );
			$url = add_query_arg( 'siteId', nc_get_site_id(), $url );
			$url = add_query_arg( 'creatorId', get_current_user_id(), $url );
			$url = add_query_arg( 'redirect', $redirect_url, $url );
			$url = add_query_arg( 'lang', nc_get_language(), $url );
			$url = esc_url( $url );
			include NELIO_CONTENT_ADMIN_DIR . '/views/partials/social-profile-settings/social-profile-icon.php';

			// TUMBLR.
			$warning = esc_attr_x( 'The maximum number of Tumblr profiles you may configure has been reached.', 'text', 'nelio-content' );

			$network = 'tumblr';
			$condition = 'isTumblrEnabled';
			$name = esc_attr_x( 'Add Tumblr Profile', 'command', 'nelio-content' );
			$url = nc_get_api_url( '/connect/tumblr', 'browser' );
			$url = add_query_arg( 'siteId', nc_get_site_id(), $url );
			$url = add_query_arg( 'creatorId', get_current_user_id(), $url );
			$url = add_query_arg( 'redirect', $redirect_url, $url );
			$url = add_query_arg( 'lang', nc_get_language(), $url );
			$url = esc_url( $url );
			include NELIO_CONTENT_ADMIN_DIR . '/views/partials/social-profile-settings/social-profile-icon.php';

			?>

		</div><!-- .nc-networks -->

	[* } *]

	[* if ( numOfConnectedProfiles > 0 ) { *]
		<p>
			<span class="nc-message-estimate"></span>
			<a class="nc-reset-social-messages" href="#" title="<?php
				echo esc_attr_x( 'Delete automatic social messages and regenerate them again using current settings', 'text', 'nelio-content' );
			?>"><?php echo esc_html_x( 'Reset Social Messages', 'command', 'nelio' ); ?></a>
		</p>
	[* } *]

</script><!-- #_nc-social-profile-settings -->
