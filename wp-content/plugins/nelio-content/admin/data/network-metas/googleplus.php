<?php
/**
 * Google Plus meta information.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/data/network-metas
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.3.0
 */

return array(
	'id'                 => 'googleplus',
	'name'               => __( 'Google+', 'nelio-content' ),
	'maxLength'          => 500,
	'allowsMultiTargets' => false,
	'previewClassName'   => 'GooglePlusPreview',
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
