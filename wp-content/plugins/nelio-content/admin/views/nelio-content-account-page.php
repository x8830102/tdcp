<?php
/**
 * Displays the UI for the Account Page.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

?>

<div class="nc-billing-page wrap">

		<h2><?php
		if ( nc_is_subscribed() ) {
			echo esc_html_x( 'Account Details', 'title', 'nelio-content' );
		} else {
			echo esc_html_x( 'Nelio Content Premium', 'title', 'nelio-content' );
		}//end if
		?> <span class="spinner is-active" style="float:none; margin-top:-5px; display:inline-block;"></span></h2>

	<div class="nc-billing-page-container"></div>

</div><!-- .nc-billing-page -->

