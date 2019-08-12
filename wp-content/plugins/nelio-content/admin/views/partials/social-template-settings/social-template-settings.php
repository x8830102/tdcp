<?php
/**
 * This partial is the skeletton for managing publication and reshare templates.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/partials/social-templates
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.3.0
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

<script type="text/template" id="_nc-social-template-settings">

	<div class="nc-publication">
		<h2><span class="nc-dashicons nc-dashicons-megaphone"></span> <?php
			echo esc_html_x( 'Publication', 'title', 'nelio-content' );
		?> <a href="#" class="page-title-action nc-add-template nc-add-publication-template"><?php
			echo esc_html_x( 'Add Template', 'command (template)', 'nelio-content' );
		?></a></h2>
		<div class="nc-empty">
			<p><?php echo esc_html_x( 'Used to promote new posts upon publication.', 'user', 'nelio-content' ); ?></p>
		</div><!-- .nc-empty -->
		<div class="nc-social-templates"></div>
	</div><!-- .nc-publication -->

	<div class="nc-reshare">
		<h2><span class="nc-dashicons nc-dashicons-share-alt"></span> <?php
			echo esc_html_x( 'Reshare', 'title', 'nelio-content' );
		?> <a href="#" class="page-title-action nc-add-template nc-add-reshare-template"><?php
			echo esc_html_x( 'Add Template', 'command (template)', 'nelio-content' );
		?></a></h2>
		<div class="nc-empty">
			<p><?php echo esc_html_x( 'Used to promote posts published some time ago.', 'user', 'nelio-content' ); ?></p>
		</div><!-- .nc-empty -->
		<div class="nc-social-templates"></div>
	</div><!-- .nc-reshare -->

</script><!-- #_nc-social-template-settings -->

