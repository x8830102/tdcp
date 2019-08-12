<?php
/**
 * The underscore template for rendering a follower of a post.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/partials/notifications
 * @author     Antonio Villegas <antonio.villegas@neliosoftware.com>
 * @since      1.4.2
 */

/**
 * List of vars used in this partial:
 *
 * @var array $follower a user following the post.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

$notify_author = apply_filters( 'nelio_content_notification_auto_subscribe_post_author', true, 'subscription_action' );
$notify_current_user = apply_filters( 'nelio_content_notification_auto_subscribe_current_user', true, 'subscription_action' );

?>
<script type="text/template" id="_nc-follower">

	<div class="ncselect2-result nc-single-user">

		<div class="nc-profile">

			<div class="nc-profile-picture nc-first-letter-[*= firstLetter *]">
				<div class="nc-actual-profile-picture" style="background-image: url( [*~ photo *] );"></div>
			</div><!-- .nc-picture -->

		</div><!-- .nc-profile -->

		<div class="nc-name-and-email">

			<span class="nc-name">
				[* if ( isCurrentUser ) { *]
					<?php echo esc_html_x( 'You', 'text (user)', 'nelio-content' ); ?>
				[* } else { *]
					[*= name *]
				[* } *]
			</span>

			<span class="nc-email">
				[* if ( isAuthor ) { *]
					<?php echo esc_html_x( 'Author', 'text', 'nelio-content' ); ?> •
				[* } *]
				[*= email *]
			</span>

		</div><!-- .nc-name-and-email -->

		<div class="nc-actions">

			<?php
			if ( $notify_author && $notify_current_user ) { ?>
				[* if ( ! isAuthor && ! isCurrentUser ) { *]
			<?php
			} elseif ( $notify_author ) { ?>
				[* if ( ! isAuthor ) { *]
			<?php
			} elseif ( $notify_current_user ) { ?>
				[* if ( ! isCurrentUser ) { *]
			<?php
			} ?>
				<span data-user-id="[*= id *]" class="nc-discard nc-dashicons nc-dashicons-no" title="<?php
					echo esc_attr_x( 'Remove', 'command (follower)', 'nelio-content' );
				?>"></span>
			<?php
			if ( $notify_author || $notify_current_user ) { ?>
				[* } *]
			<?php
			} ?>

		</div>

	</div><!-- .ncselect2-result.single-profile -->

</script><!-- #_nc-follower -->
