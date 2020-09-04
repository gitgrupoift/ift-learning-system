<?php
/**
 * Adds the User Grades admin page.
 *
 * @since 1.2.0
 *
 * @package LearnDash_Gradebook
 * @subpackage LearnDash_Gradebook/admin
 */

defined( 'ABSPATH' ) || die();

/**
 * Class LD_GB_AdminPage_UserGrades
 *
 * Adds the Gradebook admin page.
 *
 * @since 1.2.0
 *
 * @package LearnDash_Gradebook
 * @subpackage LearnDash_Gradebook/admin
 */
class LD_GB_AdminPage_UserGrades {

	/**
	 * LD_GB_AdminPage_UserGrades constructor.
	 *
	 * @since 1.2.0
	 */
	public function __construct() {

		add_action( 'admin_init', array( $this, 'page_actions' ) );
		add_filter( 'ld_gb_admin_script_data', array( $this, 'localize_data' ) );
		add_filter( 'ld_gb_admin_page_learndash-gradebook-user-grades_sections', array( $this, 'page_sections' ) );
		add_action( 'wp_ajax_ld_gb_edit_grade', array( $this, 'edit_grade' ) );
		add_action( 'wp_ajax_ld_gb_edit_component_grade', array( $this, 'edit_component_grade' ) );
	}

	/**
	 * Loads on the Gradebook page only.
	 *
	 * @since 1.2.0
	 * @access private
	 */
	function page_actions() {

		if ( ! isset( $_GET['page'] ) || $_GET['page'] !== 'learndash-gradebook-user-grades' ) {

			return;
		}

		add_filter( 'rbm_fieldhelpers_load_select2', '__return_true' );
	}

	/**
	 * Provides data for localization
	 *
	 * @since 1.2.0
	 * @access private
	 *
	 * @param array $data Data to be localized.
	 *
	 * @return array
	 */
	function localize_data( $data ) {

		$data['l10n']['change']                         = __( 'Change', 'learndash-gradebook' );
		$data['l10n']['close']                          = __( 'Close', 'learndash-gradebook' );
		$data['l10n']['could_not_edit_grade']           = __( 'Could not edit grade.', 'learndash-gradebook' );
		$data['l10n']['manual_grade_add_error']         = __( 'Please fill out Name and Score', 'learndash-gradebook' );
		$data['l10n']['manual_grade_add_error_numbers'] = __( 'Score can only contain numbers', 'learndash-gradebook' );
		$data['l10n']['manual_grade_processing']        = __( 'Processing', 'learndash-gradebook' );
		$data['l10n']['manual_grade_add_button']        = __( 'Add Grade', 'learndash-gradebook' );
		$data['l10n']['manual_grade_confirm_delete']    = __( 'Are you sure you want to delete this grade?', 'learndash-gradebook' );
		$data['l10n']['component_grade_override']       = __( 'Override', 'learndash-gradebook' );
		$data['l10n']['component_grade_modify']         = __( 'Modify', 'learndash-gradebook' );

		$data['gradebook'] = LD_GB_AdminPage_Gradebook::get_active_gradebook();

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

		$user      = get_user_by( 'id', $_GET['user'] );
		$gradebook = get_post( $_GET['gradebook'] );

		$label = sprintf(
		/* translators: First %s is user name and second %s is Gradebook name */
			__( '%s\'s grades for %s', 'learndash-gradebook' ),
			$user->display_name,
			$gradebook->post_title
		);

		return array(
			array(
				'id'       => 'main',
				'label'    => $label,
				'callback' => array( $this, 'user_grades_page' ),
			),
		);
	}

