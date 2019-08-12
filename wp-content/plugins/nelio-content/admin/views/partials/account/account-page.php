<?php
/**
 * This partial is the whole account page, with some placeholders for
 * eventual child views.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/account
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

// Promotion messages for invited people.
$promote_messages = array(
	/* translators: a Twitter handler */
	_x( 'Write better content in #WordPress with %s\'s plugin #NelioContent', 'text (account, share text)', 'nelio-content' ),
	/* translators: a Twitter handler */
	_x( '#NelioContent is an awesome #EditorialCalendar for #WordPress, by %s', 'text (account, share text)', 'nelio-content' ),
	/* translators: a Twitter handler */
	_x( 'I\'m using #NelioContent by %s in my #WordPress site and it\'s great!', 'text (account, share text)', 'nelio-content' ),
	/* translators: a Twitter handler */
	_x( 'Want to promote your content easily? Check this out: #NelioContent by %s', 'text (account, share text)', 'nelio-content' ),
	/* translators: a Twitter handler */
	_x( 'The guys at %s created a great #WordPress plugin. Take a look at #NelioContent', 'text (account, share text)', 'nelio-content' ),
);
$count = count( $promote_messages );
/* translators: We only have two twitter accounts, @NelioSoft in English and @NelioSoft_ES in Spanish. */
$nelio_twitter = _x( '@NelioSoft', 'text (Nelio\'s twitter username)', 'nelio-content' );
$twitter_message = sprintf( $promote_messages[ mt_rand( 0, $count - 1 ) ], $nelio_twitter );
$twitter_message = str_replace( '#', '%23', $twitter_message );

?>

