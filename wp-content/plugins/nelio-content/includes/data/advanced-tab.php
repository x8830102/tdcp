<?php
/**
 * List of settings.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/data
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

return array(

	array(
		'type'  => 'section',
		'name'  => 'advanced-setups',
		'label' => _x( 'Plugin Setup', 'text', 'nelio-content' ),
	),

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
		'type'    => 'checkbox',
		'name'    => 'use_notifications',
		'label'   => _x( 'Notifications', 'text', 'nelio-content' ),
		'desc'    => _x( 'Send email notifications when the status of a post is updated, when an editorial task is created or completed, or when an editorial comment is added.', 'command', 'nelio-content' ),
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

	array(
		'type'    => 'checkbox',
		'name'    => 'uses_proxy',
		'label'   => _x( 'API Proxy', 'text', 'nelio-content' ),
		'desc'    => _x( 'My server doesn\'t support SNI. Use Nelio\'s secure proxy to access the API.', 'command', 'nelio-content' ),
		'default' => false,
	),

	array(
		'type'  => 'section',
		'name'  => 'advanced-social-setups',
		'label' => _x( 'Social Behavior', 'text', 'nelio-content' ),
	),

	array(
		'type'    => 'select',
		'name'    => 'auto_reshare_default_mode',
		'label'   => _x( 'Reshare', 'text', 'nelio-content' ),
		'desc'    => _x( 'Nelio Content can automatically reshare old content according to your preferences:', 'html', 'nelio-content' ),
		'default' => 'include-in-reshare',
		'options' => array(
			array(
				'value' => 'include-in-reshare',
				'label' => _x( 'Include all posts, unless stated otherwise', 'text', 'nelio-content' ),
				'desc'  => _x( 'Nelio Content may reshare any old post, unless you\'ve explicitly excluded it from resharing.', 'text', 'nelio-content' ),
			),
			array(
				'value' => 'exclude-from-reshare',
				'label' => _x( 'Exclude all posts, unless stated otherwise', 'text', 'nelio-content' ),
				'desc'  => _x( 'Nelio Content will only reshare old posts that you\'ve manually marked as eligible for resharing.', 'text', 'nelio-content' ),
			),
		),
	),

	array(
		'type'    => 'checkbox',
		'name'    => 'use_custom_automation_frequencies',
		'label'   => _x( 'Automations Frequency', 'text', 'nelio-content' ),
		'desc'    => _x( 'Set publication and reshare frequencies manually.', 'command', 'nelio-content' ),
		'default' => false,
	),

);
