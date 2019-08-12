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
	'actions' => array(
		'showSocialMessages' => _x( 'Show All Messages', 'command (social filter)', 'nelio-content' ),
		'showTasks'          => _x( 'Show All Tasks', 'command (task filter)', 'nelio-content' ),
		'hideSocialMessages' => _x( 'Hide All Messages', 'command (social filter)', 'nelio-content' ),
		'hideTasks'          => _x( 'Hide All Tasks', 'command (task filter)', 'nelio-content' ),
		'showPosts'          => _x( 'Show All Posts', 'command (user filter)', 'nelio-content' ),
	),
	'groups' => array(
		'network'  => _x( 'Filter by Network', 'user (social filter, group name)', 'nelio-content' ),
		'profile'  => _x( 'Filter by Profile', 'user (social filter, group name)', 'nelio-content' ),
		'assignee' => _x( 'Filter by Assignee', 'user (task filter, group name)', 'nelio-content' ),
		'author'   => _x( 'Posts by author', 'text (user filter, group name)', 'nelio-content' ),
	),
	'selection' => array(
		'allPostTypes'      => _x( 'All Post Types', 'text (post filter, selection)', 'nelio-content' ),
		'allSocialMessages' => _x( 'All Social Messages', 'text (social filter, selection)', 'nelio-content' ),
		'allTasks'          => _x( 'All Tasks', 'text (task filter, selection)', 'nelio-content' ),
		'allAuthors'        => _x( 'All Authors', 'text (user filter, selection)', 'nelio-content' ),
		'engagement'        => _x( 'Sorted By Engagement', 'text (metric sorter, selection)', 'nelio-content' ),
		'pageviews'         => _x( 'Sorted By Pageviews', 'text (metric sorter, selection)', 'nelio-content' ),
		'noPosts'           => _x( 'No Posts', 'text (post filter, selection)', 'nelio-content' ),
		'noPostTypes'       => _x( 'No Post Types', 'text (post filter, selection)', 'nelio-content' ),
		'noSocialMessages'  => _x( 'No Social Messages', 'text (social filter, selection)', 'nelio-content' ),
		'noTasks'           => _x( 'No Tasks', 'text (task filter, selection)', 'nelio-content' ),
		/* translators: a user's name. For instance: "David's Tasks". */
		'tasksOf'           => _x( '%s\'s Tasks', 'text (task filter, selection)', 'nelio-content' ),
			/* translators: sorting criterion, such as "Posts by engagement" or "Posts by pageviews" */
		'postsBy'           => _x( 'Posts by %s', 'text (user filter, selection)', 'nelio-content' ),
	),
	'sortedBy' => array(
		'pageviews'  => _x( 'Sorted by <strong>Pageviews</strong>.', 'text (metric sorter, analytics)', 'nelio-content' ),
		'engagement' => _x( 'Sorted by <strong>Engagement</strong>.', 'text (metric sorter, analytics)', 'nelio-content' ),
	),
	'sortBy' => array(
		'pageviews'  => _x( 'Sort by Pageviews.', 'text (metric sorter, analytics)', 'nelio-content' ),
		'engagement' => _x( 'Sort by Engagement.', 'text (metric sorter, analytics)', 'nelio-content' ),
	),
);

