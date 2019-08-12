<?php
/**
 * Template for filtering feeds.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/filters
 * @author     Antonio Villegas <antonio.villegas@neliosoftware.com>
 * @since      1.5.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

$feeds = get_option( 'nc_feeds', array() );
usort( $feeds, array( 'Nelio_Content_Feeds_Ajax_API', 'sort_feeds_by_name' ) );

?>

<select class="nc-feed-filter">

	<option value="all"><?php
		echo esc_html_x( 'Show All Feeds', 'command (feed filter)', 'nelio-content' );
	?></option>

	<optgroup label="<?php echo esc_attr_x( 'Filter by Feed', 'user (feed filter, group name)', 'nelio-content' ); ?>">

		<?php foreach ( $feeds as $feed ) { ?>

			<option value="<?php echo esc_attr( $feed['feed'] ); ?>" data-icon="<?php echo esc_attr( $feed['icon'] ); ?>"><?php
				echo esc_html( $feed['name'] );
			?></option>

		<?php }//end foreach ?>

	</optgroup>

</select><!-- .nc-feed-filter -->
