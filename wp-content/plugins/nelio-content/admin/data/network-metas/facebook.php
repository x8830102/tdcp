<?php
/**
 * Facebook meta information.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/data/network-metas
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.3.0
 */

return array(
	'id'                 => 'facebook',
	'name'               => __( 'Facebook', 'nelio-content' ),
	'maxLength'          => 10000,
	'allowsMultiTargets' => false,
	'previewClassName'   => 'FacebookPreview',
	'isImageRequired'    => false,
	'socialAutomations' => array(
		'reshare' => array(
			'low'  => 1,
			'mid'  => 1,
			'high' => 1,
		),
		'publication' => array(
			'low'  => 1,
			'mid'  => 1,
			'high' => 2,
		),
		'boostedPublication' => array(
			'low'  => 1,
			'mid'  => 2,
			'high' => 2,
		),
	),
);
