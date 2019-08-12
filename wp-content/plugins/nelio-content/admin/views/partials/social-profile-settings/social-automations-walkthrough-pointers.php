<?php
/**
 * Social Automations Walkthrough Pointers
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/partials/social-profiles
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.3.0
 */

/**
 * List of vars used in this partial:
 *
 * None.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

?>

<script type="text/template" id="_nc-automations-publication-pointer">
	<p><?php
		printf(
			/* translators: an HTML tag that renders a dashicons */
			_x( 'Enable <em>%s Automations on Publication</em> and Nelio Content will fill the social timeline of your new posts.', 'user', 'nelio-content' ),
			'<span class="nc-dashicons nc-dashicons-megaphone"></span>'
		);
	?></p>
	<figure><img width="232" height="224" src="<?php echo esc_html( NELIO_CONTENT_ADMIN_URL . '/images/publication.gif' ); ?>"></figure>
</script><!-- #_nc-automations-publication-pointer -->

<script type="text/template" id="_nc-automations-reshare-pointer">
	<p><?php
		printf(
			/* translators: an HTML tag that renders a dashicons */
			_x( 'Enable <em>%s Social Resharing</em> and Nelio Content will make sure your calendar is never empty.', 'user', 'nelio-content' ),
			'<span class="nc-dashicons nc-dashicons-share-alt"></span>'
		);
	?></p>
	<figure><img width="228" height="142" src="<?php echo esc_html( NELIO_CONTENT_ADMIN_URL . '/images/reshare.gif' ); ?>"></figure>
</script><!-- #_nc-automations-reshare-pointer -->

<script type="text/template" id="_nc-automations-frequency-pointer">
	<p><?php
		echo _x( 'Choose the frequency at which Nelio Content should publish automatic messages for you.', 'user', 'nelio-content' );
	?></p>
</script><!-- #_nc-automations-frequency-pointer -->

<script type="text/template" id="_nc-automations-counter-pointer">
	<p><?php
		echo esc_html_x( 'Get an estimate of the maximum number of social messages that Nelio Content will automatically publish.', 'user', 'nelio-content' );
	?></p>
</script><!-- #_nc-automations-counter-pointer -->

<script type="text/template" id="_nc-automations-template-pointer">
	<p><?php
		echo esc_html_x( 'Define your own templates to customize automatic social messages.', 'user', 'nelio-content' );
	?></p>
</script><!-- #_nc-automations-template-pointer -->

