<?php
/**
 * Welcome page for the plugin.
 *
 * @since 1.2.0
 */

defined( 'ABSPATH' ) || die();

/**
 * Class LD_GB_WelcomePage
 *
 * Welcome page for the plugin.
 *
 * @since 1.2.0
 */
class LD_GB_WelcomePage {

	/**
	 * LD_GB_WelcomePage constructor.
	 *
	 * @since 1.2.0
	 */
	function __construct() {

		add_action( 'admin_menu', array( $this, 'add_page' ) );
		add_action( 'admin_head', array( $this, 'remove_submenu_item' ), 1 );
	}

	/**
	 * Adds the welcome page.
	 *
	 * @since 1.2.0
	 * @access private
	 */
	function add_page() {

		add_dashboard_page(
			__( 'Welcome to LearnDash Gradebook', 'learndash-gradebook' ),
			__( 'Welcome to LearnDash Gradebook', 'learndash-gradebook' ),
			( defined( 'LEARNDASH_ADMIN_CAPABILITY_CHECK' ) && LEARNDASH_ADMIN_CAPABILITY_CHECK != '' ) ? LEARNDASH_ADMIN_CAPABILITY_CHECK : 'manage_options',
			'learndash-gradebook-welcome',
			array( $this, 'page_output' )
		);
	}

	/**
	 * Remove from menu.
	 *
	 * @since 1.2.0
	 * @access private
	 */
	function remove_submenu_item() {

		remove_submenu_page( 'index.php', 'learndash-gradebook-welcome' );
	}

	/**
	 * Welcome page output.
	 *
	 * @since 1.2.0
	 * @access private
	 */
	function page_output() {

		$active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'whats-new';

		include_once LEARNDASH_GRADEBOOK_DIR . 'admin/views/html-welcome-page.php';
	}
}