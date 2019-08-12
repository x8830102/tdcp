<?php
/**
 * This partial is used for listing a mention in the result box.
 *
 * Notice it doesn't use "underscore" variables, but the syntax expected by the atwho lib.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/partials/social-message-editor
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.4.7
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

<script type="text/template" id="_nc-mention-search-result">

	<li class="nc-mention-search-result nc-profile">
		
		<img class="nc-profile-picture" src="${photo}" />

		<div class="nc-profile-info">
			<p><span class="nc-label"><?php
				echo esc_html( 'Name:', 'text', 'nelio-content' );
			?></span> ${displayName}</p>
			<p><em><span class="nc-label"><?php
				echo esc_html( 'Username:', 'text', 'nelio-content' );
			?></span> @${username}</em></p>
		</div>

	</li><!-- .nc-mention-search-result -->

</script><!-- #_nc-mention-search-result -->

