<?php
/**
 * The underscore template for rendering the featured image meta box.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/partials/featured-image
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.1.1
 */

/**
 * List of vars used in this partial:
 *
 * None.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

$settings = Nelio_Content_Settings::instance();
$click_to_edit = esc_html_x( 'Click the image to edit or update', 'user', 'nelio-content' );

?>

<script type="text/template" id="_nc-featured-image">

	<input type="hidden" name="_nc-featured-image-meta-box" value="true" />

	[* if ( 'efi' === mode ) { *]

		<input type="hidden" name="_nelioefi_url" value="[*= url *]" />
		<input type="hidden" name="_nelioefi_alt" value="[*~ alt *]" />
		<div class="nc-image-container">
			<img class="nc-edit-efi" src="[*~ url *]" alt="[*~ alt *]" />
		</div>

		<p class="nc-click-to-edit"><?php echo $click_to_edit; ?></p>

		<p><a href="#" class="nc-remove-featured-image"><?php
				echo esc_html_x( 'Remove featured image', 'text', 'nelio-content' );
		?></a></p>

	[* } else { *]

		<p><a href="#" class="nc-set-efi"><?php
			echo esc_html_x( 'Set external featured image', 'action', 'nelio-content' );
		?></a></p>

	[* } *]

</script><!-- #_nc-featured-image -->
