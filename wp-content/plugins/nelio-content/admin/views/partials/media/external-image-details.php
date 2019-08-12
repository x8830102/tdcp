<?php
/**
 * The underscore template for rendering details about an external image.
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

/**
 * Get size information for all currently-registered image sizes.
 *
 * @global $_wp_additional_image_sizes
 * @uses   get_intermediate_image_sizes()
 * @return array $sizes Data for all currently-registered image sizes.
 */
function get_image_sizes() {
	global $_wp_additional_image_sizes;

	$size_names = apply_filters(
		'image_size_names_choose', array(
			'thumbnail' => __( 'Thumbnail' ),
			'medium'    => __( 'Medium' ),
			'large'     => __( 'Large' ),
		)
	);

	$sizes = array();

	foreach ( get_intermediate_image_sizes() as $_size ) {
		if ( in_array( $_size, array( 'thumbnail', 'medium', 'medium_large', 'large' ), true ) ) {
			$sizes[ $_size ]['width']  = get_option( "{$_size}_size_w" );
			$sizes[ $_size ]['height'] = get_option( "{$_size}_size_h" );
			$sizes[ $_size ]['crop']   = (bool) get_option( "{$_size}_crop" );
		} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
			$sizes[ $_size ] = array(
				'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
				'height' => $_wp_additional_image_sizes[ $_size ]['height'],
				'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
			);
		}//end if
		$sizes[ $_size ]['name'] = $size_names[ $_size ];
	}//end foreach

	return $sizes;
}

