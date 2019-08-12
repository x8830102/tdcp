<?php
/**
 * JavaScript i18n strings.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/data/i18n
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.3.0
 */

return array(
	'api' => array(
		'generic'                  => _x( 'The following error occurred while accessing Nelio Content\'s API:', 'text', 'nelio-content' ),
		'emptyAjaxStatus'          => _x( 'There was an error while accessing Nelio Content\'s API. Please try again later.', 'user', 'nelio-content' ),
		'cantGetInvoices'          => _x( 'There was an error while retrieving your invoices. Please try again later.', 'user', 'nelio-content' ),
		/* translators: an error description */
		'cantGetProducts'          => sprintf( _x( 'The following error occurred while retrieving the list of available plans: %s. Please try again later.', 'user', 'nelio-content' ), '{error}' ),
		'unableToRetrieveProfiles' => _x( 'There was an error while accessing Nelio Content\'s API and your social profiles couldn\'t be retrieved. Please try again later.', 'user', 'nelio-content' ),
		'unableToRetrieveCalendar' => _x( 'There was an error while accessing Nelio Content\'s API and some items in your calendar couldn\'t be retrieved. Please try again later.', 'user', 'nelio-content' ),
	),
	'featuredImage' => array(
		'emptyUrl'        => _x( 'Please write the URL of an external image.', 'error', 'nelio-content' ),
		'invalidUrl'      => _x( 'URL is invalid', 'error', 'nelio-content' ),
		'unableToLoadUrl' => _x( 'Image can\'t be loaded. Please try again with a different URL.', 'error', 'nelio-content' ),
	),
	'generic' => array(
		'unableToRescheduleItem' => _x( 'An error occured while rescheduling the item. Please try again later.', 'user', 'nelio-content' ),
		'unableToDeleteItem'     => _x( 'An error occured while deleting the item. Please try again later.', 'user', 'nelio-content' ),
		'unknown'                => _x( 'An error occured while accessing your WordPress site. Please try again later.', 'user', 'nelio-content' ),
		'unknownUserName'        => _x( 'Unknown', 'text (username)', 'nelio-content' ),
	),
	'socialMessage' => array(
		'noMessage'        => _x( 'Please write your status update', 'error', 'nelio-content' ),
		'messageTooLong'   => _x( 'Please write a shorter social message', 'error', 'nelio-content' ),
		'noProfiles'       => _x( 'Please select a social profile', 'error', 'nelio-content' ),
		'noImageMultiple'  => _x( 'Selected profiles require you to share an image', 'error', 'nelio-content' ),
		'noImageSingle'    => _x( 'Selected profile requires you to share an image', 'error', 'nelio-content' ),
		'noTarget'         => _x( 'Please select a target', 'error', 'nelio-content' ),
		/* translators: an error description */
		'unableToLoadPost' => sprintf( _x( 'The related post couldn\'t be loaded, because of the following error: %s', 'error', 'nelio-content' ), '{error}' ),
	),
	'reference' => array(
		'noUrl'          => _x( 'Please specify the reference\'s URL', 'error', 'nelio-content' ),
		'invalidUrl'     => _x( 'URL is invalid', 'error', 'nelio-content' ),
		'invalidTwitter' => _x( 'Twitter username has to start with «@»', 'error', 'nelio-content' ),
		'invalidMail'    => _x( 'Email is invalid', 'error', 'nelio-content' ),
		'notSuggested'   => _x( 'The following error occurred while suggesting the reference:', 'error', 'nelio-content' ),
		'notDiscarded'   => _x( 'The following error occurred while discarding the reference:', 'error', 'nelio-content' ),
	),
	'feed' => array(
		'noUrl'          => _x( 'Please specify the feed\'s URL', 'error', 'nelio-content' ),
		'invalidUrl'     => _x( 'URL is invalid', 'error', 'nelio-content' ),
		'feedError'      => _x( 'The following error occurred while adding the feed:', 'error', 'nelio-content' ),
	),
	'socialTemplate' => array(
		'noMessage'        => _x( 'Please create a template with some content', 'error', 'nelio-content' ),
	),
	'task' => array(
		'noTask' => _x( 'Please enter a task', 'error', 'nelio-content' ),
	),
	'post' => array(
		'noTitle'       => _x( 'Please set post\'s title', 'error', 'nelio-content' ),
		'invalidUrl'    => _x( 'Reference\'s URL is invalid', 'error', 'nelio-content' ),
		/* translators: an error description */
		'unableToTrash' => sprintf( _x( 'The following error ocurred while trashing the post: %s.', 'error', 'nelio-content' ), '{error}' ),
	),
	'datetime' => array(
		'noPastAllowed' => _x( 'Date and time cannot be older than current date', 'error', 'nelio-content' ),
		'invalidDate'   => _x( 'Date format is invalid', 'error', 'nelio-content' ),
		'noDate'        => _x( 'Please specify a date', 'error', 'nelio-content' ),
		'invalidTime'   => _x( 'Time format is invalid', 'error', 'nelio-content' ),
		'noTime'        => _x( 'Please specify a time', 'error', 'nelio-content' ),
	),
);
