<?php
/**
 * This partial previews a social message in Tumblr.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/partials/social-message-editor
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.6.0
 */

/**
 * List of vars used in this partial:
 *
 * None.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<script type="text/template" id="_nc-tumblr-preview">

	<div class="nc-tumblr-preview">

		<div class="nc-profile">

			<div class="nc-profile-picture nc-first-letter-[*= profile.firstLetter *]">
				<div class="nc-actual-profile-picture" style="background-image: url( [*~ profile.photo *] );"></div>
			</div><!-- .nc-profile-picture -->

		</div><!-- .nc-profile -->

		<div class="nc-wrapper">

			<div class="nc-name">[*~ profile.username *]</div>

			[* if ( 'image' === type ) { *]

				<div class="nc-shared-image">
					<img src="[*= image *]" />
				</div><!-- .nc-shared-image -->

			[* } else { *]

				[* if ( sharedLink.status === 'ready' ) { *]
					<div class="nc-post">

						[* if ( sharedLink.image.length > 0 ) { *]
							<div class="nc-feat-image">

								<img src="[*= sharedLink.image *]" />

								<p class="nc-signature">
									[*~ sharedLink.domain *]
								</p><!-- .nc-signature -->

							</div><!-- .nc-feat-image -->
						[* } *]

						<div class="nc-content">

							<p class="nc-title">[*~ sharedLink.title *]</p>

							[* if ( sharedLink.excerpt.length > 0 ) { *]
								<p class="nc-excerpt">
									[*~ sharedLink.excerpt *]
								</p><!-- .nc-excerpt -->
							[* } *]

						</div><!-- .nc-content -->

					</div><!-- .nc-post -->

				[* } else if ( sharedLink.status === 'loading' ) { *]

					<div class="nc-loading-shared-link">
						<span class="spinner is-active"></span>
					</div><!-- .nc-loading-shared-link -->

				[* } *]

			[* } *]

			[* if ( textFormatted.length > 0 ) { *]
				<div class="nc-message[* if ( sharedLink.status === 'ready' ) { *] nc-ready-shared-link[* } *]">[*= textFormatted *]</div>
			[* } else { *]
				<div class="nc-message nc-empty-message"><?php esc_html_e( 'Your status update&hellip;', 'nelio-content' ); ?></div>
			[* } *]

		</div><!-- .nc-wrapper -->

	</div><!-- .nc-tumblr-preview -->

</script><!-- #_nc-tumblr-preview -->
