<?php
/**
 * LearnDash - Gradebook
 *
 * @package     LearnDash_Gradebook
 * @author      Real Big Plugins
 * @copyright   2016 Real Big Plugins
 * @license     GPL2
 *
 * Plugin Name: LearnDash - Gradebook
 * Description: Adds Gradebook functionality to LearnDash LMS.
 * Version: 1.6.5
 * Author: Real Big Plugins
 * Author URI: https://realbigplugins.com
 * Text Domain: learndash-gradebook
 * Domain Path: /languages
 * License: GPL2
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

defined( 'ABSPATH' ) || die();

if ( ! class_exists( 'LearnDash_Gradebook' ) ) {

	define( 'LEARNDASH_GRADEBOOK_VERSION', '1.6.5' );
	define( 'LEARNDASH_GRADEBOOK_DIR', plugin_dir_path( __FILE__ ) );
	define( 'LEARNDASH_GRADEBOOK_URI', plugins_url( '', __FILE__ ) );

	/**
	 * Class LearnDash_Gradebook
	 *
	 * Initiates the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @package LearnDash_Gradebook
	 */
	final class LearnDash_Gradebook {

		/**
		 * API module.
		 *
		 * @since 1.2.0
		 *
		 * @var LD_GB_API
		 */
		public $api;

		/**
		 * RBM Field Helpers instance.
		 *
		 * @since 1.2.0
		 *
		 * @var RBM_FieldHelpers
		 */
		public $field_helpers;

		/**
		 * RBP Support instance.
		 *
		 * @since 1.2.0
		 *
		 * @var RBP_Support
		 */
		public $support;

		/**
		 * Post Types module.
		 *
		 * @since 1.2.0
		 *
		 * @var LD_GB_PostTypes
		 */
		public $posttypes;

		/**
		 * Upgrade module.
		 *
		 * @since 1.2.0
		 *
		 * @var LD_GB_Upgrade
		 */
		public $upgrade;

		/**
		 * Welcome Page module.
		 *
		 * @since 1.2.0
		 *
		 * @var LD_GB_WelcomePage
		 */
		public $welcome_page;

		/**
		 * Admin Pages module.
		 *
		 * @since 1.1.0
		 *
		 * @var LD_GB_AdminPages
		 */
		public $admin_pages;

		/**
		 * Gradebook Page module.
		 *
		 * @since 1.0.0
		 *
		 * @var LD_GB_AdminPage_Gradebook
		 */
		public $gradebook_page;

		/**
		 * Shortcodes module.
		 *
		 * @since 1.0.0
		 *
		 * @var LD_GB_Shortcodes
		 */
		public $shortcodes;

		/**
		 * Dashboard Widgets module.
		 *
		 * @since 1.1.0
		 *
		 * @var LD_GB_Dashboard_Widgets
		 */
		public $dashboard_widgets;

		/**
		 * Settings page module.
		 *
		 * @since 1.0.0
		 *
		 * @var LD_GB_AdminPage_Settings
		 */
		public $settings_page;

		/**
		 * User Grades page module.
		 *
		 * @since 1.2.0
		 *
		 * @var LD_GB_AdminPage_UserGrades
		 */
		public $usergrades_page;

		/**
		 * Notices module.
		 *
		 * @since 1.0.0
		 *
		 * @var LD_GB_Notices
		 */
		public $notices;

		/**
		 * Quickstart module.
		 *
		 * @since 1.0.0
		 *
		 * @var LD_GB_QuickStart
		 */
		public $quickstart;

		/**
		 * Disable cloning.
		 *
		 * @since 1.0.0
		 */
		protected function __clone() {
		}

		/**
		 * Call this method to get singleton
		 *
		 * @since 1.0.0
		 *
		 * @return LearnDash_Gradebook()
		 */
		public static function instance() {

			static $instance = null;

			if ( $instance === null ) {
				$instance = new LearnDash_Gradebook();
			}

			return $instance;
		}

		/**
		 * AC constructor.
		 *
		 * @since 1.0.0
		 */
		private function __construct() {

			$this->require_necessities();

			$this->setup_fieldhelpers();
			$this->setup_support();

			add_action( 'init', array( $this, 'register_assets' ) );

			add_action( 'current_screen', array( $this, 'maybe_enqueue_assets' ), 999 );

			if ( isset( $_GET['ld_gb_install_roles'] ) ) {
				add_action( 'init', array( $this, 'setup_roles' ) );
			}

			add_filter( 'learndash_get_label', array( $this, 'learndash_get_label' ), 10, 2 );

		}

		/**
		 * Requires all plugin files.
		 *
		 * @since 1.0.0
		 */
		private function require_necessities() {

			require_once LEARNDASH_GRADEBOOK_DIR . 'core/ld-gb-fieldhelper-functions.php';
			require_once LEARNDASH_GRADEBOOK_DIR . 'core/api/class-ld-gb-api.php';
			require_once LEARNDASH_GRADEBOOK_DIR . 'core/post-types/class-ld-gb-posttypes.php';
			require_once LEARNDASH_GRADEBOOK_DIR . 'core/class-ld-gb-upgrade.php';
			require_once LEARNDASH_GRADEBOOK_DIR . 'core/class-ld-gb-shortcode.php';
			require_once LEARNDASH_GRADEBOOK_DIR . 'core/class-ld-gb-shortcodes.php';
			require_once LEARNDASH_GRADEBOOK_DIR . 'core/class-ld-gb-user-grade.php';

			$this->api        = new LD_GB_API();
			$this->posttypes  = new LD_GB_PostTypes();
			$this->upgrade    = new LD_GB_Upgrade();
			$this->shortcodes = new LD_GB_Shortcodes();

			// Admin
			if ( is_admin() ) {

				require_once LEARNDASH_GRADEBOOK_DIR . 'admin/includes/class-ld-gb-gradebook-list-table.php';
				require_once LEARNDASH_GRADEBOOK_DIR . 'admin/class-ld-gb-welcome-page.php';
				require_once LEARNDASH_GRADEBOOK_DIR . 'admin/class-ld-gb-admin-pages.php';
				require_once LEARNDASH_GRADEBOOK_DIR . 'admin/class-ld-gb-adminpage-gradebook.php';
				require_once LEARNDASH_GRADEBOOK_DIR . 'admin/class-ld-gb-adminpage-settings.php';
				require_once LEARNDASH_GRADEBOOK_DIR . 'admin/class-ld-gb-adminpage-user-grades.php';
				require_once LEARNDASH_GRADEBOOK_DIR . 'admin/class-ld-gb-dashboard-widgets.php';
				require_once LEARNDASH_GRADEBOOK_DIR . 'admin/class-ld-gb-notices.php';
				require_once LEARNDASH_GRADEBOOK_DIR . 'admin/class-ld-gb-quickstart.php';

				$this->welcome_page      = new LD_GB_WelcomePage();
				$this->admin_pages       = new LD_GB_AdminPages();
				$this->gradebook_page    = new LD_GB_AdminPage_Gradebook();
				$this->settings_page     = new LD_GB_AdminPage_Settings();
				$this->usergrades_page   = new LD_GB_AdminPage_UserGrades();
				$this->dashboard_widgets = new LD_GB_Dashboard_Widgets();
				$this->notices           = new LD_GB_Notices();

				if ( version_compare( get_bloginfo( 'version' ), '3.3', '>' ) ) {

					$this->quickstart = new LD_GB_QuickStart();
				}
			}
		}

		/**
		 * Initializes Field Helpers.
		 *
		 * @since 1.2.0
		 * @access private
		 */
		private function setup_fieldhelpers() {

			require_once LEARNDASH_GRADEBOOK_DIR . 'core/library/rbm-field-helpers/rbm-field-helpers.php';

			// Last l10n update: RBP Field Helpers v1.4.8
			$this->field_helpers = new RBM_FieldHelpers( array(
				'ID'   => 'ld_gb',
				'l10n' => array(
					'field_table'    => array(
						'delete_row'    => __( 'Delete Row', 'learndash-gradebook' ),
						'delete_column' => __( 'Delete Column', 'learndash-gradebook' ),
					),
					'field_select'   => array(
						'no_options'       => __( 'No select options.', 'learndash-gradebook' ),
						'error_loading'    => __( 'The results could not be loaded', 'learndash-gradebook' ),
						/* translators: %d is number of characters over input limit */
						'input_too_long'   => __( 'Please delete %d character', 'learndash-gradebook' ),
						/* translators: %d is number of characters under input limit */
						'input_too_short'  => __( 'Please enter %d or more characters', 'learndash-gradebook' ),
						'loading_more'     => __( 'Loading more results...', 'learndash-gradebook' ),
						/* translators: %d is maximum number items selectable */
						'maximum_selected' => __( 'You can only select %d item', 'learndash-gradebook' ),
						'no_results'       => __( 'No results found', 'learndash-gradebook' ),
						'searching'        => __( 'Searching...', 'learndash-gradebook' ),
					),
					'field_repeater' => array(
						'collapsable_title' => __( 'New Row', 'learndash-gradebook' ),
						'confirm_delete'    => __( 'Are you sure you want to delete this element?', 'learndash-gradebook' ),
						'delete_item'       => __( 'Delete', 'learndash-gradebook' ),
						'add_item'          => __( 'Add', 'learndash-gradebook' ),
					),
					'field_media'    => array(
						'button_text'        => __( 'Upload / Choose Media', 'learndash-gradebook' ),
						'button_remove_text' => __( 'Remove Media', 'learndash-gradebook' ),
						'window_title'       => __( 'Choose Media', 'learndash-gradebook' ),
					),
					'field_checkbox' => array(
						'no_options_text' => __( 'No options available.', 'learndash-gradebook' ),
					),
				),
			) );
		}

		/**
		 * Initialize RBP Support.
		 *
		 * @since 1.2.0
		 * @access private
		 */
		private function setup_support() {

			require_once LEARNDASH_GRADEBOOK_DIR . 'core/library/rbp-support/rbp-support.php';

			// Last l10n update: RBP Support v1.2.3
			$this->support = new RBP_Support( __FILE__, array(
				'support_form'           => array(
					'enabled'  => array(
						'title'           => _x( 'Need some help with %s?', '%s is the Plugin Name', 'learndash-gradebook' ),
						'subject_label'   => __( 'Subject', 'learndash-gradebook' ),
						'message_label'   => __( 'Message', 'learndash-gradebook' ),
						'send_button'     => __( 'Send', 'learndash-gradebook' ),
						'subscribe_text'  => _x( 'We make other cool plugins and share updates and special offers to anyone who %ssubscribes here%s.', 'Both %s are used to place HTML for the <a> in the message', 'learndash-gradebook' ),
						'validationError' => _x( 'This field is required', 'Only used by legacy browsers for JavaScript Form Validation', 'learndash-gradebook' ),
						'success'         => __( 'Support message succesfully sent!', 'learndash-gradebook' ),
						'error'           => __( 'Could not send support message.', 'learndash-gradebook' ),
					),
					'disabled' => array(
						'title'            => _x( 'Need some help with %s?', '%s is the Plugin Name', 'learndash-gradebook' ),
						'disabled_message' => __( 'Premium support is disabled. Please register your product and activate your license for this website to enable.', 'learndash-gradebook' )
					),
				),
				'licensing_fields'       => array(
					'title'                    => _x( '%s License', '%s is the Plugin Name', 'learndash-gradebook' ),
					'deactivate_button'        => __( 'Deactivate', 'learndash-gradebook' ),
					'activate_button'          => __( 'Activate', 'learndash-gradebook' ),
					'delete_deactivate_button' => __( 'Delete and Deactivate', 'learndash-gradebook' ),
					'delete_button'            => __( 'Delete', 'learndash-gradebook' ),
					'license_active_label'     => __( 'License Active', 'learndash-gradebook' ),
					'license_inactive_label'   => __( 'License Inactive', 'learndash-gradebook' ),
					'save_activate_button'     => __( 'Save and Activate', 'learndash-gradebook' ),
				),
				'license_nag'            => array(
					'register_message' => _x( 'Register your copy of %s now to receive automatic updates and support.', '%s is the Plugin Name', 'learndash-gradebook' ),
					'purchase_message' => _x( 'If you do not have a license key, you can %1$spurchase one%2$s.', 'Both %s are used to place HTML for the <a> in the message', 'learndash-gradebook' ),
				),
				'license_activation'     => _x( '%s license successfully activated.', '%s is the Plugin Name', 'learndash-gradebook' ),
				'license_deletion'       => _x( '%s license successfully deleted.', '%s is the Plugin Name', 'learndash-gradebook' ),
				'license_deactivation'   => array(
					'error'   => _x( 'Error: could not deactivate the license for %s', '%s is the Plugin Name', 'learndash-gradebook' ),
					'success' => _x( '%s license successfully deactivated.', '%s is the Plugin Name', 'learndash-gradebook' ),
				),
				'license_error_messages' => array(
					'expired'             => _x( 'Your license key expired on %s.', '%s is a localized timestamp', 'learndash-gradebook' ),
					'revoked'             => __( 'Your license key has been disabled.', 'learndash-gradebook' ),
					'missing'             => __( 'Invalid license.', 'learndash-gradebook' ),
					'site_inactive'       => __( 'Your license is not active for this URL.', 'learndash-gradebook' ),
					'item_name_mismatch'  => _x( 'This appears to be an invalid license key for %s.', '%s is the Plugin Name', 'learndash-gradebook' ),
					'no_activations_left' => __( 'Your license key has reached its activation limit.', 'learndash-gradebook' ),
					'no_connection'       => _x( '%s cannot communicate with %s for License Key Validation. Please check your server configuration settings.', '%s is the Plugin Name followed by the Store URL', 'learndash-gradebook' ),
					'default'             => __( 'An error occurred, please try again.', 'learndash-gradebook' ),
				),
				'beta_checkbox'          => array(
					'label'            => __( 'Enable Beta Releases', 'learndash-gradebook' ),
					'disclaimer'       => __( 'Beta Releases should not be considered as Stable. Enabling this on your Production Site is done at your own risk.', 'learndash-gradebook' ),
					'enabled_message'  => _x( 'Beta Releases for %s enabled.', '%s is the Plugin Name', 'learndash-gradebook' ),
					'disabled_message' => _x( 'Beta Releases for %s disabled.', '%s is the Plugin Name', 'learndash-gradebook' ),
				),
			) );
		}

		/**
		 * Registers all plugin assets.
		 *
		 * @since 1.0.0
		 * @access private
		 */
		function register_assets() {

			/**
			 * Report Card frontend style.
			 *
			 * @since 1.0.0
			 */
			wp_register_style(
				'ld-gb-report-card',
				LEARNDASH_GRADEBOOK_URI . '/assets/dist/css/ld-gb-report-card.min.css',
				array(),
				defined( 'WP_DEBUG' ) && WP_DEBUG ? time() : LEARNDASH_GRADEBOOK_VERSION
			);

			/**
			 * Global admin style.
			 *
			 * @since 1.0.0
			 */
			wp_register_style(
				'ld-gb-admin',
				LEARNDASH_GRADEBOOK_URI . '/assets/dist/css/ld-gb-admin.min.css',
				array(),
				defined( 'WP_DEBUG' ) && WP_DEBUG ? time() : LEARNDASH_GRADEBOOK_VERSION
			);

			/**
			 * Global admin script.
			 *
			 * @since 1.0.0
			 */
			wp_register_script(
				'ld-gb-admin',
				LEARNDASH_GRADEBOOK_URI . '/assets/dist/js/ld-gb-admin.min.js',
				array( 'jquery' ),
				defined( 'WP_DEBUG' ) && WP_DEBUG ? time() : LEARNDASH_GRADEBOOK_VERSION,
				true
			);
		}

		/**
		 * Prevents an issue with Gravity Forms where if RBM FH loads, Select2 is somehow broken despite us renaming it for our own use
		 * Since we're having to unhook RBM FH's scripts, we cannot check for only LearnDash Gradebook's pages since they could be using another one of our plugins that includes RBM FH and that would break those plugins.
		 * Instead, we'll just explicitly check for Gravity Forms' settings page
		 * 
		 * @access	public
		 * @since	1.4.7
		 * @return  void
		 */
		public function maybe_enqueue_assets() {

			global $current_screen;

			if ( strpos( $current_screen->base, 'toplevel_page_gf' ) !== false ) {

				learndash_gradebook_remove_class_action( 'admin_enqueue_scripts', 'RBM_FieldHelpers', 'enqueue_scripts' );

			}
			else {

				add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_assets' ) );

			}

		}

		/**
		 * Enqueues all global plugin admin assets.
		 *
		 * @since 1.0.0
		 * @access private
		 */
		function admin_enqueue_assets() {

			$current_screen = get_current_screen();

			// Data
			$data = apply_filters( 'ld_gb_admin_script_data', array(
				'current_user_id' => get_current_user_id(),
				'l10n'            => array(
					'no_users'          => __( 'No Users', 'learndash-gradebook' ),
					'component_no_name' => __( '- No Name -', 'learndash-gradebook' ),
					'select_a_user'     => __( 'Select a User', 'learndash-gradebook' ),
				),
			) );

			wp_localize_script( 'ld-gb-admin', 'LD_GB_Admin', $data );

			// Global admin style
			wp_enqueue_style( 'ld-gb-admin' );

			// Global admin script
			wp_enqueue_script( 'ld-gb-admin' );
		}

		/**
		 * Manually setup roles when called.
		 *
		 * @since 1.3.4
		 * @access private
		 */
		function setup_roles() {

			LD_GB_Install::setup_capabilities();
		}

		/**
		 * Add support for Assignments in LearnDash_Custom_Label::get_label()
		 * This only operates as a fallback, so if support is added in for Assignments in the future this will not run
		 *
		 * @param   string  $label  Label
		 * @param   string  $key    Key
		 *
		 * @access	public
		 * @since	1.6.0
		 * @return  string          Label
		 */
		public function learndash_get_label( $label, $key ) {

			if ( $label !== '' ) return $label;

			if ( $key == 'assignment' ) {
				return __( 'Assignment', 'learndash' );
			}

			if ( $key == 'assignments' ) {
				return __( 'Assignments', 'learndash' );
			}

			return $label;

		}

	}

	// Helper functions
	require_once LEARNDASH_GRADEBOOK_DIR . 'core/ld-gb-functions.php';

	// Load the bootstrapper
	require_once LEARNDASH_GRADEBOOK_DIR . 'learndash-gradebook-bootstrapper.php';
	new LearnDash_Gradebook_Bootstrapper();

	// Install the plugin
	require_once LEARNDASH_GRADEBOOK_DIR . 'core/class-ld-gb-install.php';
	register_activation_hook( __FILE__, array( 'LD_GB_Install', 'install' ) );

	/**
	 * Gets the main class object.
	 *
	 * Used to instantiate the plugin class for the first time and then used subsequent times to return the existing object.
	 *
	 * @since 1.0.0
	 *
	 * @return LearnDash_Gradebook
	 */
	function LearnDash_Gradebook() {

		return LearnDash_Gradebook::instance();
	}
}