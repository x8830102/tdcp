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
	'disabled'               => _x( 'Disabled', 'text', 'nelio-content' ),
	'allContent'             => _x( 'All Content', 'text', 'nelio-content' ),
	/* translators: All Content by "author name" */
	'allContentByAuthor'     => sprintf( _x( 'All Content by %s', 'text', 'nelio-content' ), '{author}' ),
	/* translators: "Post Type" by "author name" */
	'contentByAuthor'        => sprintf( _x( '%1$s by %2$s', 'text', 'nelio-content' ), '{type}', '{author}' ),
	/* translators: Posts in "category name" */
	'postInCategory'         => sprintf( _x( 'Posts in %s', 'text', 'nelio-content' ), '{category}' ),
	/* translators: Posts by "author name" in "category name" */
	'postByAuthorInCategory' => sprintf( _x( 'Posts by %1$s in %2$s', 'text', 'nelio-content' ), '{author}', '{category}' ),
	'postCategoryNotFound'   => _x( 'Related post category couldn\'t be found', 'text', 'nelio-content' ),
	'postTypeNotFound'       => _x( 'Related post type couldn\'t be found', 'text', 'nelio-content' ),
	'authorNotFound'         => _x( 'Related author couldn\'t be found', 'text', 'nelio-content' ),
	'noProfiles'             => _x( 'No profiles associated with this template', 'text', 'nelio-content' ),
	'anyAuthorAction'        => _x( 'All Authors', 'text', 'nelio-content' ),
	'anyAuthorLabel'         => _x( 'Any', 'text (author)', 'nelio-content' ),
);
