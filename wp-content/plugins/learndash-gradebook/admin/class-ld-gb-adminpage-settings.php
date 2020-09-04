<?php
/**
 * Adds the Settings page.
 *
 * @since 1.0.0
 *
 * @package LearnDash_Gradebook
 * @subpackage LearnDash_Gradebook/admin
 */

defined( 'ABSPATH' ) || die();

/**
 * Class LD_GB_AdminPage_Settings
 *
 * Adds the Settings page.
 *
 * @since 1.0.0
 *
 * @package LearnDash_Gradebook
 * @subpackage LearnDash_Gradebook/admin
 */
class LD_GB_AdminPage_Settings {

	/**
	 * LD_GB_AdminPage_Settings constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_init', array( $this, 'page_actions' ) );
		add_filter( 'ld_gb_admin_page_learndash-gradebook-settings_sections', array( $this, 'page_sections' ) );
		add_action( 'admin_notices', array( $this, 'notify_invalid_scales' ) );

		if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'update' ) {

			add_action( 'admin_init', array( $this, 'save_custom_options' ) );
		}
		
		// Fix specific to the Instructor Roles plugin by WisdmLabs
		add_filter( 'wdmir_set_post_types', array( $this, 'fix_granting_edit_gradebooks_to_instructors' ) );
		
	}

	/**
	 * Registers the plugin's settings.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	function register_settings() {

		register_setting( 'learndash_gradebook-general', 'ld_gb_weight_type' );
		register_setting( 'learndash_gradebook-general', 'ld_gb_quiz_score_type' );
		register_setting( 'learndash_gradebook-general', 'ld_gb_grade_display_mode' );
		register_setting( 'learndash_gradebook-general', 'ld_gb_grade_precision' );
		register_setting( 'learndash_gradebook-general', 'ld_gb_grade_round_mode' );
		register_setting( 'learndash_gradebook-general', 'ld_gb_default_assignment_type' );
		register_setting( 'learndash_gradebook-general', 'ld_gb_default_quiz_type' );
		register_setting( 'learndash_gradebook-general', 'ld_gb_assignment_grade_lesson_name' );
		register_setting( 'learndash_gradebook-general', 'ld_gb_disable_manual_grades' );
		register_setting( 'learndash_gradebook-general', 'ld_gb_disable_component_override' );
		register_setting( 'learndash_gradebook-general', 'ld_gb_gradebook_safe_mode' );
		register_setting( 'learndash_gradebook-roles', 'ld_gb_quickstart_roles' );
		register_setting( 'learndash_gradebook-roles', 'ld_gb_gradebook_roles' );
		register_setting( 'learndash_gradebook-styles', 'ld_gb_letter_grade_scale' );
		register_setting( 'learndash_gradebook-styles', 'ld_gb_grade_color_scale' );

		// "Options not found" page will show up if there are no settings registered, even though nothing is actually
		// saved on the Licensing/Support page
		register_setting( 'learndash_gradebook-licensing', 'ld_gb_dummy' );

		add_settings_section(
			'main',
			null,
			null,
			'learndash_gradebook-general'
		);

		add_settings_section(
			'main',
			null,
			null,
			'learndash_gradebook-roles'
		);

		add_settings_section(
			'main',
			null,
			null,
			'learndash_gradebook-styles'
		);

		add_settings_section(
			'main',
			null,
			null,
			'learndash_gradebook-licensing'
		);

		$this->add_settings_fields();
	}

	/**
	 * Adds all settings fields.
	 *
	 * @since 1.2.0
	 * @access private
	 */
	private function add_settings_fields() {

		$settings = array();

		// General
		$quickstart_roles = ld_gb_get_quickstart_roles();

		ob_start();
		?>
        <p>
			<?php if ( ld_gb_current_user_match_roles( $quickstart_roles ) ) : ?>
                <a href="<?php echo admin_url( 'index.php?ld_gb_restart_quickstart' ); ?>" class="button">
					<?php _e( 'Click here if you would like to restart the Quickstart guide.', 'learndash-gradebook' ); ?>
                </a>
			<?php endif; ?>
        </p>
        <p>
            <code><?php echo admin_url( 'index.php?ld_gb_restart_quickstart' ); ?></code><br/>
            <span class="description">
                <?php _e( 'Copy this link and give it to any users (who have access) that you would like to have restart the Quickstart guide.', 'learndash-gradebook' ); ?>
            </span>
        </p>
		<?php
		$restart_quickstart_html = ob_get_clean();

		$settings['learndash_gradebook-general'] = array(

			array(
				'id'       => 'quiz_score_type',
				'label'    => LearnDash_Custom_Label::get_label( 'quizzes' ) .
							  /* translators: First %s is Quiz and second is Quizzes */
				              ld_gb_get_field_tip( sprintf( __( 'How the %s scores will be determined for students who have re-taken any %s. Either the best of all the scores or the most recently taken score.', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'quiz' ), LearnDash_Custom_Label::get_label( 'quizzes' ) ) ),
				'callback' => 'ld_gb_do_field_radio',
				'section'  => 'main',
				'args'     => array(
					'default' => 'best',
					'options' => array(
						'best'   => __( 'Best', 'learndash-gradebook' ),
						'recent' => __( 'Most Recent', 'learndash-gradebook' ),
					),
				)
			),

			array(
				'id'       => 'grade_display_mode',
				'label'    => __( 'Grade Display Mode', 'learndash-gradebook' ),
				'callback' => 'ld_gb_do_field_radio',
				'section'  => 'main',
				'args'     => array(
					'default' => 'letter',
					'options' => array(
						'letter'     => __( 'Letter', 'learndash-gradebook' ),
						'percentage' => __( 'Percentage', 'learndash-gradebook' ),
					),
				)
			),

			array(
				'id'       => 'grade_precision',
				'label'    => __( 'Grade Rounding Precision', 'learndash-gradebook' ) .
				              ld_gb_get_field_tip( __( 'The number of decimal places a grade will round to.', 'learndash-gradebook' ) ),
				'callback' => 'ld_gb_do_field_number',
				'section'  => 'main',
				'args'     => array(
					'min' => 0,
					'max' => 10,
				)
			),

			array(
				'id'       => 'grade_round_mode',
				'label'    => __( 'Grade Rounding Mode', 'learndash-gradebook' ),
				'callback' => 'ld_gb_do_field_radio',
				'section'  => 'main',
				'args'     => array(
					'default' => 'ceil',
					'options' => array(
						'ceil'  => __( 'Round Up', 'learndash-gradebook' ),
						'floor' => __( 'Round Down', 'learndash-gradebook' ),
						'round' => __( 'Closest', 'learndash-gradebook' ),
					),
				)
			),

			array(
				'id'       => 'assignment_grade_lesson_name',
				/* translators: First %s is Topic, second %s is Lesson, and third %s is Assignments */
				'label'    => sprintf( __( 'Use the containing %s/%s name for %s in Gradebooks', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'topic' ), LearnDash_Custom_Label::get_label( 'lesson' ), LearnDash_Custom_Label::get_label( 'assignments' ) ) .
							  ld_gb_get_field_tip( __( 'This will not apply when editing a Gradebook.', 'learndash-gradebook' ) ),
				'callback' => 'ld_gb_do_field_toggle',
				'section'  => 'main',
				'args'     => array(
					'checked_value' => 'yes',
				),
			),

			array(
				'id'       => 'disable_manual_grades',
				'label'    => __( 'Disable Manual Grades', 'learndash-gradebook' ) .
				              ld_gb_get_field_tip( __( 'If you do not want to allow Manual Grades to be entered, turn this option on.', 'learndash-gradebook' ) ),
				'callback' => 'ld_gb_do_field_toggle',
				'section'  => 'main',
				'args'     => array(
					'checked_value' => 'yes',
				),
			),

			array(
				'id'       => 'disable_component_override',
				'label'    => __( 'Disable Component Grade Override', 'learndash-gradebook' ) .
				              ld_gb_get_field_tip( __( 'If you do not want to allow overriding the Grade of a Component, turn this option on.', 'learndash-gradebook' ) ),
				'callback' => 'ld_gb_do_field_toggle',
				'section'  => 'main',
				'args'     => array(
					'checked_value' => 'yes',
				),
			),

			array(
				'id'       => 'gradebook_safe_mode',
				'label'    => __( 'Gradebook Safe Mode', 'learndash-gradebook' ) .
				              ld_gb_get_field_tip( __( 'If you are having issues loading the Gradebook, try enabling Safe Mode.', 'learndash-gradebook' ) ),
				'callback' => 'ld_gb_do_field_toggle',
				'section'  => 'main',
				'args'     => array(
					'checked_value' => 'yes',
				),
			),

			array(
				'id'       => 'gradebook_restart_quickstart',
				'label'    => __( 'Restart Quickstart Guide', 'learndash-gradebook' ),
				'callback' => 'ld_gb_do_field_html',
				'section'  => 'main',
				'args'     => array(
					'html' => $restart_quickstart_html,
				),
			),
		);

		// Roles
		$quickstart_roles = ld_gb_get_quickstart_roles();

		$roles                = get_editable_roles();
		$all_roles            = array();
		$all_roles_no_admin   = array();
		$gradebook_roles      = array();
		$edit_gradebook_roles = array();
		$ld_capable_roles     = array();

		foreach ( $roles as $role_ID => $role ) {

			$all_roles[ $role_ID ] = $role['name'];

			if ( isset( $role['capabilities']['view_gradebook'] ) ) {

				$gradebook_roles[] = $role_ID;
			}
			
			if ( isset( $role['capabilities']['edit_gradebooks'] ) ) {

				$edit_gradebook_roles[] = $role_ID;
			}

			// Edit Courses is used as the user must be able to view the proper pages during the Quickstart Guide
			if ( isset( $role['capabilities']['edit_courses'] ) ) {

				// These have to stay hard-coded as we're working around a LearnDash Core Capability
				if ( in_array( $role_ID, array( 'administrator', 'group_leader' ) ) ) {

					$ld_capable_roles[ $role_ID ] = sprintf( '%s (cannot disable)',
						$role['name']
					);

					continue;
				}

				$ld_capable_roles[ $role_ID ] = $role['name'];
			}
		}
		
		// Holds an Array with the Role as the Key and the default granted Caps as the Values
		$default_ld_gb_caps = ld_gb_get_capabilities();
		
		$roles_with_view_gradebook = array();
		$roles_with_edit_gradebooks = array();
		
		// Determine which Roles should be excluded from being shown on the Settings Screen
		foreach ( $default_ld_gb_caps as $role => $caps ) {
			
			if ( in_array( 'view_gradebook', $caps ) ) {
				$roles_with_view_gradebook[] = $role;
			}
			
			if ( in_array( 'edit_gradebooks', $caps ) ) {
				$roles_with_edit_gradebooks[] = $role;
			}
			
		}

		$ld_non_capable_roles = array_diff_key( $all_roles, array_flip( $roles_with_view_gradebook ) );
		$ld_non_capable_roles_edit = array_diff_key( $all_roles, array_flip( $roles_with_edit_gradebooks ) );

		$settings['learndash_gradebook-roles'] = array(

			array(
				'id'       => 'gradebook_roles',
				'label'    => __( 'View the Gradebook', 'learndash-gradebook' ) .
				              ld_gb_get_field_tip( __( 'Allow extra roles to view the Gradebook. Administrators and Group Leaders are always able to view the Gradebook.', 'learndash-gradebook' ) ),
				'callback' => 'ld_gb_do_field_checkbox',
				'section'  => 'main',
				'args'     => array(
					'options'  => $ld_non_capable_roles,
					'value'    => $gradebook_roles,
					'multiple' => true,
				),
			),
			
			array(
				'id'       => 'edit_gradebook_roles',
				'label'    => __( 'Create and Edit their own Gradebooks', 'learndash-gradebook' ) .
				              ld_gb_get_field_tip( __( 'Allow extra roles to edit and create their own Gradebooks. Administrators are able to edit any Gradebook.', 'learndash-gradebook' ) ),
				'callback' => 'ld_gb_do_field_checkbox',
				'section'  => 'main',
				'args'     => array(
					'options'  => $ld_non_capable_roles_edit,
					'value'    => $edit_gradebook_roles,
					'multiple' => true,
				),
			),

			array(
				'id'       => 'quickstart_roles',
				'label'    => __( 'Quickstart Guide Visibility', 'learndash-gradebook' ) .
				              ld_gb_get_field_tip( __( 'Who can see the Quickstart Guide. NOTE: Some roles are not listed because they do not have enough capability to view LearnDash pages.', 'learndash-gradebook' ) ),
				'callback' => 'ld_gb_do_field_checkbox',
				'section'  => 'main',
				'args'     => array(
					'options'  => $ld_capable_roles,
					'value'    => $quickstart_roles,
					'multiple' => true,
				),
			),
		);

		// Styles
		$_letter_grade_scale = get_option( 'ld_gb_letter_grade_scale', ld_gb_get_default_letter_grade_scale() );

		// Ensure proper format
		if ( $_letter_grade_scale ) {

			$letter_grade_scale = array();

			foreach ( $_letter_grade_scale as $grade => $letter ) {

				$letter_grade_scale[] = array(
					'grade'  => $grade,
					'letter' => $letter,
				);
			}

		} else {

			$letter_grade_scale = $_letter_grade_scale;
		}

		$_grade_color_scale = get_option( 'ld_gb_grade_color_scale', ld_gb_get_default_grade_color_scale() );

		// Ensure proper format
		if ( $_grade_color_scale ) {

			$grade_color_scale = array();

			foreach ( $_grade_color_scale as $grade => $color ) {

				$grade_color_scale[] = array(
					'grade' => $grade,
					'color' => $color,
				);
			}

		} else {

			$grade_color_scale = $_grade_color_scale;
		}

		$settings['learndash_gradebook-styles'] = array(

			array(
				'id'       => 'letter_grade_scale',
				'label'    => __( 'Letter Grade Scale', 'learndash-gradebook' ) .
				              ld_gb_get_field_tip( __( 'On save, this will automatically be sorted by grade.', 'learndash-gradebook' ) ),
				'callback' => 'ld_gb_do_field_repeater',
				'section'  => 'main',
				'args'     => array(
					'value'    => $letter_grade_scale,
					'sortable' => false,
					'fields'   => array(
						'grade'  => array(
							'type' => 'number',
							'args' => array(
								'min'     => 0,
								'postfix' => '%',
							),
						),
						'letter' => array(
							'type' => 'text',
						),
					),
				),
			),

			array(
				'id'       => 'grade_color_scale',
				'label'    => __( 'Grade Color Scale', 'learndash-gradebook' ) .
				              ld_gb_get_field_tip( __( 'On save, this will automatically be sorted by grade.', 'learndash-gradebook' ) ),
				'callback' => 'ld_gb_do_field_repeater',
				'section'  => 'main',
				'args'     => array(
					'value'    => $grade_color_scale,
					'sortable' => false,
					'fields'   => array(
						'grade' => array(
							'type' => 'number',
							'args' => array(
								'min'     => 0,
								'postfix' => '%',
							),
						),
						'color' => array(
							'type' => 'colorpicker',
						),
					),
				),
			),
		);

		$settings['learndash_gradebook-licensing'] = array(

			array(
				'id'       => 'licensing',
				'label'    => __( 'Licensing', 'learndash-gradebook' ),
				'callback' => array( LearnDash_Gradebook()->support, 'licensing_fields' ),
				'section'  => 'main',
				'args'     => array(),
			),
			array(
				'id'       => 'beta',
				'label'    => '',
				'callback' => array( LearnDash_Gradebook()->support, 'beta_checkbox' ),
				'section'  => 'main',
				'args'     => array(),
			),
		);

		/**
		 * All plugin settings fields displayed on the setting page.
		 *
		 * @since 1.2.0
		 */
		$settings = apply_filters( 'ld_gb_settings_fields', $settings );

		foreach ( $settings as $page => $page_settings ) {

			foreach ( $page_settings as $setting ) {

				add_settings_field(
					$setting['id'],
					$setting['label'],
					$setting['callback'],
					$page,
					$setting['section'],
					array_merge(
						array(
							'option_field' => true,
							'name'         => $setting['id'],
						),
						$setting['args']
					)
				);
			}
		}
	}