<script type="text/template" id="_nc-account-page">

	<div class="nc-contact-info-and-plan nc-[*= subscription *]">

		<div class="nc-account-plan-container">
			<div class="nc-account-plan nc-box[* if ( 'none' === subscription ) { *] nc-free-user[* } else if ( 'canceled' === state ) { *] nc-subscription-canceled[* } *]">

				<div class="nc-content">

					<h3>

						[* if ( 'none' === subscription ) { *]

							<?php // Nothing to be done. ?>

						[* } else { *]
							<?php echo esc_html_x( 'Nelio Content Premium Subscription', 'title (account)', 'nelio-content' ); ?>
						[* } *]

						[* if ( 'none' === subscription ) { *]

							<?php // Nothing to be done. ?>

						[* } else if ( 'invitation' === mode ) { *]

							<span class="nc-period"><?php
								echo esc_html_x( 'Invitation', 'text (account, subscription period)', 'nelio-content' );
							?></span>

						[* } else if ( 'monthly' === subscription ) { *]

							[* if ( 'canceled' === state ) { *]
								<span class="nc-period"><?php
									echo esc_html_x( 'Monthly (canceled)', 'text (account, subscription period)', 'nelio-content' );
								?></span>
							[* } else { *]
								<span class="nc-period"><?php
									echo esc_html_x( 'Monthly', 'text (account, subscription period)', 'nelio-content' );
								?></span>
							[* } *]

						[* } else if ( 'yearly' === subscription ) { *]

							[* if ( 'canceled' === state ) { *]
								<span class="nc-period"><?php
									echo esc_html_x( 'Yearly (canceled)', 'text (account, subscription period)', 'nelio-content' );
								?></span>
							[* } else { *]
								<span class="nc-period"><?php
									echo esc_html_x( 'Yearly', 'text (account, subscription period)', 'nelio-content' );
								?></span>
							[* } *]

						[* } *]

					</h3>

					[* if ( 'none' === subscription ) { *]

						<div class="nc-promo-text-and-video">

							<div class="nc-promo-text">

								<h3><?php echo esc_html( 'Social Marketing Made Easy', 'user', 'nelio-content' ); ?></h3>

								<p class="nc-description"><?php echo esc_html( 'Write new content and auto-fill its social media promotion queue with just one click. Give a second life to your best existing content.', 'user', 'nelio-content' ); ?></p>

								<ul>
									<li><?php echo esc_html( 'Social Templates', 'user', 'nelio-content' ); ?></li>
									<li><?php echo esc_html( 'Fill Social Timeline', 'user', 'nelio-content' ); ?></li>
									<li><?php echo esc_html( 'Extract and Share Relevant Sentences', 'user', 'nelio-content' ); ?></li>
									<li><?php echo esc_html( 'Reshare Good Old Content', 'user', 'nelio-content' ); ?></li>
								</ul>

							</div><!-- .nc-promo-text -->

							<div class="nc-promo-video">
								<video autoplay="true" loop="" muted="" poster="https://neliosoftware.com/wp-content/uploads/videos/nelio-content-landing-v5.jpg">
									<source src="https://neliosoftware.com/wp-content/uploads/videos/nelio-content-landing-v5.mp4" type="video/mp4">
								</video>
							</div><!-- .nc-promo-video -->

						</div><!-- .nc-promo-text-and-video -->

					[* } else if ( 'invitation' === mode ) { *]

						<div class="nc-renewal"><?php
							echo esc_html_x( 'You\'re currently using a Free Pass to Nelio Content\'s Premium Features. Enjoy the plugin and, please, help us improve it with your feedback!', 'user', 'nelio-content' );
						?></div>

					[* } else if ( 'active' === state ) { *]

						<div class="nc-renewal"><?php
							printf( // @codingStandardsIgnoreLine
								/* translators: 1: a price, 2: a date (e.g. "Next charge will be $99.00 on December 1, 2016.") */
								_x( 'Next charge will be %1$s on %2$s.', 'text', 'nelio-content' ),
								'<span class="nc-money">[*~ nextChargeTotal *]</span>',
								'<span class="nc-date">[*= nextRenewalDateFormatted  *]</span>'
							);
						?></div>

					[* } else { *]

						<div class="nc-renewal"><?php
							printf( // @codingStandardsIgnoreLine
								/* translators: a date (e.g. "Your subscription will end on December 1, 2016.") */
								_x( 'Your subscription will end on %1$s.', 'text', 'nelio-content' ),
								'<span class="nc-date">[*= endDateFormatted *]</span>'
							);
						?></div>

					[* } *]

				</div><!-- .nc-content -->

				<div class="nc-actions">

					[* if ( 'invitation' === mode ) { *]

						<?php
						$url = 'https://twitter.com/intent/tweet';
						$url = add_query_arg( 'text', $twitter_message, $url );
						?>
						<a target="_blank" class="button button-primary" href="<?php
								echo esc_url( $url );
							?>"><?php
							echo esc_html_x( 'Tweet About Nelio Content', 'user', 'nelio-content' );
						?></a>

					[* } else if ( 'none' !== modifyLicenseKeyStatus ) { *]

						<table>
							<tr>

								<td class="nc-license">
									<input class="nc-license-key" type="text" value="[*~ newLicenseKey *]" placeholder="<?php
										echo esc_attr_x( 'License Key', 'text', 'nelio-content' );
									?>"[* if ( 'validating' === modifyLicenseKeyStatus ) { *] disabled="disabled"[* } *] />
								</div>

								<td>
									<button class="button nc-hide-license-form"[* if ( 'validating' === modifyLicenseKeyStatus ) { *] disabled="disabled"[* } *]><?php
										echo esc_html_x( 'Cancel', 'command', 'nelio-content' );
									?></button>
								</td>

								[* if ( 'validating' === modifyLicenseKeyStatus ) { *]
									<td>
										<button class="button button-primary nc-validate-license" disabled="disabled"><?php
											echo esc_html_x( 'Validating&hellip;', 'command (license key)', 'nelio-content' );
										?></button>
									</td>
								[* } else { *]
									<td>
										<button class="button button-primary nc-validate-license"><?php
											echo esc_html_x( 'Validate', 'command (license key)', 'nelio-content' );
										?></button>
									</td>
								[* } *]

							</tr>
						</table>

					[* } else if ( 'none' === subscription ) { *]

						<button class="button nc-use-license-key"><?php
							echo esc_html_x( 'Use License Key', 'command', 'nelio-content' );
						?></button>

						<button class="button button-primary nc-subscribe"><?php
							echo esc_html_x( 'Subscribe', 'command', 'nelio-content' );
						?></button>

					[* } else if ( 'active' === state ) { *]

						[* if ( 'yearly' === subscription ) { *]

							<button class="button nc-delete-button nc-cancel-subscription"><?php
								echo esc_html_x( 'Cancel Subscription', 'command', 'nelio-content' );
							?></button>

						[* } else { *]

							<button class="button nc-delete-button nc-cancel-subscription"><?php
								echo esc_html_x( 'Cancel Subscription', 'command', 'nelio-content' );
							?></button>

							<button class="button button-primary nc-upgrade"><?php
								echo esc_html_x( 'Upgrade to Yearly Plan', 'command', 'nelio-content' );
							?></button>

						[* } *]

					[* } else { *]

						<button class="button nc-use-license-key"><?php
							echo esc_html_x( 'Use New License Key', 'command', 'nelio-content' );
						?></button>

						<button class="button button-primary nc-reactivate"><?php
							echo esc_html_x( 'Reactivate Subscription', 'command', 'nelio-content' );
						?></button>

					[* } *]

				</div><!-- .nc-actions -->

			</div><!-- .nc-account-plan -->
		</div><!-- .nc-account-plan-container -->

		[* if ( 'none' !== subscription ) { *]

			<div class="nc-contact-info-container">
				<div class="nc-contact-info nc-box">

					<h3><?php echo esc_html_x( 'Additional Information', 'title (account)', 'nelio-content' ); ?></h3>

					<div class="nc-picture-and-details">

						<div class="nc-profile-picture-container">

							<div class="nc-profile-picture nc-first-letter-[*= firstLetter *]">
								<div class="nc-actual-profile-picture" style="background-image: url( [*~ photo *] )"></div>
							</div><!-- .nc-profile-picture -->

						</div><!-- .nc-profile-picture-container -->

						<div class="nc-contact-info-details">

							<p class="nc-name">[*= fullnameFormatted *]</p>

							<p class="nc-email">
								<span class="nc-dashicons nc-dashicons-email" style="margin-top:-1px;"></span>
								[*~ email *]
							</p><!-- .nc-email -->

							<p class="nc-creation">
								<span class="nc-dashicons nc-dashicons-calendar"></span>
								<?php
								printf( // @codingStandardsIgnoreLine
									/* translators: a date */
									_x( 'Member since %s', 'text (account)', 'nelio-content' ),
									'[*= creationDateFormatted *]'
								);
								?>
							</p><!-- .nc-creation -->

							<p class="nc-license">
								<span class="nc-dashicons nc-dashicons-admin-network" style="margin-top:-2px;"></span>
								<code title="<?php
									echo esc_attr_x( 'License Key', 'text', 'nelio-content' );
								?>">[*= license *]</code>
							</p><!-- .nc-license -->

						</div><!-- .nc-contact-info-details -->

					</div><!-- .nc-picture-and-info -->

				</div><!-- .nc-contact-info -->
			</div><!-- .nc-contact-info-container -->

		[* } *]

	</div><!-- .nc-contact-info-and-plan -->

	[* if ( 'none' !== subscription && ( 'regular' === mode ) ) { *]

		<div class="nc-billing nc-box">

			<h3><?php echo esc_html_x( 'Billing History', 'title', 'nelio-content' ); ?></h3>

			<?php
			$container_name = 'nc-billing-table';
			include NELIO_CONTENT_ADMIN_DIR . '/views/partials/loading-container.php';
			?>

		</div><!-- .nc-billing -->

	[* } *]

</script><!-- #_nc-account-page -->
