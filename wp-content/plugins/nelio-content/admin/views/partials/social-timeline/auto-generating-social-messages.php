<?php
/**
 * This partial renders a loading container.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/social-timeline
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.3.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

?>
<script type="text/template" id="_nc-auto-generating-social-messages">

	<div class="nc-loading-container">
		<span class="spinner is-active"></span>
		<p><?php
			echo esc_html_x( 'Generating Social Messages&hellip;', 'text', 'nelio-content' );
		?></p>
	</div><!-- .nc-loading-container -->

</script><!-- #_nc-auto-generating-social-messages -->
