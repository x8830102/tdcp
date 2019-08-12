<?php
/**
 * Tumblr meta information.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/data/network-metas
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.6.0
 */

return array(
	'id'                 => 'tumblr',
	'name'               => __( 'Tumblr', 'nelio-content' ),
	'maxLength'          => 500,
	'allowsMultiTargets' => false,
	'previewClassName'   => 'TumblrPreview',
	'isImageRequired'    => false,
	'socialAutomations' => array(
		'reshare' => array(
			'low'  => 1,
			'mid'  => 1,
			'high' => 2,
		),
		'publication' => array(
			'low'  => 1,
			'mid'  => 1,
			'high' => 2,
		),
		'boostedPublication' => array(
			'low'  => 1,
			'mid'  => 1,
			'high' => 2,
		),
	),
);
