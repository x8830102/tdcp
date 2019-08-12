<?php
/**
 * JavaScript i18n strings.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/data/i18n
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.3.0
 */

$spinner = '<span class="dashicons dashicons-update nc-animate-spinner"></span> ';
$wp_spinner = '<span class="spinner is-active"></span> ';

$wait_seconds = array();
for ( $i = 0; $i < 10; ++$i ) {
	array_push( $wait_seconds, sprintf(
		/* translators: number of seconds */
		_nx( 'Wait %s second&hellip;', 'Wait %s seconds&hellip;', $i, 'text', 'nelio-content' ),
		$i
	) );
}//end for

return array(
	'retrievingPosts'          => _x( 'Retrieving posts&hellip;', 'text (analytics)', 'nelio-content' ),
	'adding'                   => $spinner . _x( 'Adding&hellip;', 'text', 'nelio-content' ),
	'cancelingSubscription'    => $spinner . _x( 'Canceling Subscription&hellip;', 'text', 'nelio-content' ),
	'creating'                 => $spinner . _x( 'Creating&hellip;', 'text', 'nelio-content' ),
	'creatingMessages'         => $spinner . _x( 'Creating&hellip;', 'text (messages)', 'nelio-content' ),
	'creatingTask'             => $spinner . _x( 'Creating&hellip;', 'text (task)', 'nelio-content' ),
	'deleting'                 => $spinner . _x( 'Deleting&hellip;', 'text', 'nelio-content' ),
	'enabling'                 => $spinner . _x( 'Enabling&hellip;', 'text', 'nelio-content' ),
	'gaTokensExpired'          => _x( '<strong>Warning!</strong> Your Google Analytics tokens have expired.', 'text', 'nelio-content' ),
	'loading'                  => $spinner . _x( 'Loading&hellip;', 'text', 'nelio-content' ),
	'loadingNoSpinner'         => _x( 'Loading&hellip;', 'text', 'nelio-content' ),
	'noInvoices'               => _x( 'No invoices found.', 'text', 'nelio-content' ),
	'noOldMessages'            => _x( 'No social messages found.', 'text', 'nelio-content' ),
	'noUnscheduledPosts'       => _x( 'None', 'text [unscheduled posts]', 'nelio-content' ),
	'noUnscheduledPostsFound'  => _x( 'No posts found', 'text', 'nelio-content' ),
	'reactivatingSubscription' => $spinner . _x( 'Reactivating Subscription&hellip;', 'text', 'nelio-content' ),
	'refreshingAnalytics'      => $spinner . _x( 'Refreshing', 'text (analytics)', 'nelio-content' ),
	'renamingFeed'             => $spinner . _x( 'Renaming Feed&hellip;', 'text', 'nelio-content' ),
	'saving'                   => $spinner . _x( 'Saving&hellip;', 'text', 'nelio-content' ),
	'searchingMentions'        => $wp_spinner . _x( 'Searching&hellip;', 'text', 'nelio-content' ),
	/* translators: a number of characters */
	'selectionLength'          => sprintf( _x( 'Selection length: %s', 'text', 'nelio-content' ), '{0}' ),
	/* translators: a number of characters */
	'shareBlockLength'         => sprintf( _x( 'Auto share length: %s', 'text', 'nelio-content' ), '{0}' ),
	'subscribersOnly'          => _x( '(only available to subscribers)', 'text', 'nelio-content' ),
	'starting'                 => $spinner . _x( 'Starting&hellip;', 'text', 'nelio-content' ),
	'trashing'                 => $spinner . _x( 'Trashing&hellip;', 'text', 'nelio-content' ),
	'uploading'                => $spinner . _x( 'Uploading&hellip;', 'text', 'nelio-content' ),
	'upgrading'                => $spinner . _x( 'Upgrading&hellip;', 'text', 'nelio-content' ),
	'waitAMoment'              => _x( 'Please wait a moment&hellip;', 'user', 'nelio-content' ),
	'waitSeconds'              => $wait_seconds,
);
