<?php
/**
 * This partial defines the dialog for creating/editing social templates.
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

<script type="text/template" id="_nc-social-template-editor">

	<div class="nc-profile-selector nc-single"></div>

	<div class="nc-editor">
		<textarea autofocus="autofocus" placeholder="<?php
			echo esc_attr_x( 'Your social message template&hellip;', 'user', 'nelio-content' );
		?>">[*= text *]</textarea>
	</div><!-- .nc-editor -->

	<div class="nc-additional-settings">

		<div class="nc-author">
			<label><?php
				echo esc_html_x( 'Author', 'text', 'nelio-content' );
			?></label>
			<div class="nc-container"></div>
		</div><!-- .nc-author -->

		<div class="nc-post-filter">
			<label><?php
				echo esc_html_x( 'Content', 'text', 'nelio-content' );
			?></label>
			<select><?php

				$settings = Nelio_Content_Settings::instance();
				$include_regular_posts = false;
				$post_types = array();

				$aux = $settings->get( 'calendar_post_types' );
				foreach ( $aux as $post_type_name ) {
					$post_type = get_post_type_object( $post_type_name );
					if ( ! $post_type || is_wp_error( $post_type ) ) {
						continue;
					}//end if
					if ( 'post' === $post_type_name ) {
						$include_regular_posts = true;
						continue;
					}//end if
					array_push( $post_types, $post_type );
				}//end foreach

				if ( count( $post_types ) === 1 && ! $include_regular_posts ) {

					printf( '<option value="nc_any">%s</option>',
						esc_html( $post_types[0]->labels->name )
					);

				} else {

					printf( '<option value="nc_any">%s</option>',
						esc_html_x( 'Any', 'text (post type)', 'nelio-content' )
					);

					foreach ( $post_types as $post_type ) {
						printf( '<option value="%s">%s</option>',
							esc_attr( $post_type->name ),
							esc_html( $post_type->labels->name )
						);
					}//end foreach

				}//end if

				if ( $include_regular_posts ) {

					$ptobj = get_post_type_object( 'post' );

					$aux = array();
					$categories = get_categories( array( 'hide_empty' => false ) );
					foreach ( $categories as $cat ) {
						if ( $cat->parent > 0 ) {
							continue;
						}//end if
						array_push( $aux, $cat );
					}//end foreach
					$categories = $aux;

					if ( count( $post_types ) > 0 ) {
						printf( '<option value="post">%s</option>',
							esc_html( $ptobj->labels->name )
						);
					}//end if

					if ( count( $categories ) > 0 ) {

						foreach ( $categories as $cat ) {
							$label = sprintf( esc_html(
									/* translators: 1: a custom post type name, 2: a category name */
									_x( '%1$s in %2$s', 'text', 'nelio-content' )
								),
								esc_html( $ptobj->labels->name ),
								esc_html( $cat->name )
							);
							printf( '<option value="post:%s"> %s</option>',
								esc_attr( $cat->slug ),
								$label
							);
						}//end foreach

					}//end if

				}//end if

			?></select>
		</div><!-- .nc-post-filter -->

	</div><!-- .nc-additional-settings -->

</script><!-- #_nc-social-template -->

