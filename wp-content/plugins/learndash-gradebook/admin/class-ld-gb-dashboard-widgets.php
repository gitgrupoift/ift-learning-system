<?php
/**
 * Adds custom Dashboard Widgets.
 *
 * @since 1.1.0
 *
 * @package LearnDash_Gradebook
 * @subpackage LearnDash_Gradebook/admin
 */

defined( 'ABSPATH' ) || die();

/**
 * Class LD_GB_Dashboard_Widgets
 *
 * Adds custom Dashboard Widgets.
 *
 * @since 1.1.0
 *
 * @package LearnDash_Gradebook
 * @subpackage LearnDash_Gradebook/admin
 */
class LD_GB_Dashboard_Widgets {

	/**
	 * Dashboard widgets.
	 *
	 * @since 1.1.0
	 *
	 * @var array
	 */
	public $widgets = array();

	/**
	 * LD_GB_Dashboard_Widgets constructor.
	 *
	 * @since 1.1.0
	 */
	public function __construct() {

		add_action( 'admin_init', array( $this, 'add_widgets'));
	}

	/**
	 * Initializes any cusotm dashboard widgets.
	 *
	 * @since 1.1.0
	 * @access private
	 */
	function add_widgets() {

		if ( get_option( 'ld_gb_disable_dashboard_widget_overview' ) !== '1' ) {

			require_once LEARNDASH_GRADEBOOK_DIR . 'admin/includes/class-ld-gb-gradebook-dashboard-widget.php';
			$this->widgets['gradebook_widget'] = new LD_GB_Gradebook_Dashboard_Widget();
		}
	}
}