<?php
/**
 * Pinterest meta information.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/data/network-metas
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.3.0
 */

return array(
	'id'                 => 'pinterest',
	'name'               => __( 'Pinterest', 'nelio-content' ),
	'maxLength'          => 500,
	'allowsMultiTargets' => true,
	'multiTargetLabels'  => array(
		'title'                => _x( 'Select Boards', 'title (pinterest boards)', 'nelio-content' ),
		'explanation'          => _x( 'Please select the boards your message will be shared on:', 'user (pinterest boards)', 'nelio-content' ),
		'noTargetsExplanation' => _x( 'Your Pinterest profile does not have any boards yet. Go to Pinterest and create one first.', 'user (no pinterest boards)', 'nelio-content' ),
		'loading'              => _x( 'Loading boards…', 'text (pinterest boards)', 'nelio-content' ),
		'targetLabel'          => _x( 'Board', 'text (pinterest target name)', 'nelio-content' ),
	),
	'previewClassName'   => 'PinterestPreview',
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
