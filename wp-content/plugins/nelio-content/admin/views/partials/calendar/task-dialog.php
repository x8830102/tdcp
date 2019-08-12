<?php
/**
 * This partial is used for creating a new task dialog in the calendar page.
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

<script type="text/template" id="_nc-task-dialog">

	<div class="nc-task-dialog">

		<textarea class="nc-task-description" placeholder="<?php
			esc_attr_e( 'Enter a task&hellip;', 'nelio-content' );
		?>"></textarea>

		<div class="nc-assignee-and-date">

			<div class="nc-assignee">

				<label><?php echo esc_html_x( 'Assignee', 'text', 'nelio-content' ); ?></label>
				<div class="nc-field"></div>

			</div><!-- .nc-assignee -->

			<div class="nc-date">

				<label><?php echo esc_html_x( 'Due Date', 'text', 'nelio-content' ); ?></label>
				<div class="nc-field">
					<input class="nc-value" type="date" value="[*~ dateValue *]" min="[*~ today *]" placeholder="<?php
						echo esc_attr_x( 'Select a date&hellip;', 'user', 'nelio-content' );
					?>" />
				</div><!-- .nc-field -->

			</div><!-- .nc-date -->

		</div><!-- .nc-assignee-and-date -->

		<div class="nc-colors">
			<span data-color="red" class="nc-color nc-red" title="<?php esc_attr_e( 'Red', 'text', 'nelio-content' ); ?>"></span>
			<span data-color="orange" class="nc-color nc-orange" title="<?php esc_attr_e( 'Orange', 'text', 'nelio-content' ); ?>"></span>
			<span data-color="yellow" class="nc-color nc-yellow" title="<?php esc_attr_e( 'Yellow', 'text', 'nelio-content' ); ?>"></span>
			<span data-color="green" class="nc-color nc-green" title="<?php esc_attr_e( 'Green', 'text', 'nelio-content' ); ?>"></span>
			<span data-color="cyan" class="nc-color nc-cyan" title="<?php esc_attr_e( 'Cyan', 'text', 'nelio-content' ); ?>"></span>
			<span data-color="blue" class="nc-color nc-blue" title="<?php esc_attr_e( 'Blue', 'text', 'nelio-content' ); ?>"></span>
			<span data-color="purple" class="nc-color nc-purple" title="<?php esc_attr_e( 'Purple', 'text', 'nelio-content' ); ?>"></span>
		</div><!-- .nc-colors -->

	</div><!-- .nc-task-dialog -->

</script><!-- #_nc-task-dialog -->

