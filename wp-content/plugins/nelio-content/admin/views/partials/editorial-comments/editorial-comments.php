<?php
/**
 * The underscore template for rendering a list of editorial comments, as well
 * as the form for adding new comments.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/partials/editorial-comments
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
}//end if

?>
<script type="text/template" id="_nc-editorial-comments">

	<div class="nc-editorial-comments"></div>

<?php
if ( nc_is_subscribed() ) { ?>

	<div class="nc-new-editorial-comment">
		<textarea placeholder="<?php esc_attr_e( 'Write a comment and press enter to send&hellip;', 'nelio-content' ); ?>"></textarea>
	</div><!-- .nc-new-editorial-comment -->

<?php
} else { ?>

	<p style="text-align:center;"><a target="_blank" href="<?php
		echo esc_url( add_query_arg( array(
			'utm_source'   => 'nelio-content',
			'utm_medium'   => 'plugin',
			'utm_campaign' => 'subscribe-free-user',
			'utm_content'  => 'editorial-comments',
		), __( 'https://neliosoftware.com/content/pricing/', 'nelio-content' ) ) );
	?>" class="button"><?php
		echo esc_html_x( 'Subscribe to Unlock', 'command', 'nelio-content' );
	?></a></p>

<?php
} ?>

</script><!-- #_nc-editorial-comments -->
