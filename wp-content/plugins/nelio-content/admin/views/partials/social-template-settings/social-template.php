<?php
/**
 * This partial represents a social template in the settings screen.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/partials/social-templates
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.3.0
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

<script type="text/template" id="_nc-social-template">

	[* if ( isFallback ) { *]

		<div class="nc-text-and-extra">
			<div class="nc-text">[*= textFormatted *]</div>
			<div class="nc-extra"><em><?php
				echo esc_html_x( 'Default Template', 'text', 'nelio-content' );
			?></em></div><!-- .nc-extra -->
		</div><!-- .nc-text-and-extra -->

	[* } else { *]

		<div class="nc-text-and-extra">

			<div class="nc-text">[*= textFormatted *]</div>

			<div class="nc-extra">

				[* if ( 'deleting' === deletionStatus ) { *]

					<?php echo esc_html( _x( 'Deleting&hellip;', 'text (social message)', 'nelio-content' ) ); ?>
					<div class="nc-actions">&nbsp;</div>

				[* } else if ( 'awaiting-confirmation' === deletionStatus ) { *]

					[* if ( 'string' === typeof disabled && disabled.length ) { *]
						<span title="[*~ disabled *]">[*~ application *]</span>
					[* } else { *]
						<span>[*~ application *]</span>
					[* } *]

					<div class="nc-actions">
						• <span class="nc-delete-confirmation-label"><?php
							esc_html_e( 'Are you sure?', 'nelio-content' );
						?></span>
						<span class="nc-dashicons nc-dashicons-yes nc-do-delete" title="<?php
							echo esc_attr_x( 'Yes, Delete It', 'command (social message)', 'nelio-content' );
						?>"></span>
						<span class="nc-dashicons nc-dashicons-no-alt nc-cancel-deletion" title="<?php
							echo esc_attr_x( 'Cancel', 'command', 'nelio-content' );
						?>"></span>
					</div><!-- .nc-actions -->

				[* } else { *]

					[* if ( 'string' === typeof disabled && disabled.length ) { *]
						<span title="[*~ disabled *]">[*~ application *]</span>
					[* } else if ( authorLoading ) { *]
						<span><?php echo esc_html_x( 'Loading&hellip;', 'text', 'nelio-content' ); ?></span>
					[* } else { *]
						<span>[*~ application *]</span>
					[* } *]

					<div class="nc-actions">
						• <a href="#" class="nc-edit"><?php
							echo esc_html_x( 'Edit', 'command', 'nelio-content' );
						?></a>
						<span style="color: #ddd;">|</span>
						<a href="#" class="nc-delete"><?php
							echo esc_html_x( 'Delete', 'command', 'nelio-content' );
						?></a>
					</div><!-- .nc-actions -->

				[* } *]

			</div><!-- .nc-extra -->

		</div><!-- .nc-text-and-extra -->

	[* } *]

	[* if ( ! isFallback ) { *]

		<div class="nc-profiles">

			[* if ( isProfileNetwork ) { *]

				<div class="nc-network-profile" title="[*= profileNameEscaped *]">
					<div class="nc-network nc-[*= profileNetwork *] nc-single"></div>
				</div><!-- .nc-network-profile -->

			[* } else { *]

				<div class="nc-profile" title="[*= profileNameEscaped *]">
					<div class="nc-profile-picture nc-first-letter-[*= profileFirstLetter *] nc-[*= profileNetwork *]">
						<div class="nc-actual-profile-picture"[* if ( profilePhoto ) { *] style="background-image: url( [*~ profilePhoto *] );"[* } *]></div>
					</div><!-- .nc-picture -->
					<div class="nc-network nc-[*= profileNetwork *] nc-single"></div>
				</div><!-- .nc-profile -->

			[* } *]

		</div><!-- .nc-profiles -->

	[* } *]

</script><!-- #_nc-social-template -->

