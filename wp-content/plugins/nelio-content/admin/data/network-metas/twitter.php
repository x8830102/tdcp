<?php
/**
 * Twitter meta information.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/data/network-metas
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.3.0
 */

return array(
	'id'                 => 'twitter',
	'name'               => __( 'Twitter', 'nelio-content' ),
	'maxLength'          => 280,
	'allowsMultiTargets' => false,
	'previewClassName'   => 'TwitterPreview',
	'isImageRequired'    => false,
	'socialAutomations' => array(
		'reshare' => array(
			'low'  => 2,
			'mid'  => 8,
			'high' => 15,
		),
		'publication' => array(
			'low'  => 1,
			'mid'  => 2,
			'high' => 3,
		),
		'boostedPublication' => array(
			'low'  => 5,
			'mid'  => 10,
			'high' => 15,
		),
	),
);