	/**
	 * The admin page output.
	 *
	 * @since 1.2.0
	 * @access private
	 */
	function user_grades_page() {
		
		// Fix specific to the Instructor Roles plugin by WisdmLabs
		remove_filter( 'pre_get_posts', 'wdm_set_author' );

		$gradebook   = (int) $_GET['gradebook'];
		$user_grade  = new LD_GB_UserGrade( $_GET['user'], $gradebook );
		$user        = get_user_by( 'id', $_GET['user'] );
		$is_weighted = ld_gb_get_field( 'gradebook_weighting_enable', $gradebook ) === '1';

		$grade_status_options = array();

		if ( $grade_statuses = ld_gb_get_grade_statuses() ) {

			foreach ( $grade_statuses as $status_ID => $status ) {

				// We do not want to allow "Pending Approval" to be an option for users. This is specific to Assignment handling
				if ( $status_ID == 'pending' ) continue;

				$grade_status_options[ $status_ID ] = $status['label'];
			}
		}

		include LEARNDASH_GRADEBOOK_DIR . 'admin/views/html-user-grades-page.php';
		
		add_filter( 'pre_get_posts', 'wdm_set_author' );
		
	}

	/**
	 * Edits a grade.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	function edit_grade() {

		if ( ! isset( $_POST['data'] ) ) {

			wp_send_json_error( array(
				'error' => __( 'Could not edit grade. Error #1000', 'learndash-gradebook' ),
			) );
		}

		$grade = $_POST['data'];

		// Deal with slashes
		$grade = array_map( 'wp_unslash', $grade );

		switch ( $grade['type'] ) {

			case 'manual':

				if ( get_option( 'ld_gb_disable_manual_grades', false ) ) {

					$data = array(
						'type' => 'error',
						'data' => array(
							'error'         => __( 'Manual Grades have been disabled.', 'learndash-gradebook' ),
						)
					);
		
				}
				else {

					if ( $grade['delete'] == '1' ) {

						$data = learndash_gradebook_delete_manual_grade( $grade );

					} else {

						if ( $grade['new'] == '1' ) {

							$data = learndash_gradebook_update_manual_grade( $grade, false );

						} else {
							$data = learndash_gradebook_update_manual_grade( $grade );
						}
					}

				}

				break;

			case 'quiz':

				$data = $this->edit_quiz_grade( $grade );
				break;

			case 'assignment':

				$data = $this->edit_assignment_grade( $grade );
				break;

			case 'lesson':

				$data = $this->edit_lesson_grade( $grade );
				break;

			case 'topic':

				$data = $this->edit_topic_grade( $grade );
				break;

			default:

				$data = array(
					'type' => 'error',
					'data' => array(
						'error' => __( 'Could not edit grade. Error #1001', 'learndash-gradebook' ),
					),
				);
		}

		if ( $data['type'] == 'error' ) {

			wp_send_json_error( $data['data'] );

		} else {

			wp_send_json_success( $data['data'] );
		}
	}

	/**
	 * AJAX callback for editing a component grade override.
	 */
	function edit_component_grade() {

		if ( ! get_option( 'ld_gb_disable_component_override', false ) ) {
			wp_send_json_error( array(
				'error' => __( 'Overriding Component Grades has been disabled.', 'learndash-gradebook' ),
			) );
		}

		$action       = $_POST['data']['action'];
		$new_grade    = $_POST['data']['new_grade'];
		$user_ID      = $_POST['data']['user_id'];
		$component_ID = $_POST['data']['component_id'];
		$gradebook    = $_POST['data']['gradebook'];

		if ( $user_ID === null || $component_ID === null ) {

			wp_send_json_error( array(
				'error' => __( 'Could not update component grade. Error #1000', 'learndash-gradebook' ),
			) );
		}

		$component_grades = get_user_meta( $user_ID, "ld_gb_component_grades_{$gradebook}", true );
		if ( ! $component_grades ) {
			$component_grades = array();
		}

		switch ( $action ) {

			case 'save':

				$new_grade = max( $new_grade, 0 );

				$component_grades[ $component_ID ] = $new_grade;
				break;

			case 'delete':

				if ( isset( $component_grades[ $component_ID ] ) ) {

					unset( $component_grades[ $component_ID ] );
				}
				break;
		}

		if ( ! $component_grades ) {

			delete_user_meta( $user_ID, "ld_gb_component_grades_{$gradebook}" );

		} else {

			update_user_meta( $user_ID, "ld_gb_component_grades_{$gradebook}", $component_grades );
		}


		// Get final score
		$user_grade = new LD_GB_UserGrade( $user_ID, $gradebook );

		// Get component grade
		$component                = $user_grade->get_component( $component_ID );
		$component_grade['grade'] = $component['averaged_score'];
		$component_grade['score'] = LD_GB_UserGrade::get_display_grade( $component['averaged_score'], 'letter' );
		$component_grade['color'] = LD_GB_UserGrade::get_display_grade_color( $component['averaged_score'] );

		wp_send_json_success( array(
			'user_grade'      => array(
				'score' => LD_GB_UserGrade::get_display_grade( $user_grade->get_user_grade(), 'letter' ),
				'color' => LD_GB_UserGrade::get_display_grade_color( $user_grade->get_user_grade() ),
			),
			'component_grade' => $component_grade,
		) );
	}

