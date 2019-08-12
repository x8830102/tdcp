<?php
/**
 * This partial represents a single social message in the social message timeline.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/partials/social-timeline
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

<script type="text/template" id="_nc-social-timeline-message">

	<div class="nc-social-timeline nc-message nc-source-[*= source *] nc-[*= status *][* if ( isFreePreview ) { *] nc-free-preview[* } *][* if ( 'undefined' !== typeof auto ) { *] nc-auto[* } *][* if ( locked ) { *] nc-locked[* } *]">

		<div class="nc-message-pointer"></div>

		[* if ( 'publish' === status ) { *]

			<div class="nc-extra-info-label">

				<?php echo esc_html_x( 'Sent', 'text (social message status)', 'nelio-content' ); ?>
				<span class="nc-dashicons nc-dashicons-yes"></span>

			</div><!-- .nc-extra-info-label -->

		[* } else if ( '_awaitingEnqueueConfirmation' === status ) { *]

			<div class="nc-extra-info-label"><?php
				echo esc_html_x( 'Enqueuing&hellip;', 'text (social message status)', 'nelio-content' );
			?></div><!-- .nc-extra-info-label -->

		[* } else if ( '_enqueued' === status ) { *]

			<div class="nc-extra-info-label"><?php
				echo esc_html_x( 'Ready!', 'text (social message status)', 'nelio-content' );
			?></div><!-- .nc-extra-info-label -->

		[* } else { *]

			[* if ( 'error' === status ) { *]

				<div class="nc-extra-info-label">

					[* if ( 0 === NelioContent.helpers.trim( failureDescription ) ) { *]

						<span class="nc-dashicons nc-dashicons-warning" title="<?php
							echo esc_attr_x( 'Social message couldn\'t be shared because of an unknown error.', 'error', 'nelio-content' );
						?>"></span>

					[* } else { *]

						<span class="nc-dashicons nc-dashicons-warning" title="<?php
							printf(
								/* translators: a failure description (text) */
								esc_attr_x( 'The following error occurred while sharing social message: &ldquo;%s&rdquo;', 'error', 'nelio-content' ),
								'[*~ failureDescription *]'
							);
						?>"></span>

					[* } *]

				</div><!-- .nc-extra-info-label -->

			[* } *]

			<div class="nc-actions">

				[* if ( isFreePreview ) { *]

				<span class="nc-subscribe" title="<?php
					echo esc_attr_x( 'Automatic Social Messages are only available to subscribers. Subscribe to Nelio Content Premium!', 'user', 'nelio-content' );
				?>"><?php
					echo esc_html_x( 'Subscribe to Unlock', 'user', 'nelio-content' );
				?></span>

				[* } else if ( 'deleting' === deletionStatus ) { *]

					<span class="spinner is-active" title="<?php
						echo esc_attr( _x( 'Deleting&hellip;', 'text (social message)', 'nelio-content' ) );
					?>"></span>

				[* } else if ( 'awaiting-confirmation' === deletionStatus ) { *]

					<span class="nc-delete-confirmation-label"><?php
						esc_html_e( 'Are you sure?', 'nelio-content' );
					?></span>
					<span class="nc-dashicons nc-dashicons-yes nc-do-delete" title="<?php
						echo esc_attr_x( 'Yes, Delete It', 'command (social message)', 'nelio-content' );
					?>"></span>
					<span class="nc-dashicons nc-dashicons-no-alt nc-cancel-deletion" title="<?php
						echo esc_attr_x( 'Cancel', 'command', 'nelio-content' );
					?>"></span>

				[* } else if ( 'schedule' === status || 'draft' === status ) { *]

					<span class="nc-dashicons nc-dashicons-arrow-down-alt2 nc-toggle-image-visibility nc-show-image" title="<?php
						echo esc_attr_x( 'Show Image', 'command', 'nelio-content' );
					?>"></span>
					<span class="nc-dashicons nc-dashicons-arrow-up-alt2 nc-toggle-image-visibility nc-hide-image" title="<?php
						echo esc_attr_x( 'Hide Image', 'command', 'nelio-content' );
					?>"></span>
					<span class="nc-dashicons nc-dashicons-edit nc-edit" title="<?php
						echo esc_attr_x( 'Edit', 'command', 'nelio-content' );
					?>"></span>
					<span class="nc-dashicons nc-dashicons-trash nc-delete" title="<?php
						echo esc_attr_x( 'Delete', 'command', 'nelio-content' );
					?>"></span>

				[* } else if ( 'error' === status ) { *]

					<span class="nc-dashicons nc-dashicons-arrow-down-alt2 nc-toggle-image-visibility nc-show-image" title="<?php
						echo esc_attr_x( 'Show Image', 'command', 'nelio-content' );
					?>"></span>
					<span class="nc-dashicons nc-dashicons-arrow-up-alt2 nc-toggle-image-visibility nc-hide-image" title="<?php
						echo esc_attr_x( 'Hide Image', 'command', 'nelio-content' );
					?>"></span>
					<span class="nc-dashicons nc-dashicons-edit nc-edit" title="<?php
						echo esc_attr_x( 'Edit', 'command', 'nelio-content' );
					?>"></span>
					<span class="nc-dashicons nc-dashicons-trash nc-delete" title="<?php
						echo esc_attr_x( 'Delete', 'command', 'nelio-content' );
					?>"></span>
					<span class="nc-dashicons nc-dashicons-update nc-share-now" title="<?php
						echo esc_attr_x( 'Send Now', 'command (social message)', 'nelio-content' );
					?>"></span>

				[* } *]

			</div><!-- .nc-actions -->

		[* } *]

		<div class="nc-profile-and-network nc-[*= status *]">

			<div class="nc-profile">

				<div class="nc-profile-picture nc-first-letter-[*= firstLetter *]">
					<div class="nc-actual-profile-picture" style="background-image: url( [*~ photo *] );"></div>
				</div><!-- .nc-picture -->
				<div class="nc-network nc-[*= network *] nc-[*= networkKind *]"></div>

			</div><!-- .nc-profile -->

		</div><!-- .nc-profile-and-network -->

		<div class="nc-name-actual-message-and-timestamp nc-[*= status *]">

			<div class="nc-name-and-target">

				<span class="nc-name">[*~ displayName *]</span>

				[* if ( networkAllowsMultiTargets && targetDisplayName.length > 0 ) { *]
					<?php
						printf(
							/* translators: Example: "David published this post _ON_ TWITTER" */
							esc_html_x( 'on %s', 'text (social timeline)', 'nelio-content' ),
							'<span class="nc-target">[*~ targetDisplayName *]</span>'
						);
					?>
				[* } *]

			</div><!-- .nc-name-and-target -->

			<div class="nc-actual-message">[*= textFormatted *]</div>

			[* if ( 'image' === type && 'string' === typeof image && image.length ) { *]
				<div class="nc-image">
					<img src="[*~ image *]" />
				</div><!-- .nc-image -->
			[* } *]

			[* if ( '' !== dateFormatted ) { *]
				<div class="nc-timestamp">

					[* if ( 'reshare-template' === source ) { *]

						<span class="nc-dashicons nc-dashicons-share-alt" title="<?php
							echo esc_attr_x( 'Reshare Template', 'text', 'nelio-content' );
						?>"></span>

					[* } else if ( 'publication-template' === source ) { *]

						<span class="nc-dashicons nc-dashicons-megaphone" title="<?php
							echo esc_attr_x( 'Publication Template', 'text', 'nelio-content' );
						?>"></span>

					[* } else if ( 'reference-template' === source ) { *]

						<span class="nc-dashicons nc-dashicons-admin-links" title="<?php
							echo esc_attr_x( 'Reference Template', 'text', 'nelio-content' );
						?>"></span>

					[* } else if ( 'user-highlight' === source ) { *]

						<span class="nc-dashicons nc-dashicons-editor-quote" title="<?php
							echo esc_attr_x( 'User Highlighted Fragment', 'text', 'nelio-content' );
						?>"></span>

					[* } else if ( 'auto-extracted-sentence' === source ) { *]

						<span class="nc-dashicons nc-dashicons-format-status" title="<?php
							echo esc_attr_x( 'Automatically Extracted Sentence', 'text', 'nelio-content' );
						?>"></span>

					[* } *]

					<span class="nc-date">[*= dateFormatted *]</span>
				</div><!-- .nc-timestamp -->
			[* } *]

		</div><!-- .nc-name-actual-message-and-date -->

		[* if ( isFreePreview ) { *]

			<a class="nc-subscribe" target="_blank" href="<?php
				echo esc_url( add_query_arg( array(
					'utm_source'   => 'nelio-content',
					'utm_medium'   => 'plugin',
					'utm_campaign' => 'subscribe-free-user',
					'utm_content'  => 'social-timeline',
				), __( 'https://neliosoftware.com/content/pricing/', 'nelio-content' ) ) );
			?>">&nbsp;</a>
		[* } *]

	</div><!-- .nc-social-timeline.nc-message -->

</script><!-- #_nc-social-timeline-message -->

