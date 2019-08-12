<?php
/**
 * This partial is the skeletton for managing feeds.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/partials/feed-settings
 * @author     Antonio Villegas <antonio.villegas@neliosoftware.com>
 * @since      1.5.9
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

<script type="text/template" id="_nc-feed-settings">

	<div class="nc-feed-input">
		<div class="nc-feed-url">
			<input id="nc-search-field" type="url" placeholder="<?php
				echo esc_attr_x( 'Write the URL of a RSS feed…', 'command', 'nelio-content' );
			?>">
		</div><!-- .nc-image-search -->
		<div class="nc-feed-add">
			<button type="button" class="button nc-add-feed-button" disabled="disabled">[*= NelioContent.i18n.actions.add *]</button>
		</div>
	</div><!-- .nc-feed-input -->

	[* if ( numOfFeeds > 0 ) { *]

		<div class="nc-feeds"></div>

	[* } else { *]

		<p><?php echo esc_html_x( 'There are no feeds connected', 'text', 'nelio-content' ); ?></p>

	[* } *]

</script><!-- #_nc-feed-settings -->
