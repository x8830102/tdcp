<?php
/**
 * The template of the export dialog in the Editorial Calendar.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/partials/analytics
 * @author     Antonio Villegas <antonio.villegas@neliosoftware.com>
 * @since      1.4.2
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

<script type="text/template" id="_nc-export-dialog">
	<div class="nc-export-dialog">

		<div class="nc-download-csv-block">
			<p><?php echo _x( 'Export your editorial calendar in CSV format:', 'user', 'nelio-content' ); ?></p>
			<button class="button button-primary nc-download-csv"><?php
				echo esc_html_x( 'Download CSV', 'action', 'nelio-content' );
			?></button>
		</div><!-- .nc-download-csv-block -->

	<?php
	// Only show .ics subscriptions when the setting is active.
	$settings = Nelio_Content_Settings::instance();
	$ics_secret_key = get_option( 'nc_ics_key', false );

	if ( $settings->get( 'use_ics_subscription' ) && $ics_secret_key ) { ?>

		<div class="nc-export-ics-block">

			<p><?php echo _x( 'Export your editorial calendar to Google Calendar or any tool that supports iCalendar (.ics) format.', 'user', 'nelio-content' ); ?></p>

			<?php
			$args = array(
				'action' => 'nelio_content_calendar_ics_subscription',
				'user'   => wp_get_current_user()->user_login,
				'key'    => md5( wp_get_current_user()->user_login . $ics_secret_key ),
			);
			$user_link = add_query_arg( $args, admin_url( 'admin-ajax.php' ) ); ?>

			<div class="nc-url-export-block">
				<p><?php echo _x( 'URL to export <strong>your posts</strong>:', 'user', 'nelio-content' ); ?></p>
				<input type="text" name="nelio-content-ics-user-link"
					value="<?php echo $user_link; ?>" readonly />
			</div>

		<?php
		if ( nc_is_current_user( 'editor' ) ) {

			$args = array(
				'action' => 'nelio_content_calendar_ics_subscription',
				'key'    => md5( 'all' . $ics_secret_key ),
			);
			$link = add_query_arg( $args, admin_url( 'admin-ajax.php' ) ); ?>

			<div class="nc-url-export-block">
				<p><?php echo _x( 'URL to export <strong>all the posts</strong>:', 'user', 'nelio-content' ); ?></p>
				<input type="text" name="nelio-content-ics-global-link"
					value="<?php echo $link; ?>" readonly />
			</div>

		<?php }//end if
		?></div><!-- .nc-export-ics-block -->
	<?php }//end if
	?></div>
</script><!-- #_nc-export-dialog -->
