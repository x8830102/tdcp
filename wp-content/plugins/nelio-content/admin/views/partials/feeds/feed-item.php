<?php
/**
 * The underscore template of a feed item.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/partials/feeds
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

<script type="text/template" id="_nc-feed-item">

	<div class="nc-feed-item nc-box">

		<div class="nc-feed-item-data [*= actionPerformed *]">

			<div class="nc-feed-item-cell">
				<div class="nc-feed-image">
					<div class="nc-feed-item-image" title="[*~ feed.title *]" onclick="window.open('[*= feed.permalink *]', '_blank');">
						<div class="nc-actual-feed-item-image"[* if ( feed.image && feed.image.length ) { *] style="background: white url( [*= feed.image *] ) center/cover;"[* } *]></div>
					</div>
				</div>
			</div><!-- .nc-feed-item-image -->

			<div class="nc-feed-item-summary nc-feed-item-cell">

				<div class="nc-feed-item-title">
					[*= title *]<br/><span class="nc-feed-item-permalink"><a href="[*~ permalink *]" target="_blank">[*~ permalink *]</a></span>
				</div><!-- .nc-feed-item-title -->

				<div class="nc-feed-item-publication">
				<?php
					printf(
						// translators: %1$s is a comma-separated author list and %2$s is a date.
						esc_html_x( 'Published by %1$s on %2$s.', 'text', 'nelio-content' ),
						'[*~ author *]',
						'[*~ date *]'
					);
				?>
				</div><!-- .nc-feed-item-publication -->

				<div class="nc-feed-item-content">[*~ content *]</div>

				<div class="nc-actions">

					<div class="nc-share">
						<?php if ( nc_is_subscribed() ) { ?>
							<button class="button button-primary">
								<span class="nc-dashicons nc-dashicons-share"></span>
								<?php echo esc_html_x( 'Share', 'command', 'nelio-content' ); ?>
							</button>
						<?php } else { ?>
							<button class="button" disabled="disabled"
								title="<?php echo esc_attr_x( 'Only available to subscribers', 'text', 'nelio-content' ); ?>">
								<span class="nc-dashicons nc-dashicons-share"></span>
								<?php echo esc_html_x( 'Share', 'command', 'nelio-content' ); ?>
							</button>
						<?php }//end if ?>
					</div>

					<div class="nc-write">
						<button class="button">
							<span class="nc-dashicons nc-dashicons-edit"></span>
							<?php echo esc_html_x( 'Write about it', 'command', 'nelio-content' ); ?>
						</button>
					</div>

				</div><!-- .nc-actions -->

			</div><!-- .nc-feed-item-summary -->

			<!-- <div class="nc-feed-item-actions nc-feed-item-cell">
				<span class="nc-dashicons nc-dashicons-admin-post"></span>
				<span class="nc-dashicons nc-dashicons-share"></span>
			</div> -->

		</div><!-- .nc-feed-item-data -->

	</div><!-- .nc-feed-item -->

</script><!-- #_nc-feed-item -->
