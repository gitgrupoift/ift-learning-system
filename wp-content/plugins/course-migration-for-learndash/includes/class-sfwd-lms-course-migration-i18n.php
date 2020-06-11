<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       google.com
 * @since      1.0.0
 *
 * @package    Sfwd_Lms_Course_Migration
 * @subpackage Sfwd_Lms_Course_Migration/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Sfwd_Lms_Course_Migration
 * @subpackage Sfwd_Lms_Course_Migration/includes
 * @author     Faizaan Gagan <fzngagan@gmail.com>
 */
class Sfwd_Lms_Course_Migration_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'sfwd-lms-course-migration',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
