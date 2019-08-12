<?php
/**
 * This partial contains a warning message.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/partials/social-message-editor
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.3.0
 */

?>
<script type="text/template" id="_nc-preview-warning-message">

	<div class="nc-warning">

		<p class="nc-warning-title"><span class="nc-dashicons nc-dashicons-warning"></span> <?php
			echo esc_html_x( 'Preview not available', 'title', 'nelio-content' );
		?></p>

		<p class="nc-warning-message"><?php
			printf(
				/* translators: a URL */
				_x( 'Please select a social profile using the selector above. <a href="%s" target="_blank">Show me how&hellip;</a>', 'user', 'nelio-content' ), //@codingStandardsIgnoreLine
				esc_url( add_query_arg( array(
					'utm_source'   => 'nelio-content',
					'utm_medium'   => 'plugin',
					'utm_campaign' => 'support',
					'utm_content'  => 'social-message-editor',
				), _x( 'https://neliosoftware.com/content/help/how-to-select-social-profiles/', 'text', 'nelio-content' ) ) )
			);
		?></p>

	</div><!--.nc-warning-message -->

</script><!-- #_nc-preview-warning-message -->

