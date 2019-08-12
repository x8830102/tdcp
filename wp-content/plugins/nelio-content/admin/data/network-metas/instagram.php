<?php
/**
 * Instagram meta information.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/data/network-metas
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.3.0
 */

return array(
	'id'                 => 'instagram',
	'name'               => __( 'Instagram', 'nelio-content' ),
	'maxLength'          => 2000,
	'allowsMultiTargets' => false,
	'previewClassName'   => 'InstagramPreview',
	'isImageRequired'    => true,
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
