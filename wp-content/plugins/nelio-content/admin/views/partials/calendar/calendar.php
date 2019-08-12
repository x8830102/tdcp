<?php
/**
 * Underscore template for displaying a social message item in the calendar.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/calendar
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

/**
 * List of vars used in this page:
 *
 * None.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

?>
<script type="text/template" id="_nc-calendar">

	<div class="nc-h1-and-notifications-wrapper" style="display:none;">
		<h1 class="nc-current-month">
			printf( // @codingStandardsIgnoreLine
				/* translators: 1: month, 2: year. For instance, January 2019 */
				_x( '%1$s %2$s', 'text (calendar title: {month} {year})', 'nelio-content' ),
				'<span class="nc-month"></span>', '<span class="nc-year"></span>'
			);
		?></h1>
	</div><!-- .nc-h1-and-notifications-wrapper -->

	<div class="nc-calendar-page-container">

		<div class="nc-calendar-header">

			<div class="nc-calendar-actions">

				<div class="nc-left">

					<button class="button nc-action nc-prev nc-month"><span class="nc-dashicons nc-dashicons-arrow-left-alt2"></span></button><button class="button nc-action nc-next nc-month"><span class="nc-dashicons nc-dashicons-arrow-right-alt2"></span></button>
					<span class="nc-current-month"><?php
						printf( // @codingStandardsIgnoreLine
							/* translators: 1: month, 2: year. For instance, January 2019 */
							_x( '%1$s %2$s', 'text (calendar title: {month} {year})', 'nelio-content' ),
							'<span class="nc-month"></span>', '<span class="nc-year"></span>'
						);
					?></span>
					<button class="button nc-action nc-today"><?php esc_html_e( 'Today', 'nelio-content' ); ?></button>

					<div class="nc-quick-actions">
						<span class="nc-dashicons nc-toggle-publication"></span>
						<span class="nc-dashicons nc-toggle-unscheduled-posts"></span>
						<?php
						if ( nc_is_subscribed() ) {
							$download_class = 'nc-export-action';
							$download_label = _x( 'Export Calendar&hellip;', 'command', 'nelio-content' );
						} else {
							$download_class = 'nc-export-action nc-disabled';
							$download_label = _x( 'Export Calendar&hellip;', 'command', 'nelio-content' ) . ' ' . _x( '(only available to subscribers)', 'text', 'nelio-content' );
						}//end if
						?>
						<span class="nc-dashicons nc-dashicons-download <?php echo $download_class; ?>" title="<?php
							echo esc_attr( $download_label )
						?>"></span>
					</div>

					<span class="spinner nc-cal-sync" title="<?php
						echo esc_html_x( 'Synching calendar&hellip;', 'text', 'nelio-content' );
					?>"></span>

				</div><!-- .nc-left -->

				<div class="nc-right">

					<?php
					include NELIO_CONTENT_ADMIN_DIR . '/views/partials/filters/post-filter.php';
					?>

					<select class="nc-social-filter"></select>

					<?php
					if ( nc_is_subscribed() ) { ?>
						<span class="nc-task-filter"></span>
					<?php
					} ?>

				</div><!-- .nc-right -->

			</div><!-- .nc-calendar-actions -->

			<div class="nc-days-of-week"></div>

		</div><!-- .nc-calendar-header -->

		<div class="nc-calendar-holder nc-box">

			<div class="nc-calendar">

				<div class="nc-scroll-up-area"></div>

				<div class="nc-grid"></div>

				<?php
				$side = 'left';
				include NELIO_CONTENT_ADMIN_DIR . '/views/partials/calendar/context-actions.php';
				?>

				<?php
				$side = 'right';
				include NELIO_CONTENT_ADMIN_DIR . '/views/partials/calendar/context-actions.php';
				?>

				<div class="nc-scroll-down-area"></div>

			</div><!-- .nc-calendar -->

		</div><!-- .nc-calendar-holder -->

		<div class="nc-unscheduled-posts">

			<div class="nc-title"><?php
				echo esc_html_x( 'Unscheduled', 'title', 'nelio-content' );
			?></div>

			<div class="nc-search">
				<input type="text" placeholder="<?php
					echo esc_attr_x( 'Search&hellip;', 'user', 'nelio-content' );
				?>" />
			</div>

			<div class="nc-box nc-list-and-pages">
				<div class="nc-list"></div>
			</div><!-- .nc-box -->


		</div><!-- .nc-unscheduled-posts -->

	</div><!-- .nc-calendar-page-container -->

	<div class="nc-helper-div"></div>

</script><!-- #_nc-calendar -->
