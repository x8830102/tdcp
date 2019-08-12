<?php
/**
 * This file contains the setting for exporting post data to other calendar
 * tools using an iCal feed.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/lib/settings
 * @author     Antonio Villegas <antonio.villegas@neliosoftware.com>
 * @since      1.4.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * This class represents the setting for exporting post data to other calendar
 * tools using an iCal feed.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/lib/settings
 * @author     Antonio Villegas <antonio.villegas@neliosoftware.com>
 * @since      1.4.2
 */
class Nelio_Content_ICS_Calendar_Setting extends Nelio_Content_Abstract_Setting {

	/**
	 * The current value of this setting.
	 *
	 * @since  1.2.0
	 * @access protected
	 * @var    string
	 */
	protected $value;

	/**
	 * Creates a new instance of this class.
	 *
	 * @since  1.4.2
	 * @access public
	 */
	public function __construct() {

		parent::__construct( 'use_ics_subscription' );

	}//end __construct()

	/**
	 * Specifies the value of the setting.
	 *
	 * @param string $value The current value of this setting.
	 *
	 * @since  1.4.2
	 * @access public
	 */
	public function set_value( $value ) {

		$this->value = $value;

	}//end set_value()

	// @Implements
	/** . @SuppressWarnings( PHPMD.UnusedLocalVariable, PHPMD.ShortVariableName ) */
	public function display() { // @codingStandardsIgnoreLine

		$id      = str_replace( '_', '-', $this->name );
		$name    = $this->option_name . '[' . $this->name . ']';
		$checked = $this->value;
		$desc    = _x( 'Export your calendar posts to Google Calendar or any other calendar tool.', 'command', 'nelio-content' );
		include NELIO_CONTENT_ADMIN_DIR . '/views/partials/settings/ics-feed.php';

	}//end display()

	// @Implements
	public function sanitize( $input ) { // @codingStandardsIgnoreLine

		$checked = false;

		if ( isset( $input[ $this->name ] ) ) {

			if ( 'on' === $input[ $this->name ] ) {
				$checked = true;
			} elseif ( true === $input[ $this->name ] ) {
				$checked = true;
			}//end if

		}//end if

		// Manage key option when needed.
		$ics_secret_key = get_option( 'nc_ics_key', false );
		if ( $checked ) {

			if ( ! $ics_secret_key ) {
				update_option( 'nc_ics_key', wp_generate_password() );
			}//end if

		} else {
			delete_option( 'nc_ics_key' );
		}//end if

		$input[ $this->name ] = $checked;

		return $input;

	}//end sanitize()

}//end class
