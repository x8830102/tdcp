<?php
/**
 * This partial is used by a Select2 component and determines how a single
 * social profile has to be displayed.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/partials/social-message-editor
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

/**
 * List of vars used in this partial:
 *
 * None.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<script type="text/template" id="_nc-single-profile-selector-selected-profile">

	<span class="nc-selected-profile">

		[* if ( 'network' === kind ) { *]
			<div class="nc-network nc-[*= network *] nc-single"></div>
		[* } else { *]
			<div class="nc-network nc-[*= network *] nc-[*= kind *]"></div>
		[* } *]

		[*~ displayName *]

		[* if ( 'network' === kind ) { *]
			[* /* Nothing to be done here */ *]
		[* } else if ( 'twitter' === network || 'instagram' === network) { *]
			<span class="nc-username">([*~ username *])</span>
		[* } *]

	</div><!-- .nc-selected-profile -->

</script><!-- #_nc-single-profile-selector-selected-profile -->

