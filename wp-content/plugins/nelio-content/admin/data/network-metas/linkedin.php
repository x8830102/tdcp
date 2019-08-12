<?php
/**
 * LinkedIn meta information.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/data/network-metas
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.3.0
 */

return array(
	'id'                 => 'linkedin',
	'name'               => __( 'LinkedIn', 'nelio-content' ),
	'maxLength'          => 600,
	'allowsMultiTargets' => false,
	'previewClassName'   => 'LinkedInPreview',
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
