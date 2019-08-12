<?php
/**
 * The PHP template to show users who do not hold a subscription.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/partials/media
 * @author     Antonio Villegas <antonio.villegas@neliosoftware.com>
 * @since      1.4.6
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

<div style="position: absolute;top: 50%; left: 50%; transform: translate(-50%, -50%);">

	<a class="button button-primary button-hero" target="_blank" href="<?php
		echo esc_url( admin_url( 'admin.php?page=nelio-content-account' ) );
	?>"><?php echo esc_html_x( 'Subscribe to Nelio Content to unlock this feature.', 'user', 'nelio-content' ); ?></a>

</div>
