<?php
/**
 * This partial shows a warning message in thep lugins page.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/partials
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

?>

<tr class="plugin-update-tr active" id="nelio-content-staging-warning" data-slug="nelio-content" data-plugin="nelio-content/nelio-content.php">
	<td colspan="3" class="plugin-update colspanchange">
		<div class="notice inline notice-warning notice-alt">
			<p><?php
				printf(
					/* translators: a URL */
					_x( '<strong>Warning!</strong> This site has been identified as a <strong>staging site</strong> and, as a result, you can\'t use any of Nelio Content\'s features. If this is not correct and you want to use Nelio Content normally, please <a href="%s">follow these instructions</a>.', 'user', 'nelio-content' ),
					add_query_arg( array(
						'utm_source'   => 'nelio-content',
						'utm_medium'   => 'plugin',
						'utm_campaign' => 'support',
						'utm_content'  => 'staging',
					), __( 'https://neliosoftware.com/content/help/modify-list-of-staging-urls/', 'nelio-content' ) )
				);
				remove_filter( 'after_plugin_row_nelio-content/nelio-content.php', 'nc_add_staging_notice', 99 );
			?></p>
		</div>
	</td>
</tr>

<script>
(function() {
	document.getElementById( 'nelio-content-staging-warning' ).previousElementSibling.className += ' update';
})();
</script>

