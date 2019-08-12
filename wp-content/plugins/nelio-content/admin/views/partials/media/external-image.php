<?php
/**
 * The underscore template for rendering an external image.
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
<script type="text/template" id="_nc-external-image">

	<div class="nc-attachment-preview" data-id="[*~ id *]">
		<div class="nc-thumbnail">
			<img src="[*~ thumbnails.small.image *]" draggable="false" alt="[*~ description *]" />
		</div><!-- .nc-thumbnail -->
	</div><!-- .nc-attachment-preview -->

	<button type="button" class="nc-check">
		<span class="media-modal-icon"></span><span class="screen-reader-text"><?php echo esc_html_x( 'Deselect', 'command', 'nelio-content' ); ?></span>
	</button>

</script><!-- #_nc-external-image"> -->
