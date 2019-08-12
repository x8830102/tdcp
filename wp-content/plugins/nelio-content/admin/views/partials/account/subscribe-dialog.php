<?php
/**
 * This partial defines the content of the subscribe dialog.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/account
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

?>

<script type="text/template" id="_nc-subscribe-dialog">

		[* if ( 'loading' === mode ) { *]

			<div class="nc-loading-container">
				<span class="spinner is-active"></span>
				<p><?php
					echo esc_html_x( 'Loading&hellip;', 'text', 'nelio-content' );
				?></p>
			</div><!-- .nc-loading-container -->

		[* } else if ( 'error' === mode ) { *]

			<p style="margin-top:0;"><?php
				echo esc_html_x( 'Thanks for your interest in Nelio Content Premium! Unfortunately, we weren\'t able to load our pricing tiers. Please try again later or click on the following button to continue:', 'user', 'nelio-content' );
			?></p>

			<p style="text-align:center;"><a class="button button-hero" href="<?php
				echo esc_url( add_query_arg( array(
					'utm_source'   => 'nelio-content',
					'utm_medium'   => 'plugin',
					'utm_campaign' => 'subscribe-free-user',
					'utm_content'  => 'subscribe-dialog',
				), __( 'https://neliosoftware.com/content/pricing/', 'nelio-content' ) ) );
			?>"><?php
				esc_html_e( 'View Nelio Content Pricing', 'nelio-content' );
			?></a></p>

		[* } else { *]

			<p style="margin-top:0;"><?php
				echo esc_html_x( 'Subscribe to Nelio Content Premium and let us do the hard work for you:', 'user', 'nelio-content' );
			?></p>

			<ul class="nc-monthly-yearly-options">

				<li>
					<span class="nc-title"><?php
						esc_html_e( 'Monthly Plan', 'nelio-content' );
					?></span>
					<span class="nc-price">
						<span><span class="nc-currency">$</span><span class="nc-value">[*= monthlyPlan.price *]</span><span class="nc-period">/<?php esc_html_e( 'month', 'nelio-content' ); ?></span></span>
					</span>
					<span class="nc-equivalence">&nbsp;</span>
					<a class="button button-hero" href="<?php
						echo esc_url( add_query_arg( array(
							'plan'         => 'nc-monthly',
							'utm_source'   => 'nelio-content',
							'utm_medium'   => 'plugin',
							'utm_campaign' => 'subscribe-free-user',
							'utm_content'  => 'subscribe-dialog',
						), __( 'https://neliosoftware.com/content/pricing/', 'nelio-content' ) ) );
					?>"><?php
						esc_html_e( 'Subscribe', 'nelio-content' );
					?></a>
				</li>

				<li>
					<span class="nc-title"><?php
						esc_html_e( 'Yearly Plan', 'nelio-content' );
					?></span>
					<span class="nc-price">
						<span><span class="nc-currency">$</span><span class="nc-value">[*= yearlyPlan.price *]</span><span class="nc-period">/<?php esc_html_e( 'year', 'nelio-content' ); ?></span></span>
					</span>
					<span class="nc-equivalence">(<?php
						printf(
							/* translators: a price (such as $5) */
							__( 'equivalent to %s/month', 'nelio-content' ),
							'$[*= yearlyPlan.equivalence *]'
						);
					?>)</span>
					<a class="button button-hero button-primary" href="<?php
						echo esc_url( add_query_arg( array(
							'plan'         => 'nc-yearly',
							'utm_source'   => 'nelio-content',
							'utm_medium'   => 'plugin',
							'utm_campaign' => 'subscribe-free-user',
							'utm_content'  => 'subscribe-dialog',
						), __( 'https://neliosoftware.com/content/pricing/', 'nelio-content' ) ) );
					?>"><?php
						esc_html_e( 'Subscribe', 'nelio-content' );
					?></a>
				</li>

			</ul>

		[* } *]

</script><!-- #_nc-subscribe-dialog -->

