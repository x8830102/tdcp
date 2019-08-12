<?php
/**
 * JavaScript i18n strings.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/data/i18n
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.3.0
 */

$settings_reshare = array( '' );
$settings_publication = array( '' );

for ( $i = 1; $i <= 50; ++$i ) {
	array_push( $settings_reshare, sprintf(
		/* translators: a number */
		_nx( 'Your calendar will contain <strong>%d social message per day</strong>.', 'Your calendar will contain about <strong>%d social messages per day</strong>.', $i, 'user', 'nelio-content' ),
		$i
	) );
	array_push( $settings_publication, sprintf(
		/* translators: a number */
		_nx( 'When publishing new content, Nelio will automatically generate up to <strong>%d social message</strong>.', 'When publishing new content, Nelio will automatically generate up to <strong>%d social messages</strong>.', $i, 'text', 'nelio-content' ),
		$i
	) );
}//end for

array_push( $settings_reshare, __( 'Your calendar will contain <strong>as many messages per day as possible</strong>.', 'user', 'nelio-content' ) );
array_push( $settings_publication, __( 'When publishing new content, Nelio will automatically generate <strong>as many messages as possible</strong>.', 'text', 'nelio-content' ) );

return array(
	'publicationEstimateMessages' => $settings_publication,
	'reshareEstimateMessages'     => $settings_reshare,
	'emptyEstimateMessages'       => _x( 'Enable social automations on one or more profiles to allow Nelio Content to generate messages automatically.', 'user', 'nelio-content' ),
	'gaSelectorPlaceholder'       => _x( 'Select which view can be accessed by Nelio Content', 'user', 'nelio-content' ),
);
