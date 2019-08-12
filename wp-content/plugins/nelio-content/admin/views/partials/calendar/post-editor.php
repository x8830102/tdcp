<?php
/**
 * This partial is used for creating/editing a post in the calendar page.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/partials/calendar
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

/**
 * List of vars used in this page:
 *
 * None.
 */

?>

<script type="text/template" id="_nc-post-editor">

	<div class="nc-post-editor-dialog">

		<div class="nc-post-information">

			<div class="nc-title-and-edit">

				<input class="nc-title" name="post-title" type="text" placeholder="<?php
					echo esc_attr_x( 'Title', 'text', 'nelio-content' );
				?>" value="[*~ title *]" />

				<?php
				$settings = Nelio_Content_Settings::instance();
				$all_post_types = get_post_types( array( 'public' => true ), 'objects' );
				$calendar_post_types = $settings->get( 'calendar_post_types' );

				$i = 1;
				$options = array();
				foreach ( $all_post_types as $pt ) {

					// We'll work with posts later.
					if ( 'post' === $pt->name ) {
						continue;
					}//end if

					if ( ! in_array( $pt->name, $calendar_post_types ) ) {
						continue;
					}//end if

					$label = esc_html( $pt->labels->singular_name );
					$options[ $label . $i ] = "<option value=\"$pt->name\">$label</option>";
					++$i;

				}//end foreach
				ksort( $options );

				$categories = array();
				$aux = get_post_type_object( 'post' );
				$post_label = $aux->labels->singular_name;
				if ( in_array( 'post', $calendar_post_types ) ) {

					$options['post'] = '';

					$aux = array();
					$categories = get_categories( array( 'hide_empty' => false ) );
					foreach ( $categories as $cat ) {
						if ( $cat->parent > 0 ) {
							continue;
						}//end if
						array_push( $aux, $cat );
					}//end foreach
					$categories = $aux;

				}//end if

				?>

				<?php
				if ( count( $options ) > 1 ) { ?>

					[* if ( 0 === id ) { *]
						<select class="nc-post-type"><?php

							unset( $options['post'] );

							foreach ( $options as $option ) {
								echo $option;
							}//end foreach

							foreach ( $categories as $cat ) {
								$label = esc_html(
									/* translators: a category name */
									sprintf( _x( 'Post in %s', 'text', 'nelio-content' ), $cat->name )
								);
								printf( '<option value="post:%s">%s</option>',
									esc_attr( $cat->cat_ID ),
									$label
								);
							}//end foreach

						?></select>
					[* } *]

				<?php
				} else if ( count( $categories ) > 0 ) { ?>

					[* if ( 0 === id && 'post' === type ) { *]
						<select class="nc-post-type"><?php
							foreach ( $categories as $cat ) {
								printf( '<option value="post:%s">%s</option>',
									esc_attr( $cat->cat_ID ),
									esc_html( $cat->name )
								);
							}//end foreach
						?></select>
					[* } *]

				<?php
				} ?>

			</div><!-- .nc-title-and-edit -->

			<div class="nc-author-date-and-time">

				<div class="nc-author">
					<label for="post-author"><?php
						echo esc_html_x( 'Author', 'text', 'nelio-content' );
					?></label>
					<div class="nc-field"></div>
				</div><!-- .nc-post-author -->

				<div class="nc-date">
					<label><?php
						echo esc_html_x( 'Date', 'text', 'nelio-content' );
					?></label>

					<input class="nc-value" type="date" value="[*~ localDate *]" min="[*~ today *]" placeholder="<?php
						echo esc_attr_x( 'Select a date&hellip;', 'user', 'nelio-content' );
					?>"[* if ( 'publish' === status ) { *]disabled="disabled"[* } *] />

				</div><!-- .nc-date -->

				<div class="nc-time">
					<label><?php
						echo esc_html_x( 'Time', 'text', 'nelio-content' );
					?></label>

					<input class="nc-value" type="time" value="[*~ localTime *]" placeholder="HH:MM" [* if ( 'publish' === status ) { *]disabled="disabled"[* } *] />

				</div><!-- .nc-time -->

			</div><!-- .nc-author-date-and-time -->

		</div><!-- .nc-post-information -->

		[* if ( 0 === id ) { *]

			<div class="nc-editor-information">

				<h3 class="nc-toggle-editor-visibility">
					<?php echo esc_html_x( 'Editor Information', 'title', 'nelio-content' ); ?>
					[* if ( isEditorInformationVisible ) { *]
						<span class="nc-dashicons nc-dashicons-arrow-up nc-indicator"></span>
					[* } else { *]
						<span class="nc-dashicons nc-dashicons-arrow-down nc-indicator"></span>
					[* } *]
				</h3>

				[* if ( isEditorInformationVisible ) { *]

					<div class="nc-suggested-reference">
						<label for="nc-suggested-reference"><?php
							echo esc_html_x( 'Suggested Reference', 'text', 'nelio-content' );
						?></label>
						<div class="nc-field">
							<input type="url" name="nc-suggested-reference" placeholder="<?php
								echo esc_attr_x( 'Suggest a reference&hellip;', 'user', 'nelio-content' );
							?>" value="[*~ suggestedReference *]" />
						</div><!-- .nc-field -->
					</div><!-- .nc-post-author -->

					<?php
					if ( nc_is_subscribed() ) { ?>

						<div class="nc-initial-comment">
							<label for="nc-initial-comment"><?php
								echo esc_html_x( 'Initial Comment', 'text', 'nelio-content' );
							?></label>
							<div class="nc-field">
								<textarea type="url" name="nc-initial-comment" placeholder="<?php
									echo esc_attr_x( 'Leave a comment to the author&hellip;', 'user', 'nelio-content' );
								?>">[*= editorialComment *]</textarea>
							</div><!-- .nc-field -->
						</div><!-- .nc-post-author -->

					<?php
					}//end if ?>

				[* } *]

			</div><!-- .nc-editor-information -->

		[* } *]

	</div><!-- .nc-post-editor-dialog -->

</script><!-- #_nc-post-editor -->

