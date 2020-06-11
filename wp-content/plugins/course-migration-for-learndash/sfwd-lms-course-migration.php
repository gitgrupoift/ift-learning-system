<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              google.com
 * @since             1.0.2
 * @package           Sfwd_Lms_Course_Migration
 *
 * @wordpress-plugin
 * Plugin Name:        Course Migration for Learndash
 * Plugin URI:        https://wordpress.org/plugins/course-migration-for-learndash
 * Description:       An invaluable tool to migrate your LearnDash course Data
 * Version:           1.0.2
 * Author:            Faizaan Gagan
 * Author URI:        google.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sfwd-lms-course-migration
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'LDCM_BASE_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'LDCM_BASE_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );



/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'SFWD_LMS_COURSE_MIGRATION_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-sfwd-lms-course-migration-activator.php
 */
function activate_sfwd_lms_course_migration() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sfwd-lms-course-migration-activator.php';
	Sfwd_Lms_Course_Migration_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-sfwd-lms-course-migration-deactivator.php
 */
function deactivate_sfwd_lms_course_migration() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sfwd-lms-course-migration-deactivator.php';
	Sfwd_Lms_Course_Migration_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_sfwd_lms_course_migration' );
register_deactivation_hook( __FILE__, 'deactivate_sfwd_lms_course_migration' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-sfwd-lms-course-migration.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_sfwd_lms_course_migration() {

	$plugin = new Sfwd_Lms_Course_Migration();
	$plugin->run();

}
run_sfwd_lms_course_migration();
