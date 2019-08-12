<?php
/**
 * Editorial Calendar Settings only.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/data
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.6.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

return array(

	array(
		'type'     => 'custom',
		'name'     => 'calendar_post_types',
		'label'    => _x( 'Managed Post Types', 'text', 'nelio-content' ),
		'instance' => new Nelio_Content_Calendar_Post_Type_Setting(),
		'default'  => array( 'post' ),
	),

	array(
		'type'    => 'checkbox',
		'name'    => 'use_custom_post_statuses',
		'label'   => _x( 'Custom Post Statuses', 'text', 'nelio-content' ),
		'desc'    => _x( 'Add custom post statuses (such as "Idea", "Assigned", or "In Progress") to managed post types.', 'command', 'nelio-content' ),
		'default' => false,
	),

	array(
		'type'     => 'custom',
		'name'     => 'use_ics_subscription',
		'label'    => _x( 'iCal Calendar Feed', 'text', 'nelio-content' ),
		'desc'     => _x( 'Export your calendar posts to Google Calendar or any other calendar tool.', 'user', 'nelio-content' ),
		'instance' => new Nelio_Content_ICS_Calendar_Setting(),
		'default'  => false,
	),

);
