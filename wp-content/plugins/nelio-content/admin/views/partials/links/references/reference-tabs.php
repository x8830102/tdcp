<?php
/**
 * This partial contains the list of external references.
 *
 * Presumably used in the Links meta box.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/partials/references
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.3.4
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

<script type="text/template" id="_nc-reference-tabs">

	<ul class="nc-reference-tabs">
		<li class="nc-tab nc-suggested-references-tab"><?php echo esc_html_x( 'Suggested', 'text (reference)', 'nelio-content' ); ?></li>
		<li class="nc-tab nc-external-references-tab"><?php echo esc_html_x( 'External', 'text (reference)', 'nelio-content' ); ?></li>
	</ul>

</script><!-- #_nc-reference-tabs -->