?>
<script type="text/template" id="_nc-external-image-details">

	<div class="nc-image-details attachment-details media-frame">

		<h2><?php echo esc_html_x( 'Attachment Details', 'title', 'nelio-content' ); ?></h2>
		<div class="attachment-info">
			<div class="thumbnail thumbnail-image">
				<img src="[*~ thumbnails.small.image *]" draggable="false" alt="[*~ description *]">
			</div>
			<div class="details">
				<div class="filename">[*~ id *]</div>

				[* if ( authorUrl ) { *]
				<a class="author" href="[*~ authorUrl *]" target="_blank">[*~ author *]</a>
				[* } else if ( author ) { *]
				<span class="author">[*~ author *]</span>
				[* } *]

				<div class="file-size">
					[*~ size *]
				</div>

				<div class="dimensions">
					[* if ( thumbnails.original.width && thumbnails.original.height ) {
						print( thumbnails.original.width + ' × ' + thumbnails.original.height );
					} *]
				</div>

				<a class="edit-attachment" href="[*~ url *]" target="_blank"><?php echo esc_html_x( 'View Source', 'user', 'nelio-content' ); ?></a>
			</div>
		</div>

		<label class="setting" data-setting="url">
			<span class="name"><?php echo esc_html_x( 'URL', 'field', 'nelio-content' ); ?></span>
			<input type="text" value="[*~ url *]" readonly="">
		</label>

		<label class="setting" data-setting="title">
			<span class="name"><?php echo esc_html_x( 'Title', 'field', 'nelio-content' ); ?></span>
			<input type="text" value="[*~ description *]">
		</label>

		<label class="setting" data-setting="caption">
			<span class="name"><?php echo esc_html_x( 'Caption', 'field', 'nelio-content' ); ?></span>
			<?php include NELIO_CONTENT_ADMIN_DIR . '/views/partials/media/external-image-description.php'; ?>
		</label>

		<label class="setting" data-setting="alt">
			<span class="name"><?php echo esc_html_x( 'Alt Text', 'field', 'nelio-content' ); ?></span>
			<input type="text" value="[*~ description *]">
		</label>

		<label class="setting last" data-setting="description">
			<span class="name"><?php echo esc_html_x( 'Description', 'field', 'nelio-content' ); ?></span>
			<?php include NELIO_CONTENT_ADMIN_DIR . '/views/partials/media/external-image-description.php'; ?>
		</label>

		<h2><?php echo esc_html_x( 'Attachment Display Settings', 'title', 'nelio-content' ); ?></h2>

		<label class="setting align">
			<span><?php echo esc_html_x( 'Alignment', 'field', 'nelio-content' ); ?></span>
			<select class="alignment" data-setting="align" data-user-setting="align">
				<option value="left"><?php echo esc_html_x( 'Left', 'option', 'nelio-content' ); ?></option>
				<option value="center"><?php echo esc_html_x( 'Center', 'option', 'nelio-content' ); ?></option>
				<option value="right"><?php echo esc_html_x( 'Right', 'option', 'nelio-content' ); ?></option>
				<option value="none" selected=""><?php echo esc_html_x( 'None', 'option', 'nelio-content' ); ?></option>
			</select>
		</label>

		<div class="setting">
			<label>
				<span><?php echo esc_html_x( 'Link To', 'field', 'nelio-content' ); ?></span>
				<select class="link-to" data-setting="link" data-user-setting="urlbutton">
					<option value="none" selected=""><?php echo esc_html_x( 'None', 'option', 'nelio-content' ); ?></option>
					<option value="file"><?php echo esc_html_x( 'Media File', 'option', 'nelio-content' ); ?></option>
					<option value="custom"><?php echo esc_html_x( 'Custom URL', 'option', 'nelio-content' ); ?></option>
				</select>
			</label>
			<input type="text" class="link-to-custom hidden" data-setting="linkUrl">
		</div>

		<label class="setting">
			<span><?php echo esc_html_x( 'Size', 'field', 'nelio-content' ); ?></span>
			<select class="size" name="size" data-setting="size" data-user-setting="imgsize"><?php
			if ( 'pixabay' === $source ) {
				$sizes = get_image_sizes();

				foreach ( $sizes as $name => $size ) :
					if ( isset( $size['name'] ) ) { ?>
					<option value="<?php echo esc_attr( $name ); ?>" data-width="<?php echo esc_html( $size['width'] ); ?>" data-height="<?php echo esc_html( $size['height'] ); ?>">
						<?php echo esc_html( $size['name'] ); ?> &ndash; <?php echo esc_html( $size['width'] ); ?> &times; <?php echo esc_html( $size['height'] ); ?>
					</option><?php
					}//end if
				endforeach; ?>

				<option value="full" selected="selected"><?php echo esc_html_x( 'Full Size', 'option', 'nelio-content' ); ?>[*
					if ( thumbnails.original.width && thumbnails.original.height ) {
						*] – [*~ thumbnails.original.width *] × [*~ thumbnails.original.height *][*
					} *]</option><?php

			} else { ?>

				[* if ( thumbnails.small ) { *]
					<option value="small"><?php echo esc_html_x( 'Small', 'option', 'nelio-content' ); ?>[*
						if ( thumbnails.small.width && thumbnails.small.height ) {
							*] – [*~ thumbnails.small.width *] × [*~ thumbnails.small.height *][*
						} *]
					</option>
				[* } *]
				[* if ( thumbnails.medium ) { *]
					<option value="medium"><?php echo esc_html_x( 'Medium', 'option', 'nelio-content' ); ?>[*
						if ( thumbnails.medium.width && thumbnails.medium.height ) {
							*] – [*~ thumbnails.medium.width *] × [*~ thumbnails.medium.height *][*
						} *]
					</option>
				[* } *]
				[* if ( thumbnails.large ) { *]
					<option value="large" selected="selected"><?php echo esc_html_x( 'Large', 'option', 'nelio-content' ); ?>[*
						if ( thumbnails.large.width && thumbnails.large.height ) {
							*] – [*~ thumbnails.large.width *] × [*~ thumbnails.large.height *][*
						} *]
					</option>
				[* } *]
				[* if ( thumbnails.original ) { *]
					<option value="original"><?php echo esc_html_x( 'Original', 'option', 'nelio-content' ); ?>[*
						if ( thumbnails.original.width && thumbnails.original.height ) {
							*] – [*~ thumbnails.original.width *] × [*~ thumbnails.original.height *][*
						} *]</option>
				[* } *]<?php
			}//end if ?>
			</select>
		</label>

	</div><!-- .nc-image-details -->

</script><!-- #_nc-external-image-details -->
