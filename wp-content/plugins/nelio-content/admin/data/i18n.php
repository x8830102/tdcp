<?php
/**
 * JavaScript i18n strings.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/data
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.3.0
 */

$aux = get_post_type_object( 'post' );
$post_name = $aux->labels->singular_name;

$time_format = get_option( 'time_format', 'a' );
$time_format = str_ireplace( '\\a', '', $time_format );

if ( stripos( $time_format, 'a' ) !== false ) {
	$time_format = 'h:mma';
} else {
	$time_format = 'H:mm';
}//end if

$sent_messages = array();
for ( $i = 1; $i <= 50; ++$i ) {
	array_push( $sent_messages, sprintf(
		/* translators: a number */
		_nx( '<strong>+%d more</strong> social message sent', '<strong>+%d more</strong> social messages sent', $i, 'text', 'nelio-content' ),
		$i
	) );
}//end for
array_push( $sent_messages, sprintf(
	/* translators: String used for 51 or more social messages sent. Up to 50, proper i18n is used in JavaScript */
	_x( '<strong>+%s more</strong>', 'text', 'nelio-content' ),
	'{count}'
) );

return array(
	'locale'                 => get_locale(),
	/* translators: 1: a name, 2: a surname */
	'fullnameFormat'         => sprintf( _x( '%1$s %2$s', 'text: {firstname} {lastname}', 'nelio-content' ), '{firstname}', '{lastname}' ),
	'startOfWeek'            => get_option( 'start_of_week' ),
	'timezone'               => nc_get_timezone(),
	'sentMessagesInCalendar' => $sent_messages,
	'moreMessagesInCalendar' => sprintf(
		/* translators: String used for 51 or more social messages sent. Up to 50, proper i18n is used in JavaScript */
		_x( '<strong>+%s more</strong>', 'text', 'nelio-content' ),
		'{count}'
	),
	'actions'   => include( NELIO_CONTENT_ADMIN_DIR . '/data/i18n/actions.php' ),
	'dates'     => include( NELIO_CONTENT_ADMIN_DIR . '/data/i18n/dates.php' ),
	'dialogs'   => include( NELIO_CONTENT_ADMIN_DIR . '/data/i18n/dialogs.php' ),
	'errors'    => include( NELIO_CONTENT_ADMIN_DIR . '/data/i18n/errors.php' ),
	'feedback'  => include( NELIO_CONTENT_ADMIN_DIR . '/data/i18n/feedback.php' ),
	'filters'   => include( NELIO_CONTENT_ADMIN_DIR . '/data/i18n/filters.php' ),
	'pointers'  => include( NELIO_CONTENT_ADMIN_DIR . '/data/i18n/pointers.php' ),
	'settings'  => include( NELIO_CONTENT_ADMIN_DIR . '/data/i18n/settings.php' ),
	'templates' => include( NELIO_CONTENT_ADMIN_DIR . '/data/i18n/templates.php' ),
	'titles'    => include( NELIO_CONTENT_ADMIN_DIR . '/data/i18n/titles.php' ),
	'messages' => array(
		'davilera'           => __( 'David Aguilera, Co-Founder of Nelio Software', 'nelio-content' ),
		'commentFreeMessage' => _x( 'Hey! Did you know you can leave comments to the members of your team using Editorial Comments? Subscribe to Nelio Content Premium using the button below and start saving time today!', 'user', 'nelio-content' ),
	),
	'prices' => array(
		/* translators: e.g. $19/month or $59/year */
		'options' => _x( '%1$s or %2$s', 'text', 'nelio-content' ),
		/* translators: e.g. $19/month */
		'monthly' => _x( '$%d/month', 'text', 'nelio-content' ),
		/* translators: e.g. $59/year */
		'yearly'  => _x( '$%d/year', 'text', 'nelio-content' ),
	),
	'time' => array(
		/* translators: Syntax of moment.js library. Translate "Now" surrounded by square brackes. */
		'now'      => _x( '[Now]', 'momentjs', 'nelio-content' ),
		/* translators: Time using moment.js library's syntax. For instance, "8:03pm". */
		'default'  => _x( 'h:mma', 'momentjs', 'nelio-content' ),
		'calendar' => $time_format,
	),
);

