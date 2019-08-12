<?php
/**
 * The partial for rendering description and credit details about an external
 * image.
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
<textarea>[*
	if ( descriptionWithDot.length ) {
		*][*~ descriptionWithDot *] [*
	}
	*][* if ( author.length && authorUrl.length ) { *]<?php
		/* translators: 1: author of the picture; 2: source (such as giphy or unsplash) */
		printf( esc_html_x( 'Picture by %1$s on %2$s.', 'text', 'nelio-content' ),
			'<a href="[*= authorUrl *]" target="_blank">[*~ author *]</a>',
			'<a href="[*= sourceUrl *]" target="_blank">[*~ source *]</a>'
		);
	?>[* } else if ( author.length ) { *]<?php
		/* translators: 1: author of the picture; 2: source (such as giphy or unsplash) */
		printf( esc_html_x( 'Picture by %1$s on %2$s.', 'text', 'nelio-content' ),
			'[*~ author *]',
			'<a href="[*= sourceUrl *]" target="_blank">[*~ source *]</a>'
		);
	?>[* } else { *]<?php
		printf(
			/* translators: an HTML anchor tag */
			esc_html_x( 'Source: %s.', 'text', 'nelio-content' ),
			'<a href="[*= sourceUrl *]" target="_blank">[*~ source *]</a>'
		);
	?>[*
	} *]</textarea>
