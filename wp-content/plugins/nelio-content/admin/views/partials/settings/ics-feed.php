<?php
/**
 * Partial for the ICS Calendar setting.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/views/settings
 * @author     Antonio Villegas <antonio.villegas@neliosoftware.com>
 * @since      1.4.2
 */

 /**
  * List of vars used in this partial:
  *
  * @var string  $id      The identifier of this field.
  * @var string  $name    The name of this field.
  * @var boolean $checked Whether this checkbox is selected or not.
  * @var string  $desc    The description of this field.
  */

 ?>

 <p><input
 	type="checkbox"
 	id="<?php echo esc_attr( $id ); ?>"
 	name="<?php echo esc_attr( $name ); ?>"
 	<?php checked( $checked ); ?> />
 <?php
 echo $desc; // @codingStandardsIgnoreLine
 ?></p>
