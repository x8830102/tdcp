<?php
/**
 * The underscore template of a new task form.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/partials/editorial-tasks
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
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

<script type="text/template" id="_nc-new-editorial-task">

	<div class="nc-new-task">

		<textarea placeholder="<?php esc_attr_e( 'Enter a task&hellip;', 'nelio-content' ); ?>"></textarea>

		<div class="nc-assignee-and-date">
			<label><?php echo esc_html_x( 'Assignee', 'text', 'nelio-content' ); ?></label>
			<div class="nc-assignee"></div>
			<label><?php echo esc_html_x( 'Due Date', 'text', 'nelio-content' ); ?></label>
			<div class="nc-date"></div>
			<div class="nc-colors">
				<span data-color="red" class="nc-color nc-red" title="<?php esc_attr_e( 'Red', 'text', 'nelio-content' ); ?>"></span>
				<span data-color="orange" class="nc-color nc-orange" title="<?php esc_attr_e( 'Orange', 'text', 'nelio-content' ); ?>"></span>
				<span data-color="yellow" class="nc-color nc-yellow" title="<?php esc_attr_e( 'Yellow', 'text', 'nelio-content' ); ?>"></span>
				<span data-color="green" class="nc-color nc-green" title="<?php esc_attr_e( 'Green', 'text', 'nelio-content' ); ?>"></span>
				<span data-color="cyan" class="nc-color nc-cyan" title="<?php esc_attr_e( 'Cyan', 'text', 'nelio-content' ); ?>"></span>
				<span data-color="blue" class="nc-color nc-blue" title="<?php esc_attr_e( 'Blue', 'text', 'nelio-content' ); ?>"></span>
				<span data-color="purple" class="nc-color nc-purple" title="<?php esc_attr_e( 'Purple', 'text', 'nelio-content' ); ?>"></span>
			</div><!-- .nc-colors -->
		</div><!-- .nc-assignee-and-date -->

		<div class="nc-actions">
			<input type="button" class="button nc-cancel" value="<?php
				echo esc_attr_x( 'Cancel', 'command', 'nelio-content' );
			?>" />
			<input type="button" class="button button-primary button-disabled nc-add-task" value="<?php
				echo esc_attr_x( 'Add Task', 'command', 'nelio-content' );
			?>" />
		</div><!-- .nc-actions -->

	</div><!-- .nc-new-task -->

</script><!-- #_nc-new-editorial-task -->
