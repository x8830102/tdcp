<?php
/**
 * The underscore template for rendering the notifications metabox.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/partials/notifications
 * @author     Antonio Villegas <antonio.villegas@neliosoftware.com>
 * @since      1.4.2
 */

/**
 * List of vars used in this partial:
 *
 * @var array $followers the users following the post.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

?>
<script type="text/template" id="_nc-notifications">

	<input type="hidden" name="_nc-notifications-meta-box" value="true" />
	<input type="hidden" name="nc_followers" value="[*~ followerIds *]" />

	<div class="nc-users-placeholder"></div>

	<div class="nc-followers">

		[* if ( 0 === amountOfFollowers ) { *]
			<div class="nc-no-followers">
				<?php
					echo esc_html_x( 'Select the users who should receive email notifications when the status of this post is updated, when an editorial task is created or completed, or when an editorial comment is added.', 'user', 'nelio-content' );
				?>
			</div><!-- .nc-no-followers -->
		[* } *]

	</div><!-- .nc-followers -->
</script><!-- #_nc-notifications -->
