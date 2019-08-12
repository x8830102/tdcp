<?php
/**
 * The underscore template for rendering a list of feed items.
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

$feeds           = get_option( 'nc_feeds', array() );
$are_there_feeds = 0 < count( $feeds );

?>

<script type="text/template" id="_nc-feed-items">

	<h1><?php echo esc_html_x( 'Feeds', 'title', 'nelio-content' ); ?></h1>
	<?php if ( nc_is_current_user( 'editor' ) ) { ?>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=nelio-content-settings&tab=feeds' ) ); ?>" class="page-title-action">Add New</a>
	<?php }//end if ?>

	<?php if ( $are_there_feeds ) { ?>

		<div class="nc-feed-filters">
			<?php require_once NELIO_CONTENT_ADMIN_DIR . '/views/partials/filters/feed-filter.php'; ?>
		</div>
		<div class="nc-feed-items-none" style="display:none;">
			<span class="nc-dashicons nc-dashicons-info"></span>
			<?php echo esc_html_x( 'No items found.', 'text', 'nelio-content' ); ?>
		</div>
		<div class="nc-feed-items"></div>
		<div class="nc-loading-more">
			<span class="spinner" style="display:inline-block;float:none;margin: 2em 0 0 0"></span>
		</div>

	<?php } else { ?>

		<div class="nc-feeds-none">
			<span class="nc-dashicons nc-dashicons-info"></span>
			<?php
			if ( nc_is_current_user( 'editor' ) ) {
				printf(
					/* translators: a URL */
					_x( 'There are no feeds. <a href="%s">Add one to start</a>.', 'text', 'nelio-content' ),//@codingStandardsIgnoreLine
					esc_url( admin_url( 'admin.php?page=nelio-content-settings&tab=feeds' ) )
				);
			} else {
				echo esc_html_x( 'There are no feeds.', 'text', 'nelio-content' );
			}//end if
			?>
		</div>

	<?php }//end if ?>

</script><!-- #_nc-feed-items -->
