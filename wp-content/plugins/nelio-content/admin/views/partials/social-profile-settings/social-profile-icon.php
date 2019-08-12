<?php
/**
 * This partial renders a social profile icon.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/partials/social-profiles
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.1.7
 */

/**
 * List of vars used in this partial:
 *
 *  * string $network   the network of the social profile.
 *  * string $condition variable holding whether the current network is enabled or not.
 *  * string $name      the name of the profile.
 *  * string $url       the URL of the profile.
 *  * string $warning   warning message.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

?>

[* if ( isLoadingSocialProfiles ) { *]
<div class="nc-network nc-<?php echo $network; ?> nc-disabled">
		<div class="nc-logo" title="<?php echo $name; ?>"></div>
[* } else if ( <?php echo $condition; ?> ) { *]
	<div class="nc-network nc-<?php echo $network; ?>" data-href="<?php echo $url; ?>" data-network="<?php echo $network; ?>">
		<div class="nc-logo" title="<?php echo $name; ?>"></div>
[* } else { *]
	<div class="nc-network nc-<?php echo $network; ?> nc-disabled">
		<div class="nc-logo" title="<?php echo $warning; ?>"></div>
[* } *]
</div><!-- .nc-<?php echo $network; ?> -->