	/**
	 * Loads on the Gradebook Settings page only.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	function page_actions() {

		if ( ! isset( $_GET['page'] ) || $_GET['page'] != 'learndash-gradebook-settings' ) {

			return;
		}

		add_filter( 'rbm_fieldhelpers_load_select2', '__return_true' );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'settings_page_scripts' ) );
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

		if ( LearnDash_Gradebook()->support->get_license_status() !== 'valid' ) {

			$licensing_label = sprintf( __( 'Licensing %s', 'learndash-gradebook' ),
				'<span class="ld-gb-licensing-menu-nag dashicons dashicons-warning"></span>'
			);

			add_settings_error(
				'ld-gb-licensing',
				'',
				__( 'Please activate your license for ongoing updates and support.', 'learndash-gradebook' ),
				'ld-gb-notice not-dismissable error'
			);

		} else {

			$licensing_label = __( 'Licensing', 'learndash-gradebook' );
		}

		$sections = array(
			array(
				'id'        => 'general',
				'tab_label' => __( 'General', 'learndash-gradebook' ),
				'label'     => __( 'General Settings', 'learndash-gradebook' ),
				'callback'  => array( __CLASS__, 'section_output' ),
			),
			array(
				'id'       => 'roles',
				'label'    => __( 'Roles', 'learndash-gradebook' ),
				'callback' => array( __CLASS__, 'section_output' ),
			),
			array(
				'id'       => 'styles',
				'label'    => __( 'Styles', 'learndash-gradebook' ),
				'callback' => array( __CLASS__, 'section_output' ),
			),
			array(
				'id'       => 'licensing',
				'label'    => $licensing_label,
				'callback' => array( __CLASS__, 'section_output' ),
				'args'     => array(
					'display_submit' => false,
				),
			),
		);

		/**
		 * Sections for the settings page.
		 *
		 * @since 1.2.0
		 */
		$sections = apply_filters( 'ld_gb_settings_page_sections', $sections );

