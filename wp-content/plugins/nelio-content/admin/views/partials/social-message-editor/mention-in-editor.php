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

<script type="text/template" id="_nc-mention-in-editor">

	<span class="nc-mention nc-[*= network *]" data-mention="@[[*= network *]:[*= username *]]">[*= displayMentionEscaped *]</span>

</script><!-- #_nc-mention-in-editor -->

