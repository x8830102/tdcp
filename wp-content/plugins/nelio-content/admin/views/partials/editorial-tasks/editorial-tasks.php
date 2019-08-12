<?php
/**
 * The underscore template for rendering a list of editorial tasks.
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

<script type="text/template" id="_nc-editorial-tasks">

<?php
if ( nc_is_subscribed() ) { ?>

	[* if ( 0 === taskCount ) { *]
		<div class="nc-no-tasks"><?php
			echo esc_html_x( 'Keep track of the things that need to get done with tasks.', 'user', 'nelio-content' );
		?></div>
	[* } else { *]
		<div class="nc-task-list-progress">

			<div class="nc-progress">

				<div class="nc-bar-container">
					<div class="nc-bar"></div>
				</div><!-- .nc-bar-container" -->

			</div><!-- .nc-progress -->

			<div class="nc-percentage"></div>

		</div><!-- .nc-task-list-progress -->
	[* } *]

	<div class="nc-tasks"></div>

	<div class="nc-new-task-form-opener">
		<input type="button" class="button" value="<?php
			echo esc_attr_x( 'Add Task', 'command', 'nelio-content' );
		?>" />
	</div><!-- .nc-new-task-form-opener -->
	<div class="nc-new-task-form-container"></div>

<?php
} else { ?>

	<div class="nc-no-tasks"><?php
		echo esc_html_x( 'Keep track of the things that need to get done with tasks.', 'user', 'nelio-content' );
	?> <a target="_blank" href="<?php
		echo esc_url( add_query_arg( array(
			'utm_source'   => 'nelio-content',
			'utm_medium'   => 'plugin',
			'utm_campaign' => 'support',
			'utm_content'  => 'editorial-tasks',
		), __( 'https://neliosoftware.com/content/help/editorial-tasks/', 'nelio-content' ) ) );
	?>"><?php
		echo esc_html_x( 'Learn more&hellip;', 'user', 'nelio-content' );
	?></div>

	<p style="text-align:center;"><a target="_blank" href="<?php
		echo esc_url( add_query_arg( array(
			'utm_source'   => 'nelio-content',
			'utm_medium'   => 'plugin',
			'utm_campaign' => 'subscribe-free-user',
			'utm_content'  => 'editorial-tasks',
		), __( 'https://neliosoftware.com/content/pricing/', 'nelio-content' ) ) );
	?>" class="button"><?php
		echo esc_html_x( 'Subscribe to Unlock', 'command', 'nelio-content' );
	?></a></p>

<?php
} ?>

</script><!-- #_nc-editorial-tasks -->
