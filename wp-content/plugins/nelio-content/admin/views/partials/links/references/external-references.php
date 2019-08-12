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

<script type="text/template" id="_nc-external-references">

	<div class="nc-external-references">

		<div class="nc-list">

			<div class="nc-no-external-references"><?php
				echo esc_html_x( 'External references included in your content will appear here as you add them.', 'user', 'nelio-content' );
			?></div>

		</div><!-- .nc-list -->

	</div><!-- .nc-external-references -->

</script><!-- #_nc-external-references -->