		return $sections;
	}

	/**
	 * Loads scripts on this page.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	static function settings_page_scripts() {

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );

		LearnDash_Gradebook()->support->enqueue_all_scripts();
	}

	/**
	 * Saves any custom options on form submit.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	function save_custom_options() {

		if ( isset( $_POST['option_page'] ) && $_POST['option_page'] == 'learndash_gradebook-styles' ) {

			check_admin_referer( 'learndash_gradebook-styles-options' );

			$this->save_letter_grade_scale();
			$this->save_grade_scale_colors();

			add_filter( 'whitelist_options', array( __CLASS__, 'remove_style_settings' ), 999 );
		}

		if ( isset( $_POST['option_page'] ) && $_POST['option_page'] == 'learndash_gradebook-roles' ) {

			check_admin_referer( 'learndash_gradebook-roles-options' );

			$this->save_gradebook_roles();
		}
	}

	/**
	 * Removes options for WP to save so that I can save them with custom methods.
	 *
	 * @since 1.1.0
	 * @access private
	 *
	 * @param array $options Options to be saved.
	 *
	 * @return array
	 */
	static function remove_style_settings( $options ) {

		if ( ( $key = array_search( 'ld_gb_letter_grade_scale', $options['learndash_gradebook-styles'] ) ) !== false ) {

			unset( $options['learndash_gradebook-styles'][ $key ] );
		}

		if ( ( $key = array_search( 'ld_gb_grade_color_scale', $options['learndash_gradebook-styles'] ) ) !== false ) {

			unset( $options['learndash_gradebook-styles'][ $key ] );
		}

		return $options;
	}

	/**
	 * Saves the letter grade scale.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	function save_letter_grade_scale() {

		if ( ! isset( $_POST['ld_gb_letter_grade_scale'] ) ) {

			delete_option( 'ld_gb_letter_grade_scale' );

			return;
		}

		// Flatten array
		$grades = array();
		foreach ( $_POST['ld_gb_letter_grade_scale'] as $grade ) {

			$grades[ (int) $grade['grade'] ] = $grade['letter'];
		}

		// Sort
		krsort( $grades );

		update_option( 'ld_gb_letter_grade_scale', $grades );
	}

	/**
	 * Saves the grade scale styles.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	function save_grade_scale_colors() {

		if ( ! isset( $_POST['ld_gb_grade_color_scale'] ) ) {

			delete_option( 'ld_gb_grade_color_scale' );

			return;
		}

		// Flatten array
		$grades = array();
		foreach ( $_POST['ld_gb_grade_color_scale'] as $grade ) {

			$grades[ (int) $grade['grade'] ] = $grade['color'];
		}

		// Sort
		krsort( $grades );

		update_option( 'ld_gb_grade_color_scale', $grades );
	}

	/**
	 * Updates roles/capabilities for viewing the Gradebook.
	 *
	 * @since 1.1.0
	 * @access private
	 */
	function save_gradebook_roles() {

		$roles           = get_editable_roles();
		$gradebook_roles = isset( $_POST['ld_gb_gradebook_roles'] ) ? $_POST['ld_gb_gradebook_roles'] : array();
		$edit_gradebook_roles = isset( $_POST['ld_gb_edit_gradebook_roles'] ) ? $_POST['ld_gb_edit_gradebook_roles'] : array();
		
		// Holds the Caps necessary to Edit Gradebooks
		$edit_gradebook_caps = $this->get_edit_gradebook_capabilities();
		
		// Holds any default LD GB Caps, important for preserving the Group Leader's normal abilities after revoking Edit Gradebooks
		$default_ld_gb_caps = ld_gb_get_capabilities();
		
		// This is used in the loop to effectively whitelist any special Caps that a Role may have by default
		$temp_edit_gradebook_caps = array();

		foreach ( $roles as $role_ID => $role ) {

			if ( $role_ID == 'administrator' ) {

				continue;
			}

			$role = get_role( $role_ID );
			
			if ( ! isset( $default_ld_gb_caps[ $role_ID ] ) || 
			   ( isset( $default_ld_gb_caps[ $role_ID ] ) && ! in_array( 'view_gradebook', $default_ld_gb_caps[ $role_ID ] ) ) ) { // Ensure Group Leader's ability to View Gradebooks doesn't get wiped

				if ( in_array( $role_ID, $gradebook_roles ) ) {

					if ( ! $role->has_cap( 'view_gradebook' ) ) {

						$role->add_cap( 'view_gradebook' );
					}

				} else {

					if ( $role->has_cap( 'view_gradebook' ) ) {

						$role->remove_cap( 'view_gradebook' );
					}
				}
				
			}
			
			if ( array_key_exists( $role_ID, $default_ld_gb_caps ) ) {
				
				// Only check against caps that they were not granted by default
				$temp_edit_gradebook_caps = array_diff( $edit_gradebook_caps, $default_ld_gb_caps[ $role_ID ] );
				
			}
			else {
				
				// Check against all Edit Gradebook caps
				$temp_edit_gradebook_caps = $edit_gradebook_caps;
				
			}
			
			if ( in_array( $role_ID, $edit_gradebook_roles ) ) {
				
				foreach ( $temp_edit_gradebook_caps as $cap ) {

					if ( ! $role->has_cap( $cap ) ) {

						$role->add_cap( $cap );
					}
					
				}

			} else {

				foreach ( $temp_edit_gradebook_caps as $cap ) {

					if ( $role->has_cap( $cap ) ) {

						$role->remove_cap( $cap );
					}
					
				}

			}
			
		}
	}
	
	/**
	 * Grab the Edit Gradebook Capabilites
	 * By default, this includes everything but the ability to Edit/Delete the Gradebooks of Other Users
	 * 
	 * @access		private
	 * @since		1.4.0
	 * @return		array Edit Gradebook Capabilities
	 */
	private function get_edit_gradebook_capabilities() {
		
		return apply_filters( 'get_edit_gradebook_capabilities', array(
			'read_gradebook',
			'read_private_gradebooks',
			'publish_gradebooks',
			'edit_gradebook',
			'edit_gradebooks',
			'edit_private_gradebooks',
			'edit_published_gradebooks',
			'delete_gradebooks',
			'delete_private_gradebooks',
			'delete_published_gradebooks',
			'delete_gradebook',
		) );
		
	}
	
	/**
	 * Addresses the fact that the Instructor Roles plugin doesn't purely use Capabilities when checking whether or not a user can view a specific page
	 * 
	 * @param		array $learndash_post_types Array of LearnDash Post Types
	 *                                                             
	 * @access		public
	 * @since		1.4.0
	 * @return		array Array of LearnDash Post Types
	 */
	public function fix_granting_edit_gradebooks_to_instructors( $learndash_post_types ) {
		
		$learndash_post_types[] = 'gradebook';
		
		return $learndash_post_types;
		
	}

	/**
	 * Settings page section General output.
	 *
	 * @since 1.1.0
	 * @access private
	 *
	 * @param array $active_section Currently active section and its args.
	 */
	static function section_output( $active_section ) {

		$page = isset( $_GET['section'] ) ? "learndash_gradebook-{$_GET['section']}" : 'learndash_gradebook-general';

		include LEARNDASH_GRADEBOOK_DIR . 'admin/views/html-settings-page.php';
	}

	/**
	 * Notifies the user if scales are invalid.
	 *
	 * @since 1.1.0
	 * @access private
	 */
	function notify_invalid_scales() {

		global $wp_settings_errors;

		$letter_grade_scale = get_option( 'ld_gb_letter_grade_scale', ld_gb_get_default_letter_grade_scale() );
		$grade_color_scale  = get_option( 'ld_gb_grade_color_scale', ld_gb_get_default_grade_color_scale() );

		if ( ! isset( $letter_grade_scale[0] ) ) {

			$wp_settings_errors[] = array(
				'setting' => 'ld-gb-invalid-scales',
				'message' => __( 'Letter Grade Scale requires a 0% grade to exist. It has been added manually.', 'learndash-gradebook' ),
				'code'    => '',
				'type'    => 'error',
			);

			$letter_grade_scale[0] = 'F';

			update_option( 'ld_gb_letter_grade_scale', $letter_grade_scale );
		}

		if ( ! isset( $grade_color_scale[0] ) ) {

			$wp_settings_errors[] = array(
				'setting' => 'ld-gb-invalid-scales',
				'message' => __( 'Grade Color Scale requires a 0% grade to exist. It has been added manually.', 'learndash-gradebook' ),
				'code'    => '',
				'type'    => 'error',
			);

			$grade_color_scale[0] = '#f00';

			update_option( 'ld_gb_grade_color_scale', $grade_color_scale );
		}

		settings_errors( 'ld-gb-invalid-scales' );

		// Remove any we just added
		if ( $wp_settings_errors ) {
			foreach ( $wp_settings_errors as $i => $error ) {

				if ( $error['setting'] == 'ld-gb-invalid-scales' ) {
					unset( $wp_settings_errors[ $i ] );
				}
			}
		}
	}
}