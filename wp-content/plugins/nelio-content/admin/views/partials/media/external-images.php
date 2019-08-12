<?php
/**
 * The underscore template for rendering a list of external images, as well
 * as the form for searching images in external sources.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/partials/media
 * @author     Antonio Villegas <antonio.villegas@neliosoftware.com>
 * @since      1.4.6
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
<script type="text/template" id="_nc-external-images">

	<div class="nc-image-frame">

		<div class="nc-image-search-area">
			<div class="nc-image-search">
				<input id="nc-search-field" type="text" placeholder="<?php
					echo esc_attr_x( 'Search images…', 'command', 'nelio-content' );
				?>"><span class="spinner"></span>
			</div><!-- .nc-image-search -->

			<div class="nc-image-results">
				<div class="nc-no-images-found">
					<h2 class="upload-message"><?php echo esc_html_x( 'No items found.', 'text', 'nelio-content' ); ?></h2>
					<p class="upload-message"><?php echo esc_html_x( 'Try again with a different set of keywords.', 'user', 'nelio-content' ); ?></p>
				</div>
				<ul tabindex="-1" class="nc-external-images attachments ui-sortable ui-sortable-disabled"></ul>
			</div><!-- .nc-image-results -->
		</div><!-- .nc-image-search-area -->

		<div class="nc-image-details-panel"></div>

	</div><!-- .nc-image-frame -->

	<div class="nc-image-actions">
	<?php if ( 'giphy' === $source ) {?>
		<img class="nc-attribution" src="<?php echo esc_url( NELIO_CONTENT_ADMIN_URL . '/images/giphy-attribution.gif' ); ?>" />
	<?php } elseif ( 'pixabay' === $source ) {?>
		<a href="https://pixabay.com/" target="_blank"><img class="nc-attribution" src="<?php echo esc_url( NELIO_CONTENT_ADMIN_URL . '/images/pixabay-attribution.svg' ); ?>" /></a>
	<?php }//end if ?>

		<button type="button" class="nc-insert-button button media-button button-primary button-large media-button-select" disabled="disabled">
			<?php
			if ( 'pixabay' === $source ) {
				echo esc_html_x( 'Upload and insert into post', 'command', 'nelio-content' );
			} else {
				echo esc_html_x( 'Insert into post', 'command', 'nelio-content' );
			}//end if ?>
		</button>
	</div><!-- .nc-image-actions -->

</script><!-- #_nc-external-images -->
