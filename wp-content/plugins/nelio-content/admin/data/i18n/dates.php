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
	'cancelDateDialog'        => _x( 'LL', 'momentjs (as in "The subscription will continue until {date}")', 'nelio-content' ),
	'cancelDateText'          => _x( 'LL', 'momentjs (as in "Your subscription will end on {date}")', 'nelio-content' ),
	'creationDate'            => _x( 'LL', 'momentjs (as in "Member since {date}")', 'nelio-content' ),
	/* translators: a number */
	'daysAfterPublication'    => sprintf( _x( '%s days after publication', 'text', 'nelio-content' ), '{days}' ),
	/* translators: a number */
	'daysBeforePublication'   => sprintf( _x( '%s days before publication', 'text', 'nelio-content' ), '{days}' ),
	'default'                 => _x( 'L', 'momentjs (default date)', 'nelio-content' ),
	/* translators: a number */
	'hoursAfterPublication'   => sprintf( _x( '%s hours after publication', 'text', 'nelio-content' ), '{hours}' ),
	/* translators: Syntax of moment.js library. Translate "Yesterday" surrounded by square brackes. */
	'lastDay'                 => _x( '[Yesterday]', 'momentjs', 'nelio-content' ),
	/* translators: Date using the syntax of moment.js library. Example: "Last Monday". */
	'lastWeek'                => _x( '[Last] dddd', 'momentjs', 'nelio-content' ),
	/* translators: Syntax of moment.js library. Translate "Tomorrow" surrounded by square brackes. */
	'nextDay'                 => _x( '[Tomorrow]', 'momentjs', 'nelio-content' ),
	/* translators: Date using the syntax of moment.js library. Example: "Next Thursday". */
	'nextWeek'                => _x( '[Next] dddd', 'momentjs', 'nelio-content' ),
	'nextChargeDate'          => _x( 'LL', 'momentjs (as in "Next charge will be on {date}")', 'nelio-content' ),
	'onPublication'           => _x( 'Same time as publication', 'text', 'nelio-content' ),
	'oneHourAfterPublication' => _x( 'One hour after publication', 'text', 'nelio-content' ),
	'publicationDay'          => _x( 'Publication day', 'text', 'nelio-content' ),
	/* translators: Syntax of moment.js library. Translate "Today" surrounded by square brackes. */
	'sameDay'                 => _x( '[Today]', 'momentjs', 'nelio-content' ),
	'someday'                 => _x( 'Someday', 'text (date)', 'nelio-content' ),
	'preview' => array(
		/* translators: format a date as Facebook does in your timeline, using the syntax of moment.js library. */
		'facebook'      => _x( 'MMMM Do, YYYY', 'text (Facebook preview date)', 'nelio-content' ),
		/* translators: format a date as Google Plus does in your timeline, using the syntax of moment.js library. */
		'googleplus'    => _x( 'MMMM Do, YYYY', 'text (Google+ preview date)', 'nelio-content' ),
		/* translators: format a date as Pinterest does in your timeline, using the syntax of moment.js library. */
		'instagram'     => _x( 'MMMM Do, YYYY', 'text (Instagram preview date)', 'nelio-content' ),
		/* translators: format a date as LinkedIn does in your timeline, using the syntax of moment.js library. */
		'linkedin'      => _x( 'MMMM Do, YYYY', 'text (LinkedIn preview date)', 'nelio-content' ),
		/* translators: format a date as Pinterest does in your timeline, using the syntax of moment.js library. */
		'pinterest'     => _x( 'MMMM Do, YYYY', 'text (Pinterest preview date)', 'nelio-content' ),
		/* translators: format a date as Tumblr does in your timeline, using the syntax of moment.js library. */
		'tumblr'        => _x( 'MMMM Do, YYYY', 'text (Tumblr preview date)', 'nelio-content' ),
		/* translators: format a date as Twitter does in your timeline (including time), using the syntax of moment.js library. */
		'twitter'       => _x( 'h:mm A - DD MMM YYYY', 'text (Twitter preview date)', 'nelio-content' ),
		/* translators: format a date as Twitter does in your timeline (without time), using the syntax of moment.js library. */
		'twitterNoTime' => _x( 'DD MMM YYYY', 'text (Twitter preview date)', 'nelio-content' ),
	),
);
