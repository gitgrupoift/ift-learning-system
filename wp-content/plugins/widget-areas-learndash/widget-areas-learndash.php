<?php
/**
 * Plugin Name:       Widget Areas for LearnDash
 * Description:       Adds multiple widget areas throughout the LearnDash interface. This plugin enables you to add unlimited widgets to several areas of Focus Mode & LearnDash course pages.
 * Version:           1.0
 * Author:            Escape Creative
 * Author URI:        https://escapecreative.com/
 * License:           GPLv3
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       widget-areas-learndash
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Current plugin version.
 * Start at version 1.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'LDX_LEARNDASH_WIDGET_AREAS_VERSION', '1.0' );

/**
 * Define Constants
 */
define( 'LDX_LEARNDASH_WIDGET_AREAS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );


/**
 * Register Sidebars / Widget Areas
 *
 * @since 1.0
 */
include_once LDX_LEARNDASH_WIDGET_AREAS_PLUGIN_DIR . 'inc/register-sidebars.php';


/**
 * Action Hooks / Assign Widgets
 *
 * @since 1.0
 */
include_once LDX_LEARNDASH_WIDGET_AREAS_PLUGIN_DIR . 'inc/action-hooks.php';
