<?php
/**
 * JavaScript i18n strings.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/data/i18n
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.3.0
 */

$pricing_url = add_query_arg( array(
	'utm_source'   => 'nelio-content',
	'utm_medium'   => 'plugin',
	'utm_campaign' => 'subscribe-free-user',
	'utm_content'  => 'subscribe-dialog',
), __( 'https://neliosoftware.com/content/pricing/', 'nelio-content' ) );

return array(
	'analyticsNoPostsFound'   => _x( 'No posts found.', 'user', 'nelio-content' ),
	/* translators: a number */
	'analyticsProcessed'      => sprintf( _x( 'Analytics successfully updated.<br>Posts processed: %s', 'user', 'nelio-content' ), '{posts}' ),
	'deleteSocialMessage'     => _x( 'Do you really want to delete this social message? <strong>This operation cannot be undone</strong>.', 'user', 'nelio-content' ),
	'deleteSocialTemplate'    => _x( 'Do you really want to delete this social template? <strong>This operation cannot be undone</strong>.', 'user', 'nelio-content' ),
	/* translators: a URL */
	'enableSocialAutomations' => sprintf( _x( 'Nelio Content can automatically generate social messages based on your content and preferences. Unfortunately, none of your social profiles has social automations enabled. Please activate them in the <a href="%s">settings page</a>.', 'user', 'nelio-content' ), esc_url( admin_url( 'admin.php?page=nelio-content-settings' ) ) ),
	/* translators: a date */
	'cancelSubscription'      => sprintf( _x( 'Canceling your subscription will cause it not to renew. If you cancel your subscription, it will continue until <strong>%s</strong>. Then, the subscription will expire and will not be invoiced again, but you will be able to use the Free Version of Nelio Content. Do you want to cancel your subscription?', 'user', 'nelio-content' ), '{date}' ),
	'pausePublication'        => _x( 'You\'re about to pause the publication of social messages. If you continue, none of your scheduled social messages will be published on your social media accounts. Do you really want to continue?', 'user', 'nelio-content' ),
	'reactivateSubscription'  => _x( 'Reactivating your subscription will cause it to renew. Do you want to reactivate your subscription?', 'user', 'nelio-content' ),
	'resetSocialMessages'     => _x( 'Automatic social messages will be regenerated shortly. Please check your editorial calendar in few minutes&hellip;', 'user', 'nelio-content' ),
	'reusePreviousMessage'    => _x( 'Please select the text you want to reuse:', 'user', 'nelio-content' ),
	'socialNotSentUnknown'    => _x( 'Social message couldn\'t be shared because of an unknown error.', 'error', 'nelio-content' ),
	/* translators: an error message */
	'socialNotSent'           => sprintf( _x( 'The following error occurred while sharing social message:<br><strong>%s</strong>', 'error', 'nelio-content' ), '{error}' ),
	'subscribeForAutomations' => '<div><p>' . esc_html_x( 'Nelio Content can automatically fill your editorial calendar with social messages. Let us help you to save time with your Social Media strategy!', 'user', 'nelio-content' ) . '</p><p style="text-align:center;"><a target="_blank" class="button button-primary" href="' . esc_url( $pricing_url ) . '">' . esc_html_x( 'Subscribe to Nelio Content Premium', 'user', 'nelio-content' ) . '</a></p></div>',
	'trashPost'               => _x( 'Do you really want to trash this post?', 'user', 'nelio-content' ),
	'trashElement'            => _x( 'Do you really want to trash this element?', 'user', 'nelio-content' ),
	'invalidSocialScheduling' => _x( 'Social messages whose publication date is relative to the publication date of a post <strong>can\'t be scheduled before the post is published</strong>. If you want to do so, please edit the social message and use an <em>exact</em> date.', 'user', 'nelio-content' ),
);