	/**
	 * Edits a quiz grade.
	 *
	 * @since 1.0.1
	 * @access private
	 *
	 * @param array $grade
	 */
	function edit_quiz_grade( $grade ) {

		$response                    = $this->edit_post_driven_grade( $grade );
		/* translators: First %s is Quiz */
		$response['data']['success'] = sprintf( __( 'Successfully edited %s grade.', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'quiz' ) );

		return $response;
	}

	/**
	 * Edits a assignment grade.
	 *
	 * @since 1.0.1
	 * @access private
	 *
	 * @param array $grade
	 */
	function edit_assignment_grade( $grade ) {

		$response                    = $this->edit_post_driven_grade( $grade );
		/* translators: First %s is Assignment */
		$response['data']['success'] = sprintf( __( 'Successfully edited %s grade.', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'assignment' ) );

		return $response;
	}

	/**
	 * Edits a lesson grade.
	 *
	 * @since 1.2.0
	 * @access private
	 *
	 * @param array $grade
	 */
	function edit_lesson_grade( $grade ) {

		$response                    = $this->edit_post_driven_grade( $grade );
		/* translators: First %s is Lesson */
		$response['data']['success'] = sprintf( __( 'Successfully edited %s grade.', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'lesson' ) );

		return $response;
	}

	/**
	 * Edits a topic grade.
	 *
	 * @since 1.2.0
	 * @access private
	 *
	 * @param array $grade
	 */
	function edit_topic_grade( $grade ) {

		$response                    = $this->edit_post_driven_grade( $grade );
		/* translators: First %s is Topic */
		$response['data']['success'] = sprintf( __( 'Successfully edited %s grade.', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'topic' ) );

		return $response;
	}

	/**
	 * Some component types share editing functionality.
	 *
	 * @since 1.2.0
	 * @access private
	 *
	 * @param array $grade
	 *
	 * @return array
	 */
	private function edit_post_driven_grade( $grade ) {

		if ( isset( $grade['status'] ) ) {

			if ( $grade['status'] ) {

				update_user_meta( $grade['user_id'], "ld_gb_grade_status_{$grade['gradebook']}_{$grade['post_id']}", $grade['status'] );

			} else {

				delete_user_meta( $grade['user_id'], "ld_gb_grade_status_{$grade['gradebook']}_{$grade['post_id']}" );
			}
		}

		// Get final score
		$user_grade = new LD_GB_UserGrade( $grade['user_id'], $grade['gradebook'] );

		// Get compnonent grade
		$component                = $user_grade->get_component( $grade['component'] );
		$component_grade['score'] = LD_GB_UserGrade::get_display_grade( $component['averaged_score'], 'letter' );
		$component_grade['color'] = LD_GB_UserGrade::get_display_grade_color( $component['averaged_score'] );

		$grade = LD_GB_UserGrade::modify_grade_by_status( $grade );

		return array(
			'status' => 'success',
			'data'   => array(
				'score_display'   => $grade['score_display'],
				'component_grade' => $component_grade,
				'user_grade'      => array(
					'score' => LD_GB_UserGrade::get_display_grade( $user_grade->get_user_grade(), 'letter' ),
					'color' => LD_GB_UserGrade::get_display_grade_color( $user_grade->get_user_grade() ),
				),
			),
		);
	}
}