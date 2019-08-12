<?php
/**
 * This partial is used for rendering a single social profile in the settings page.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/partials/social-profiles
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

// Language of the blog.
$lang = strtolower( get_locale() );
if ( strpos( $lang, '_' ) > 0 ) {
	$lang = substr( $lang, 0, strpos( $lang, '_' ) );
}//end if

// Linkg for re-authenticating single profiles.
$single_url = nc_get_api_url( '/connect/[*= network *]', 'browser' );
$single_url = add_query_arg( 'siteId', nc_get_site_id(), $single_url );
$single_url = add_query_arg( 'redirect', $redirect_url, $single_url );
$single_url = add_query_arg( 'lang', $lang, $single_url );
$single_url = $single_url . '&creatorId=[*= creatorId *]&socialProfileId=[*= id *]';

// Linkg for re-authenticating non-single profiles.
$kind_url = nc_get_api_url( '/connect/[*= network *]/[*= kind *]', 'browser' );
$kind_url = add_query_arg( 'siteId', nc_get_site_id(), $kind_url );
$kind_url = add_query_arg( 'redirect', $redirect_url, $kind_url );
$kind_url = add_query_arg( 'lang', $lang, $kind_url );
$kind_url = $kind_url . '&creatorId=[*= creatorId *]&socialProfileId=[*= id *]';

$settings = Nelio_Content_Settings::instance();

?>

<script type="text/template" id="_nc-connected-social-profile">

	<div class="nc-profile">
		<div class="nc-profile-picture nc-first-letter-[*= firstLetter *]">
			<div class="nc-actual-profile-picture" style="background-image: url( [*~ photo *] );"></div>
		</div><!-- .nc-picture -->
		<div class="nc-network nc-[*= network *] nc-[*= kind *]"></div>
	</div><!-- .nc-profile -->

	<div class="nc-information">

		<div class="nc-profile-name">
			[*~ displayName *]
		</div><!-- .nc-profile-name -->

		[* if ( addedByCurrentUser ) { *]

			<div class="nc-creator-and-date"><?php
			$date_format = _x( 'MMMM DD, YYYY', 'momentjs (as in \'Something happened on DATE\')', 'nelio-content' );
			printf(
				/* translators: a date */
				esc_html( _x( 'Added by you on %2$s', 'text', 'nelio-content' ) ),
				'<a class="nc-creator" href="[*~ creatorEditLink *]">[*~ creatorDisplayName *]</a>',
				'<span class="nc-date">[*~ creationDate.format( ' . wp_json_encode( $date_format ) . ' ) *]</span>'
			);
			?></div>

		[* } else if ( creatorDisplayName.length > 0 ) { *]

			<div class="nc-creator-and-date"><?php
			$date_format = _x( 'MMMM DD, YYYY', 'momentjs (as in \'Something happened on DATE\')', 'nelio-content' );
			printf(
				/* translators: e.g. [Social profile was] Added by 'David' on 'a certain date' */
				esc_html( _x( 'Added by %1$s on %2$s', 'text', 'nelio-content' ) ),
				'<a class="nc-creator" href="[*~ creatorEditLink *]">[*~ creatorDisplayName *]</a>',
				'<span class="nc-date">[*~ creationDate.format( ' . wp_json_encode( $date_format ) . ' ) *]</span>'
			);
			?></div>

		[* } else { *]

			<div class="nc-creator-and-date"><?php
			printf(
				/* translators: e.g. [Social profile was] Added on 'a certain date' */
				esc_html( _x( 'Added on %1$s', 'text', 'nelio-content' ) ),
				'<span class="nc-date">[*~ creationDate.format( ' . wp_json_encode( $date_format ) . ' ) *]</span>'
			);
			?></div>

		[* } *]

		<div class="nc-actions">

			[* if ( 'awaiting-confirmation' === deletionStatus ) { *]

				<span class="nc-delete-confirmation-label"><?php
					echo esc_html_x( 'Are you sure?', 'user', 'nelio-content' );
				?></span>
				<span class="nc-dashicons nc-dashicons-no-alt nc-cancel-deletion" title="<?php
					echo esc_attr_x( 'Cancel', 'command', 'nelio-content' );
				?>"></span>
				<span class="nc-dashicons nc-dashicons-yes nc-do-delete" title="<?php
					echo esc_attr_x( 'Yes, Delete It', 'command (social profile)', 'nelio-content' );
				?>"></span>

			[* } else { *]

				[* if ( 'renew' !== status ) { *]

					<a href="#" class="nc-refresh" data-href="[* if ( 'single' === kind ) { *]<?php echo $single_url; ?>[* } else { *]<?php echo $kind_url; ?>[* } *]"><?php
						/* translators: this command refreshes the name and image of a Twitter, Facebook, ... social profile. */
						echo esc_html_x( 'Refresh', 'command (social profile)', 'nelio-content' );
					?></a> |

				[* } *]
				<a href="#" class="nc-delete"><?php echo esc_html_x( 'Delete', 'command', 'nelio-content' ); ?></a>

			[* } *]

		</div><!-- .nc-actions -->

	</div><!-- .nc-information -->

	<div class="nc-extra">

		[* if ( 'deleting' === deletionStatus ) { *]

			<span class="spinner is-active"></span>

		[* } else if ( 'renew' === status ) { *]

			<button class="button nc-reauthenticate" data-href="[* if ( 'single' === kind ) { *]<?php echo $single_url; ?>[* } else { *]<?php echo $kind_url; ?>[* } *]"><?php
				echo esc_html_x( 'Re-Authenticate', 'command (social profile)', 'nelio-content' );
			?></button>

		[* } else { *]

			<span class="nc-sync spinner[* if ( synching ) { *] is-active[* } *]"></span>

			<?php if ( $settings->get( 'use_custom_automation_frequencies' ) ) { ?>
					<span title="<?php echo esc_attr_x( 'Automatic social messages on post publication', 'command', 'nelio-content' ); ?>" class="nc-dashicons nc-dashicons-megaphone nc-pre-input"></span>
				<input type="number" min="0" max="20" class="nc-auto-publication" value="[*~ publicationFrequency *]" [* if ( synching ) { *]readonly[* } *] />
			<?php } else { ?>
				[* if ( ! publicationFrequency ) { *]
					<span title="<?php
						echo esc_attr_x( 'Enable automatic social messages on post publication', 'command', 'nelio-content' );
					?>" class="nc-dashicons nc-dashicons-megaphone nc-auto-publication nc-disabled"></span>
				[* } else { *]
					<span title="<?php
						echo esc_attr_x( 'Disable automatic social messages on post publication', 'command', 'nelio-content' );
					?>" class="nc-dashicons nc-dashicons-megaphone nc-auto-publication"></span>
				[* } *]
			<?php } ?>

			<?php if ( $settings->get( 'use_custom_automation_frequencies' ) ) { ?>
					<span title="<?php echo esc_attr_x( 'Automatic resharing of old content', 'command', 'nelio-content' ); ?>" class="nc-dashicons nc-dashicons-share-alt nc-pre-input"></span>
				<input type="number" min="0" max="20" class="nc-auto-reshare" value="[*~ reshareFrequency *]" [* if ( synching ) { *]readonly[* } *] />
			<?php } else { ?>
				[* if ( ! reshareFrequency ) { *]
					<span title="<?php
						echo esc_attr_x( 'Enable automatic resharing of old content', 'command', 'nelio-content' );
					?>" class="nc-dashicons nc-dashicons-share-alt nc-auto-reshare nc-disabled"></span>
				[* } else { *]
					<span title="<?php
						echo esc_attr_x( 'Disable automatic resharing of old content', 'command', 'nelio-content' );
					?>" class="nc-dashicons nc-dashicons-share-alt nc-auto-reshare"></span>
				[* } *]
			<?php } ?>

		[* } *]

	</div><!-- .nc-extra -->

</script><!-- #_nc-connected-social-profile -->

