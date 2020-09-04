<?php
/**
 * Adds the Gradebook admin page.
 *
 * @since 1.0.0
 *
 * @package LearnDash_Gradebook
 * @subpackage LearnDash_Gradebook/admin
 */

defined( 'ABSPATH' ) || die();

/**
 * Class LD_GB_AdminPage_Gradebook
 *
 * Adds the Gradebook admin page.
 *
 * @since 1.0.0
 *
 * @package LearnDash_Gradebook
 * @subpackage LearnDash_Gradebook/admin
 */
class LD_GB_AdminPage_Gradebook {

	/**
	 * LD_GB_AdminPage_Gradebook constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		add_action( 'admin_init', array( $this, 'page_actions' ) );
		add_filter( 'ld_gb_admin_script_data', array( $this, 'translations' ) );
		add_filter( 'ld_gb_admin_script_data', array( $this, 'page_data' ) );
		add_filter( 'ld_gb_admin_page_learndash-gradebook_sections', array( $this, 'page_sections' ) );
	}

	/**
	 * Loads on the Gradebook page only.
	 *
	 * @since 1.2.0
	 * @access private
	 */
	function page_actions() {

		if ( ! isset( $_GET['page'] ) || $_GET['page'] !== 'learndash-gradebook' ) {

			return;
		}

		add_filter( 'rbm_fieldhelpers_load_select2', '__return_true' );
		
		// Fix incompatibility with Download Manager Pro
		learndash_gradebook_remove_class_action( 'admin_head', 'WPDM\admin\WordPressDownloadManagerAdmin', 'adminHead' );
		
	}

	/**
	 * Provides translations for localization.
	 *
	 * @since 1.1.0
	 * @access private
	 *
	 * @param array $data Data to be localized.
	 *
	 * @return array
	 */
	function translations( $data ) {

		$data['l10n']['cannot_show_tip'] = __( 'Error in loading information.', 'learndash-gradebook' );

		return $data;
	}

	/**
	 * Adds some data from the page.
	 *
	 * @since 1.1.0
	 * @access private
	 *
	 * @param array $data Data to localize.
	 *
	 * @return array
	 */
	function page_data( $data ) {

		$current_screen = get_current_screen();

		if ( ! $current_screen || $current_screen->id != 'admin_page_learndash-gradebook' ) {

			return $data;
		}

		$data['group_id'] = self::get_active_group();

		return $data;
	}

	/**
	 * This page's sections.
	 *
	 * @since 1.1.0
	 * @access private
	 *
	 * @return array
	 */
	function page_sections() {

		return array(
			array(
				'id'       => 'main',
				'label'    => __( 'Gradebook', 'learndash-gradebook' ),
				'callback' => array( __CLASS__, 'gradebook_page' ),
			),
		);
	}

	/**
	 * Gets the active group ID for the gradebook, if one at all.
	 *
	 * @since 1.1.0
	 *
	 * @return bool|int Group ID or false if none.
	 */
	public static function get_active_group() {

		$active_group_ID = false;

		if ( $group_IDs = learndash_get_administrators_group_ids( get_current_user_id() ) ) {

			if ( isset( $_GET['ld_group'] ) && (int) $_GET['ld_group'] > 0 ) {

				$active_group_ID = $_GET['ld_group'];

			} elseif ( ! learndash_is_admin_user() ) {

				$active_group_ID = $group_IDs[0];
			}
		}

		return $active_group_ID;
	}

	/**
	 * Gets the active Gradebook.
	 *
	 * @since 1.2.0
	 *
	 * @return bool|int Gradebook ID or false if none.
	 */
	public static function get_active_gradebook() {

		$active_gradebook_ID = false;

		if ( isset( $_GET['gradebook'] ) ) {

			$active_gradebook_ID = $_GET['gradebook'];

		} else {

			// The same filter is applied here as is on the Select dropdown to ensure that the default Gradebook is one that the User will match what is shown in the dropdown
			$gradebooks = get_posts( apply_filters( 'ld_gb_adminpage_gradebook_select_query_args', array(
				'post_type'   => 'gradebook',
				'numberposts' => 1,
			) ) );

			if ( $gradebooks ) {

				$active_gradebook_ID = $gradebooks[0]->ID;
			}
		}

		return $active_gradebook_ID;
	}

	/**
	 * The admin page output.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	static function gradebook_page() {
		
		// Fix specific to the Instructor Roles plugin by WisdmLabs
		remove_filter( 'pre_get_posts', 'wdm_set_author' );

		$active_gradebook = self::get_active_gradebook();

		if ( $active_gradebook === false ) {

			include LEARNDASH_GRADEBOOK_DIR . 'admin/views/html-no-gradebooks.php';

			return;
		}

		if ( isset( $_POST['ld-gb-toggle-hidden-users'] ) ) {

			if ( get_user_meta( get_current_user_id(), 'ld_gb_gradebook_show_inactive_users', true ) != 'yes' ) {

				update_user_meta( get_current_user_id(), 'ld_gb_gradebook_show_inactive_users', 'yes' );

			} else {

				delete_user_meta( get_current_user_id(), 'ld_gb_gradebook_show_inactive_users' );
			}
		}

		$hide_rows = get_user_meta( get_current_user_id(), 'ld_gb_gradebook_show_inactive_users', true ) == 'yes';

		$active_group_ID = self::get_active_group();
		$group_IDs       = learndash_get_administrators_group_ids( get_current_user_id() );

		$gradebooks = get_posts( apply_filters( 'ld_gb_adminpage_gradebook_select_query_args', array(
			'post_type'   => 'gradebook',
			'numberposts' => - 1,
		) ) );

		$gradebook_options = wp_list_pluck( $gradebooks, 'post_title', 'ID' );

		$gradebook = new LD_GB_GradebookListTable( $active_gradebook, $active_group_ID );

		$gradebook->prepare_items();

		include LEARNDASH_GRADEBOOK_DIR . 'admin/views/html-gradebook.php';
		
		add_filter( 'pre_get_posts', 'wdm_set_author' );
		
	}
}