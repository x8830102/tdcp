<?php
/**
 * This partial contains the list of available networks for which we can connect social profiles.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/partials/social-profiles
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

// URL to which our cloud has to redirect the user once a social profile has been successfully completed.
$redirect_url = add_query_arg( 'nc-page', 'connected-profile-callback', admin_url( 'admin.php' ) );

?>

<script type="text/template" id="_nc-connect-googleplus-profile-dialog">

	<p><?php
		echo esc_html_x( 'Please select the Google+ resource you want to connect to Nelio Content (requires a Buffer account):', 'user', 'nelio-content' );
	?></p>

	<div class="nc-network-type-dialog nc-networks">

		<?php
		$url = nc_get_api_url( '/connect/googleplus', 'browser' );
		$url = add_query_arg( 'siteId', nc_get_site_id(), $url );
		$url = add_query_arg( 'creatorId', get_current_user_id(), $url );
		$url = add_query_arg( 'redirect', $redirect_url, $url );
		$url = add_query_arg( 'lang', nc_get_language(), $url );
		$url = esc_url( $url );
		?>
		<div class="nc-network nc-googleplus">
			<div class="nc-logo" data-href="<?php echo $url; ?>" title="<?php
				echo esc_attr_x( 'Google+ Personal Profile (via Buffer)', 'text', 'nelio-content' );
			?>"></div>
			<?php echo esc_html_x( 'Personal', 'text (googleplus)', 'nelio-content' ); ?>
		</div><!-- .nc-googleplus-personal -->

		<?php
		$url = nc_get_api_url( '/connect/googleplus/page', 'browser' );
		$url = add_query_arg( 'siteId', nc_get_site_id(), $url );
		$url = add_query_arg( 'creatorId', get_current_user_id(), $url );
		$url = add_query_arg( 'redirect', $redirect_url, $url );
		$url = add_query_arg( 'lang', nc_get_language(), $url );
		$url = esc_url( $url );
		?>
		<div class="nc-network nc-googleplus-page">
			<div class="nc-logo" data-href="<?php echo $url; ?>" title="<?php
				echo esc_attr_x( 'Google+ Page (via Buffer)', 'text', 'nelio-content' );
			?>"></div>
			<?php echo esc_html_x( 'Page', 'text (googleplus)', 'nelio-content' ); ?>
		</div><!-- .nc-googleplus-page -->

	</div><!-- .nc-networks -->

</script><!-- #_nc-connect-googleplus-profile-dialog -->

