<?php
/**
 * This partial represents a feed in the settings screen.
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

<script type="text/template" id="_nc-feed">

	<div class="nc-image">
		<div class="nc-feed-image" title="[*~ name *]" onclick="window.open('[*= feed *]', '_blank');">
			<div class="nc-actual-feed-image"[* if ( icon && icon.length ) { *] style="background: white url( [*= icon *] ) center/cover;"[* } *]></div>
		</div>
	</div><!-- .nc-image -->

	<div class="nc-information">
		<div class="nc-feed-info">
			[*= name *]<br/><span class="nc-feed-url"><a href="[*~ url *]" target="_blank">[*~ url *]</a></span>
		</div><!-- .nc-feed-info -->

		<div class="nc-extra">

			[* if ( 'deleting' === deletionStatus ) { *]

				<?php echo esc_html( _x( 'Deleting&hellip;', 'text (feed)', 'nelio-content' ) ); ?>
				<div class="nc-actions">&nbsp;</div>

			[* } else if ( 'awaiting-confirmation' === deletionStatus ) { *]

				<div class="nc-actions">
					<span class="nc-delete-confirmation-label"><?php
						esc_html_e( 'Are you sure?', 'nelio-content' );
					?></span>
					<span class="nc-dashicons nc-dashicons-yes nc-do-delete" title="<?php
						echo esc_attr_x( 'Yes, Delete It', 'command (feed)', 'nelio-content' );
					?>"></span>
					<span class="nc-dashicons nc-dashicons-no-alt nc-cancel-deletion" title="<?php
						echo esc_attr_x( 'Cancel', 'command', 'nelio-content' );
					?>"></span>
				</div><!-- .nc-actions -->

			[* } else { *]

				<div class="nc-actions">
					<a href="#" class="nc-rename"><?php
						echo esc_html_x( 'Rename', 'command', 'nelio-content' );
					?></a> |
					<a href="#" class="nc-delete"><?php
						echo esc_html_x( 'Delete', 'command', 'nelio-content' );
					?></a>
				</div><!-- .nc-actions -->

			[* } *]

		</div><!-- .nc-extra -->
	</div><!-- .nc-information -->

</script><!-- #_nc-feed -->
