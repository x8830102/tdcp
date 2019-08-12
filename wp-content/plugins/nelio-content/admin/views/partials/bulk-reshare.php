<?php
/**
 * TODO.
 *
 * @since 1.4.2
 */

?>
<div class="inline-edit-group wp-clearfix">
	<fieldset>
		<label class="alignleft">
		<span class="title"><?php echo esc_html_x( 'Reshare', 'title', 'nelio-content' ); ?></span>
		<select name="nc_reshare">
			<option value="-1"><?php echo _x( '&mdash; No Change &mdash;', 'text', 'nelio-content' ); ?></option>
			<option value="exclude"><?php echo esc_html_x( 'Exclude from Automatic Reshare', 'command', 'nelio-content' ); ?></option>
			<option value="include"><?php echo esc_html_x( 'Include in Automatic Reshare', 'command', 'nelio-content' ); ?></option>
		</select></label>
	</fieldset>
</div>
