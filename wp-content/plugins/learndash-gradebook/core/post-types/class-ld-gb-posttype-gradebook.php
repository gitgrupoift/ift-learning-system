<?php
/**
 * Creates the Gradebook post type.
 *
 * @since 1.2.0
 */

defined( 'ABSPATH' ) || die();

/**
 * Class LD_GB_PostType_Gradebook
 *
 * Creates the Gradebook post type.
 *
 * @since 1.2.0
 */
class LD_GB_PostType_Gradebook {

	/**
	 * LD_GB_PostType_Gradebook constructor.
	 *
	 * @since 1.2.0
	 */
	function __construct() {

		add_action( 'current_screen', array( $this, 'screen_actions' ) );
		add_filter( 'ld_gb_admin_script_data', array( $this, 'add_script_data' ) );
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_filter( 'post_updated_messages', array( $this, 'post_messages' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_filter( 'ld_gb_fieldhelpers_gradebook-settings_save_field_components', array(
			$this,
			'validate_components_save'
		), 10, 2 );

		add_action( 'wp_ajax_ld_gb_get_component_options', array( $this, 'ajax_get_component_options' ) );
		add_action( 'wp_ajax_ld_gb_get_new_component_id', array( $this, 'ajax_get_new_component_id' ) );
		
		add_filter( 'wp_dropdown_users_args', array( $this, 'wp_dropdown_users_args' ), 10, 2 );
		
	}

	/**
	 * Loads actions specific to the current screen.
	 *
	 * @since 1.2.0
	 * @access private
	 *
	 * @param WP_Screen $wp_screen
	 */
	function screen_actions( $wp_screen ) {

		switch ( $wp_screen->id ) {
			case 'gradebook':

				add_filter( 'rbm_fieldhelpers_load_select2', '__return_true' );
				add_filter( 'rbm_fieldhelpers_load_datetimepicker', '__return_true' );
				add_action( 'admin_enqueue_scripts', array( $this, 'gradebook_post_edit_scripts' ) );
				break;
		}
	}

	/**
	 * Enqueues scripts only on the Gradebook Post Edit page.
	 *
	 * @since 1.2.0
	 * @access private
	 */
	function gradebook_post_edit_scripts() {

		wp_enqueue_script( 'tiny_mce' );
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_editor();
		wp_enqueue_media();
	}

	/**
	 * Adds some data to be localized.
	 *
	 * @since 1.2.0
	 * @access private
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	function add_script_data( $data ) {

		$data['component_weights'] = ld_gb_get_field( 'component_weights' );
		$data['components']        = ld_gb_get_field( 'components' );
		$data['gradebook_id']      = get_the_ID();

		return $data;
	}

	/**
	 * Registers the post type.
	 *
	 * @since 1.2.0
	 * @access private
	 */
	function register_post_type() {

		$labels = array(
			'name'               => _x( 'Gradebooks', 'post type general name', 'learndash-gradebook' ),
			'singular_name'      => _x( 'Gradebook', 'post type singular name', 'learndash-gradebook' ),
			'menu_name'          => _x( 'Gradebooks', 'admin menu', 'learndash-gradebook' ),
			'name_admin_bar'     => _x( 'Gradebook', 'add new on admin bar', 'learndash-gradebook' ),
			'add_new'            => _x( 'Add New', 'gradebook', 'learndash-gradebook' ),
			'add_new_item'       => __( 'Add New Gradebook', 'learndash-gradebook' ),
			'new_item'           => __( 'New Gradebook', 'learndash-gradebook' ),
			'edit_item'          => __( 'Edit Gradebook', 'learndash-gradebook' ),
			'view_item'          => __( 'View Gradebook', 'learndash-gradebook' ),
			'all_items'          => __( 'All Gradebooks', 'learndash-gradebook' ),
			'search_items'       => __( 'Search Gradebooks', 'learndash-gradebook' ),
			'parent_item_colon'  => __( 'Parent Gradebooks:', 'learndash-gradebook' ),
			'not_found'          => __( 'No gradebooks found.', 'learndash-gradebook' ),
			'not_found_in_trash' => __( 'No gradebooks found in Trash.', 'learndash-gradebook' )
		);

		/**
		 * Post type labels for the Gradebook post type.
		 *
		 * @since 1.2.0
		 */
		$labels = apply_filters( 'ld_gb_posttype_gradebook_labels', $labels );

		$args = array(
			'labels'             => $labels,
			'description'        => __( 'Student Gradebook for LearnDash LMS.', 'learndash-gradebook' ),
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'capability_type'    => 'gradebook',
			'capabilities'       => array(
				'edit_post'          => 'edit_gradebook',
				'edit_posts'         => 'edit_gradebooks',
				'edit_others_posts'  => 'edit_others_gradebooks',
				'publish_posts'      => 'publish_gradebooks',
				'read_post'          => 'read_gradebook',
				'read_private_posts' => 'read_private_gradebooks',
				'delete_post'        => 'delete_gradebook',
			),
			'map_meta_cap'       => true,
			'has_archive'        => false,
			'hierarchical'       => false,
			'supports'           => array( 'title', 'author' ),
		);

		/**
		 * Post type arguments for the Gradebook post type.
		 *
		 * @since 1.2.0
		 */
		$args = apply_filters( 'ld_gb_posttype_gradebook_args', $args );

		register_post_type( 'gradebook', $args );
	}

	/**
	 * Adds Gradebook post updated messages.
	 *
	 * @since 1.2.0
	 * @access private
	 *
	 * @param array $messages Post messages.
	 *
	 * @return array
	 */
	function post_messages( $messages ) {

		global $post;

		$scheduled_date = date_i18n( __( 'M j, Y @ H:i' ), strtotime( $post->post_date ) );

		$messages['gradebook'] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => __( 'Gradebook updated.' ),
			2  => __( 'Custom field updated.' ),
			3  => __( 'Custom field deleted.' ),
			4  => __( 'Gradebook updated.' ),
			/* translators: %s: date and time of the revision */
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Gradebook restored to revision from %s.' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6  => __( 'Gradebook published.' ),
			7  => __( 'Gradebook saved.' ),
			8  => __( 'Gradebook submitted.' ),
			9  => sprintf( __( 'Gradebook scheduled for: %s.' ), '<strong>' . $scheduled_date . '</strong>' ),
			10 => __( 'Gradebook draft updated.' ),
		);

		return $messages;
	}

	/**
	 * Adds meta boxes to the post type.
	 *
	 * @since 1.2.0
	 * @access private
	 */
	function add_meta_boxes() {

		$current_screen = get_current_screen();

		add_meta_box(
			'gradebook-grading',
			__( 'Grading', 'learndash-gradebook' ),
			array( $this, 'mb_grading' ),
			'gradebook'
		);

		add_meta_box(
			'gradebook-shortcode',
			__( 'Report Card', 'learndash-gradebook' ),
			array( $this, 'mb_shortcode' ),
			'gradebook',
			'side',
			'high'
		);

		if ( $current_screen->id === 'gradebook' && $current_screen->action !== 'add' ) {

			add_meta_box(
				'gradebook-users',
				__( 'Users', 'learndash-gradebook' ),
				array( $this, 'mb_users' ),
				'gradebook',
				'side'
			);
		}

		add_meta_box(
			'gradebook-weighting',
			__( 'Weighting', 'learndash-gradebook' ),
			array( $this, 'mb_weighting' ),
			'gradebook',
			'side'
		);

		add_meta_box(
			'gradebook-settings',
			__( 'Settings', 'learndash-gradebook' ),
			array( $this, 'mb_settings' ),
			'gradebook',
			'side'
		);
	}

	/**
	 * Outputs the users metabox.
	 *
	 * @since 1.2.0
	 * @access private
	 */
	function mb_users() {

		ld_gb_do_field_select( array(
			'no_init'           => true,
			'name'              => 'user_view_grades',
			'show_empty_select' => true,
			'select2_disable'   => true,
			'input_class'       => 'widefat',
		) );

		echo '<a href="' . admin_url( 'admin.php?page=learndash-gradebook-user-grades&gradebook=' . get_the_ID() . '&return=gradebook-edit&referrer=' . urlencode( $_SERVER['REQUEST_URI'] ) ) .
		     '" class="button disabled" data-open-user-grades>' . __( 'View/Edit User Grades', 'learndash-gradebook' ) . '</a>';
	}

	/**
	 * Outputs the shortcode metabox.
	 *
	 * @since 1.2.0
	 * @access private
	 */
	function mb_shortcode() {
		?>
        <p>
			<?php _e( 'In order to show a report card for this Gradebook on your site, copy and paste the following shortcode wherever you like.', 'learndash-gradebook' ); ?>
        </p>

        <p>
            <code>
                [ld_report_card gradebook="<?php the_ID(); ?>"]
            </code>
        </p>
		<?php
	}

	/**
	 * Outputs the grading metabox.
	 *
	 * @since 1.2.0
	 * @access private
	 */
	function mb_grading( $post ) {
		
		// Fix specific to the Instructor Roles plugin by WisdmLabs
		remove_filter( 'pre_get_posts', 'wdm_set_author' );

		?>
        <div class="notice error inline ld-gb-component-error-message" style="display: none;">
            <p>
				<?php _e( 'At least one Component must exist.', 'learndash-gradebook' ); ?>
            </p>
        </div>
		<?php

		$courses = get_posts( array(
			'post_type'   => 'sfwd-courses',
			'numberposts' => - 1,
			'post_status' => 'any',
		) );
		
		$course_id = ld_gb_get_field( 'course', get_the_ID(), false );

		ld_gb_do_field_select( array(
			'name'                  => 'course',
			'group'                 => 'gradebook-settings',
			'label'                 => LearnDash_Custom_Label::get_label( 'course' ),
			/* translators: First %s is Course, second %s is Courses, third is Courses, forth is Lessons, fifth is Topics, and sixth is Courses */
			'description'           => sprintf( __( 'Select a %s to use for this Gradebook. If "All %s" is selected, all active %s will count towards the Gradebook grade. Note: %s and %s cannot be graded when "All %s" is selected.', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'course' ), LearnDash_Custom_Label::get_label( 'courses' ), LearnDash_Custom_Label::get_label( 'courses' ), LearnDash_Custom_Label::get_label( 'lessons' ), LearnDash_Custom_Label::get_label( 'topics' ), LearnDash_Custom_Label::get_label( 'courses' ) ),
			'description_placement' => 'after_label',
			/* translators: First %s is Courses */
			'options'               => array( 'all' => sprintf( __( 'All %s', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'courses' ) ) ) + wp_list_pluck( $courses, 'post_title', 'ID' ),
			'input_class'           => 'regular-text',
			'placeholder'           => __( 'Make a Selection', 'learndash-gradebook' ),
			/* translators: First %s is Courses */
			'option_none'           => sprintf( __( 'All %s', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'courses' ) ),
			'select2_options'       => array(
				'allowClear' => true,
			),
			'l10n'                  => array(
				/* translators: First %s is Courses */
				'no_options' => sprintf( __( 'All %s', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'courses' ) ),
				/* translators: First %s is Courses */
				'no_results' => sprintf( __( 'No %s Available', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'courses' ) ),
			),
			'input_atts' => array(
				'required' => true,
			),
			'default' => ( $post->post_status == 'auto-draft' && ! $course_id ) ? false : ( ! $course_id ) ? 'all' : false,
		) );

		if ( $post->post_status == 'auto-draft' && ! $course_id ) {

			// If it is a new post, we do not need to worry about a legacy fallback to 'all' for an empty Course ID
			$options = array(
				'lessons' => array(),
				'topics' => array(),
				'quizzes' => array(),
				'assignments' => array(),
				'assignment_lessons' => array(),
				'assignment_topics' => array(),
			);

		}
		else {
			$options = self::get_component_options( $course_id );
		}

		ld_gb_do_field_repeater( array(
			'name'                => 'components',
			'group'               => 'gradebook-settings',
			'label'               => __( 'Components', 'learndash-gradebook' ),
			'add_item_text'       => __( 'Add Component', 'learndacomponentssh-gradebook' ),
			'delete_item_text'    => __( 'Delete Component', 'learndash-gradebook' ),
			'confirm_delete_text' => __( 'Are you sure you want to delete this Component? This cannot be undone', 'learndash-gradebook' ),
			'fields'              => array(
				'id'                          => array(
					'type' => 'hidden',
				),
				'name'                        => array(
					'type' => 'text',
					'args' => array(
						'label'       => __( 'Name', 'learndash-gradebook' ),
						'input_class' => 'regular-text',
					),
				),
				'lessons_section_divider'     => array(
					'type' => 'html',
					'args' => array(
						/* translators: First %s is Lesson */
						'html' => '<div class="ld-gb-clearfix"></div><hr/>' . sprintf( __( '%s Grading', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'lesson' ) ),
					),
				),
				'lessons'                     => array(
					'type' => 'select',
					'args' => array(
						'label'                 => LearnDash_Custom_Label::get_label( 'lessons' ),
						/* translators: First %s is Lessons */
						'description'           => sprintf( __( '%s will be graded based on completion.', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'lessons' ) ),
						'description_placement' => 'after_label',
						'options'               => wp_list_pluck( $options['lessons'], 'text', 'value' ),
						'wrapper_class'         => 'fieldhelpers-col-2',
						'input_class'           => 'widefat ld-gb-component-items-select',
						'input_atts'            => array(
							'data-type'             => 'lessons',
							'data-disable-from-all' => 'lessons'
						),
						'show_empty_select'     => true,
						'multiple'              => true,
						/* translators: First %s is Lessons */
						'placeholder'           => sprintf( __( 'No %s', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'lessons' ) ),
						'l10n'                  => array(
							/* translators: First %s is Lessons */
							'no_options' => sprintf( __( 'No %s', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'lessons' ) ),
							/* translators: First %s is Lessons */
							'no_results' => sprintf( __( 'No %s Available', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'lessons' ) ),
						),
					),
				),
				'lessons_all'                 => array(
					'type' => 'toggle',
					'args' => array(
						/* translators: First %s is Lessons */
						'label'                 => sprintf( __( 'All %s', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'lessons' ) ),
						'wrapper_class'         => 'fieldhelpers-col-2',
						'input_atts'            => array( 'data-disable-group' => 'lessons' ),
						/* translators: First %s is Lessons and second %s is Lessons */
						'description'           => sprintf( __( 'Enabling this will cause all %s to be factored into the Component grade. This will override any %s you have selected and instead include everything.', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'lessons' ), LearnDash_Custom_Label::get_label( 'lessons' ) ),
						'description_placement' => 'after_label',
					),
				),
				'topics_section_divider'      => array(
					'type' => 'html',
					'args' => array(
						/* translators: First %s is Topic */
						'html' => '<div class="ld-gb-clearfix"></div><hr/>' . sprintf( __( '%s Grading', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'topic' ) ),
					),
				),
				'topics'                      => array(
					'type' => 'select',
					'args' => array(
						/* translators: First %s is Topics */
						'label'                 => sprintf( __( '%s', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'topics' ) ),
						/* translators: First %s is Topics */
						'description'           => sprintf( __( '%s will be graded based on completion.', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'topics' ) ),
						'description_placement' => 'after_label',
						'options'               => wp_list_pluck( $options['topics'], 'text', 'value' ),
						'wrapper_class'         => 'fieldhelpers-col-2',
						'input_class'           => 'widefat ld-gb-component-items-select',
						'input_atts'            => array(
							'data-type'             => 'topics',
							'data-disable-from-all' => 'topics'
						),
						'show_empty_select'     => true,
						'multiple'              => true,
						/* translators: First %s is Topics */
						'placeholder'           => sprintf( __( 'No %s', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'topics' ) ),
						'l10n'                  => array(
							/* translators: First %s is Topics */
							'no_options' => sprintf( __( 'No %s', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'topics' ) ),
							/* translators: First %s is Topics */
							'no_results' => sprintf( __( 'No %s Available', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'topics' ) ),
						),
					),
				),
				'topics_all'                  => array(
					'type' => 'toggle',
					'args' => array(
						/* translators: First %s is Topics */
						'label'                 => sprintf( __( 'All %s', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'topics' ) ),
						'wrapper_class'         => 'fieldhelpers-col-2',
						'input_atts'            => array( 'data-disable-group' => 'topics' ),
						/* translators: First %s is Topics and second %s is Topics */
						'description'           => sprintf( __( 'Enabling this will cause all %s to be factored into the Component grade. This will override any %s you have selected and instead include everything.', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'topics' ), LearnDash_Custom_Label::get_label( 'topics' ) ),
						'description_placement' => 'after_label',
					),
				),
				'quizzes_section_divider'     => array(
					'type' => 'html',
					'args' => array(
						/* translators: First %s is Quiz */
						'html' => '<div class="ld-gb-clearfix"></div><hr/>' . sprintf( __( '%s Grading', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'quiz' ) ),
					),
				),
				'quizzes'                     => array(
					'type' => 'select',
					'args' => array(
						'label'             => LearnDash_Custom_Label::get_label( 'quizzes' ),
						'options'           => wp_list_pluck( $options['quizzes'], 'text', 'value' ),
						'wrapper_class'     => 'fieldhelpers-col-2',
						'input_class'       => 'widefat ld-gb-component-items-select',
						'input_atts'        => array( 'data-type' => 'quizzes', 'data-disable-from-all' => 'quizzes' ),
						'show_empty_select' => true,
						'multiple'          => true,
						/* translators: First %s is Quizzes */
						'placeholder'       => sprintf( __( 'No %s', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'quizzes' ) ),
						'l10n'              => array(
							/* translators: First %s is Quizzes */
							'no_options' => sprintf( __( 'No %s', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'quizzes' ) ),
							/* translators: First %s is Quizzes */
							'no_results' => sprintf( __( 'No %s Available', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'quizzes' ) ),
						),
					),
				),
				'quizzes_all'                 => array(
					'type' => 'toggle',
					'args' => array(
						/* translators: First %s is Quizzes */
						'label'                 => sprintf( __( 'All %s', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'quizzes' ) ),
						'wrapper_class'         => 'fieldhelpers-col-2',
						'input_atts'            => array( 'data-disable-group' => 'quizzes' ),
						/* translators: First %s is Quizzes and second %s is Quizzes */
						'description'           => sprintf( __( 'Enabling this will cause all %s to be factored into the Component grade. This will override any %s you have selected and instead include everything.', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'quizzes' ), LearnDash_Custom_Label::get_label( 'quizzes' ) ),
						'description_placement' => 'after_label',
					),
				),
				'assignments_section_divider' => array(
					'type' => 'html',
					'args' => array(
						/* translators: First %s is Assignment */
						'html' => '<div class="ld-gb-clearfix"></div><hr/>' . sprintf( __( '%s Grading', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'assignment' ) ),
					),
				),
				'assignments'                 => array(
					'type' => 'select',
					'args' => array(
						'label'             => LearnDash_Custom_Label::get_label( 'assignments' ),
						'options'           => wp_list_pluck( $options['assignments'], 'text', 'value' ),
						'wrapper_class'     => 'fieldhelpers-col-2',
						'input_class'       => 'widefat ld-gb-component-items-select',
						'input_atts'        => array(
							'data-type'             => 'assignments',
							'data-disable-from-all' => 'assignments'
						),
						'show_empty_select' => true,
						'multiple'          => true,
						/* translators: First %s is Assignments */
						'placeholder'       => sprintf( __( 'No %s', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'assignments' ) ),
						'l10n'              => array(
							/* translators: First %s is Assignments */
							'no_options' => sprintf( __( 'No %s', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'assignments' ) ),
							/* translators: First %s is Assignments */
							'no_results' => sprintf( __( 'No %s Available', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'assignments' ) ),
						),
					),
				),
				'assignments_all'             => array(
					'type' => 'toggle',
					'args' => array(
						/* translators: First %s is Assignments */
						'label'                 => sprintf( __( 'All %s', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'assignments' ) ),
						'wrapper_class'         => 'fieldhelpers-col-2',
						'input_atts'            => array( 'data-disable-group' => 'assignments' ),
						/* translators: First %s is Assignments, second %s is Assignments, third is Lessons, and forth is Topics */
						'description'           => sprintf( __( 'Enabling this will cause all %s to be factored into the Component grade. This will override any %s you have selected, as well as any %s or %s, and instead include everything.', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'assignments' ), LearnDash_Custom_Label::get_label( 'assignments' ), LearnDash_Custom_Label::get_label( 'lessons' ), LearnDash_Custom_Label::get_label( 'topics' ) ),
						'description_placement' => 'after_label',
					),
				),
				'assignments_clearfix'        => array(
					'type' => 'html',
					'args' => array(
						'html' => '<div class="ld-gb-clearfix"></div>',
					),
				),
				'assignment_lessons'          => array(
					'type' => 'select',
					'args' => array(
						/* translators: First %s is Assignments and second %s is Lessons */
						'label'                 => sprintf( __( '%s from %s', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'assignments' ), LearnDash_Custom_Label::get_label( 'lessons' ) ),
						'options'               => wp_list_pluck( $options['assignment_lessons'], 'text', 'value' ),
						'wrapper_class'         => 'fieldhelpers-col-2',
						'input_class'           => 'widefat ld-gb-component-items-select',
						'input_atts'            => array(
							'data-type'             => 'assignment_lessons',
							'data-disable-from-all' => 'assignments'
						),
						'show_empty_select'     => true,
						'multiple'              => true,
						/* translators: First %s is Lessons */
						'placeholder'           => sprintf( __( 'No %s', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'lessons' ) ),
						/* translators: First %s is Assignments, second %s is Lessons, third is Assignments, and forth is Assignments */
						'description'           => sprintf( __( 'Only %s belonging to these %s will be graded. This will override any selected %s. Also, if "All %s" is selected, this will be overriden.', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'assignments' ), LearnDash_Custom_Label::get_label( 'lessons' ), LearnDash_Custom_Label::get_label( 'assignments' ), LearnDash_Custom_Label::get_label( 'assignments' ) ),
						'description_placement' => 'after_label',
						'l10n'                  => array(
							/* translators: First %s is Lessons */
							'no_options' => sprintf( __( 'No %s', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'lessons' ) ),
							/* translators: First %s is Lessons and second %s is Assignment */
							'no_results' => sprintf( __( 'No %s with %s Uploads Available', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'lessons' ), LearnDash_Custom_Label::get_label( 'assignment' ) ),
						),
					),
				),
				'assignment_topics'           => array(
					'type' => 'select',
					'args' => array(
						/* translators: First %s is Assignments and second %s is Topics */
						'label'                 => sprintf( __( '%s from %s', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'assignments' ), LearnDash_Custom_Label::get_label( 'topics' ) ),
						'options'               => wp_list_pluck( $options['assignment_topics'], 'text', 'value' ),
						'wrapper_class'         => 'fieldhelpers-col-2',
						'input_class'           => 'widefat ld-gb-component-items-select',
						'input_atts'            => array(
							'data-type'             => 'assignment_topics',
							'data-disable-from-all' => 'assignments'
						),
						'show_empty_select'     => true,
						'multiple'              => true,
						/* translators: First %s is Topics */
						'placeholder'           => sprintf( __( 'No %s', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'topics' ) ),
						/* translators: First %s is Assignments, second %s is Topics, third is Assignments, and forth is Assignments */
						'description'           => sprintf( __( 'Only %s belonging to these %s will be graded. This will override any selected %s. Also, if "All %s" is selected, this will be overriden.', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'assignments' ), LearnDash_Custom_Label::get_label( 'topics' ), LearnDash_Custom_Label::get_label( 'assignments' ), LearnDash_Custom_Label::get_label( 'assignments' ) ),
						'description_placement' => 'after_label',
						'l10n'                  => array(
							/* translators: First %s is Topics */
							'no_options' => sprintf( __( 'No %s', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'topics' ) ),
							/* translators: First %s is Topics and second %s is Assignment */
							'no_results' => sprintf( __( 'No %s with %s Uploads Available', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'topics' ), LearnDash_Custom_Label::get_label( 'assignment' ) ),
						),
					),
				),
			),
		) );
		
		add_filter( 'pre_get_posts', 'wdm_set_author' );

		ld_gb_fieldhelpers()->fields->save->initialize_fields( 'gradebook-settings' );
		
	}

	/**
	 * Outputs the weighting metabox.
	 *
	 * @since 1.2.0
	 * @access private
	 */
	function mb_weighting() {

		$components = ld_gb_get_field( 'components' );

		include_once LEARNDASH_GRADEBOOK_DIR . 'core/post-types/views/metabox-gradebook-weighting.php';

		ld_gb_init_field_group( 'gradebook-weighting' );
	}

	/**
	 * Outputs the settings metabox.
	 *
	 * @since 1.2.0
	 * @access private
	 */
	function mb_settings() {

		ld_gb_do_field_radio( array(
			'name'    => 'completion_grading_mode',
			'group'   => 'gradebook-settings',
			'label'   => __( 'Completion Grading', 'learndash-gradebook' ),
			'default' => 'completion',
			'options' => array(
				'completion' => __( 'Only count on completion', 'learndash-gradebook' ),
				'pass_fail'  => __( 'Fail until completion', 'learndash-gradebook' ),
			),
		) );

		ld_gb_do_field_toggle( array(
			'name'                      => 'include_all_users',
			'group'                     => 'gradebook-settings',
			'label'                     => __( 'Include All Users In Gradebook', 'learndash-gradebook' ),
			/* translators: First %s is Course, second %s is Course, and third is Course */
			'description'               => sprintf( __( 'If this Gradebook has a %s set, only students enrolled in that %s will show in the Gradebook. If you would to show all users in this Gradebook, despite a %s selected, enable this.', 'learndash-gradebook' ), LearnDash_Custom_Label::get_label( 'course' ), LearnDash_Custom_Label::get_label( 'course' ), LearnDash_Custom_Label::get_label( 'course' ) ),
			'description_placement'     => 'after_label',
			'description_tip_alignment' => 'right',
		) );

		$orderby_options = array(
			array(
				'text'  => __( 'Name', 'learndash-gradebook' ),
				'value' => 'title',
			),
			array(
				'text'  => __( 'Grade', 'learndash-gradebook' ),
				'value' => 'grade',
			),
			array(
				'text'  => __( 'Date Created', 'learndash-gradebook' ),
				'value' => 'date',
			),
			array(
				'text'  => __( 'Date Modified', 'learndash-gradebook' ),
				'value' => 'modified',
			),
		);

		$order_options = array(
			array(
				'text'  => __( 'Ascending', 'learndash-gradebook' ),
				'value' => 'asc',
			),
			array(
				'text'  => __( 'Descending', 'learndash-gradebook' ),
				'value' => 'desc',
			),
		);

		ld_gb_do_field_select( array(
			'name'    => 'component_orderby',
			'group'   => 'gradebook-settings',
			'label'   => __( 'Order Component Resources By', 'learndash-gradebook' ),
			'options' => $orderby_options,
		) );

		ld_gb_do_field_select( array(
			'name'    => 'component_order',
			'group'   => 'gradebook-settings',
			'label'   => __( 'Component Resource Order', 'learndash-gradebook' ),
			'options' => $order_options,
		) );

		ld_gb_init_field_group( 'gradebook-settings' );
	}

	/**
	 * Makes sure components are valid when saving.
	 *
	 * @since 1.3.6
	 * @access private
	 *
	 * @param array $components
	 * @param int $gradebook_ID
	 */
	function validate_components_save( $components, $gradebook_ID ) {

		$components = $this->validate_components_ids( $components, $gradebook_ID );

		return $components;
	}

	/**
	 * If components are missing ID's, generate new ones.
	 *
	 * @since 1.3.6
	 * @access private
	 *
	 * @param array $components
	 * @param int $gradebook_ID
	 *
	 * @return array
	 */
	private function validate_components_ids( $components, $gradebook_ID ) {

		foreach ( $components as &$component ) {

			if ( ! $component['id'] || (int) $component['id'] < 0 ) {

				$component['id'] = $this->get_latest_component_id( $gradebook_ID );
			}
		}

		return $components;
	}

	/**
	 * Get available options for a specific Course.
	 *
	 * @since 1.2.6
	 *
	 * @param int|string $course_id Course ID to filter by.
	 *
	 * @return array Options grouped by type.
	 */
	public static function get_course_component_options( $course_id ) {

		$options = array(
			'lessons'     => array(),
			'topics'      => array(),
			'quizzes'     => array(),
			'assignments' => array(),
			'assignment_lessons' => array(),
			'assignment_topics' => array(),
		);

		$option_type_map = array(
			'sfwd-courses'    => 'courses',
			'sfwd-assignment' => 'assignments',
		);

		foreach ( $option_type_map as $post_type => $option_type ) {

			if ( $option_type === 'assignments' ) {

				$assignment_posts = get_posts( array(
					'post_type'   => 'sfwd-assignment',
					'numberposts' => - 1,
					'meta_key'    => 'course_id',
					'meta_value'  => $course_id,
					'meta_query'  => array(
						array(
							'key'   => 'approval_status',
							'value' => '1',
						),
					),
				) );

				$steps = wp_list_pluck( $assignment_posts, 'ID' );
				
				foreach ( $steps as $step_post_id ) {
					
					$options[ $option_type ][] = array(
						'value' => (int) $step_post_id,
						'text'  => get_the_title( $step_post_id )
					);
					
				}

			}
			else {

				$steps_object = LDLMS_Factory_Post::course_steps( $course_id );
				$course_heirarchy = $steps_object->get_steps( 'h' );
				
				$formatted_data = array(
					'lessons' => array(),
					'topics' => array(),
					'quizzes' => array(),
				);
				
				self::get_formatted_course_heirarchy( $course_heirarchy, $formatted_data );
				
				// Add everything but Assignments
				foreach ( $formatted_data as $data_type => $data ) {

					foreach ( $data as $id => $formatted_title ) {

						// We want to also include this data for our Assignment Lesson/Topic dropdowns
						if ( ( $data_type == 'lessons' || $data_type == 'topics' ) && 
							lesson_hasassignments( get_post( $id ) ) ) {

							$options[ "assignment_$data_type" ][] = array(
								'value' => $id,
								'text'  => $formatted_title,
							);

						}

						$options[ $data_type ][] = array(
							'value' => $id,
							'text'  => $formatted_title,
						);

					}

				}
				
			}

		}

		return $options;
	}

	/**
	 * Get ALL available options (Lesson, Topic, Quiz, Assignment).
	 *
	 * @since 1.2.6
	 *
	 * @return array Options grouped by type.
	 */
	public static function get_all_component_options() {

		$options = array(
			'lessons'     => array(),
			'topics'      => array(),
			'quizzes'     => array(),
			'assignments' => array(),
			'assignment_lessons' => array(),
			'assignment_topics' => array(),
		);

		$option_type_map = array(
			'sfwd-lessons'    => 'lessons',
			'sfwd-topic'      => 'topics',
			'sfwd-quiz'       => 'quizzes',
			'sfwd-assignment' => 'assignments',
		);

		$posts = get_posts( array(
			'post_type'   => array( 'sfwd-courses', 'sfwd-assignment' ),
			'numberposts' => - 1,
		) );

		if ( ! $posts || is_wp_error( $posts ) ) {

			return $options;
		}
		
		// Holds all our Non-Assignment Data to add to $options later
		// We have to manually traverse each Course's Step Heirarchy, so it is easiest to process them and then add as a batch at the end
		$formatted_data = array(
			'lessons' => array(),
			'topics' => array(),
			'quizzes' => array(),
		);

		foreach ( $posts as $post ) {
			
			if ( $post->post_type == 'sfwd-assignment' ) {
				
				// Add to options array by post type
				$options[ $option_type_map[ $post->post_type ] ][] = array(
					'value' => $post->ID,
					'text'  => get_the_title( $post->ID ),
				);
				
			}
			else { // Get proper hierarchy for Course
				
				$steps_object = LDLMS_Factory_Post::course_steps( $post->ID );
				$course_heirarchy = $steps_object->get_steps( 'h' );
				
				self::get_formatted_course_heirarchy( $course_heirarchy, $formatted_data, array( $post->ID ) );
				
			}
			
		}
		
		// Add everything but Assignments
		foreach ( $formatted_data as $data_type => $data ) {
					
			foreach ( $data as $id => $formatted_title ) {

				// We want to also include this data for our Assignment Lesson/Topic dropdowns
				if ( ( $data_type == 'lessons' || $data_type == 'topics' ) && 
					lesson_hasassignments( get_post( $id ) ) ) {

					$options[ "assignment_$data_type" ][] = array(
						'value' => $id,
						'text'  => $formatted_title,
					);

				}

				$options[ $data_type ][] = array(
					'value' => $id,
					'text'  => $formatted_title,
				);

			}

		}

		return $options;
	}

	/**
	 * Gets component options.
	 *
	 * @since 1.2.6
	 *
	 * @param bool|int|string $course_id Send course post ID to filter by Course. Otherwise all component options are
	 *                                   returned.
	 *
	 * @return array Component options grouped by type.
	 */
	public static function get_component_options( $course_id = 'all' ) {

		if ( ! $course_id || $course_id == 'all' ) {

			return self::get_all_component_options();

		} else {

			return self::get_course_component_options( $course_id );
		}
	}
	
	/** 
	 * Constructs a Component Title based on an Array of Post IDs
	 * 
	 * @param		array  IDs, in order of LearnDash Course Hierarchy
	 *                                                  
	 * @access		public
	 * @since		1.3.7
	 * @return		string Formatted Hierarchy string
	 */
	public static function get_component_title( $ids = array() ) {
		
		// Remove duplicates and empties
		$ids = array_unique( array_filter( $ids ) );
		
		$titles = array();
		foreach ( $ids as $id ) {
			
			$titles[] = get_the_title( $id );
			
		}
		
		return implode( ' -> ', $titles );
		
	}
	
	/**
	 * Builds out a Course Heirarchy in a format we can use
	 * 
	 * @param		array LDLMS_Factory_Post::course_steps( $course_id )->get_steps( 'h' )
	 * @param		array Holds our results. Passed by Reference
	 * @param		array Holds the Component Post IDs used to build the Title
	 *                                                              
	 * @access		public
	 * @since		1.3.7
	 * @return		void
	 */ 
	public static function get_formatted_course_heirarchy( $course_hierarchy_steps, &$formatted_data = array( 'lessons' => array(), 'topics' => array(), 'quizzes' => array() ), $components_array = array() ) {
		
		$option_type_map = array(
			'sfwd-lessons'    => 'lessons',
			'sfwd-topic'      => 'topics',
			'sfwd-quiz'       => 'quizzes',
		);
		
		foreach ( $course_hierarchy_steps as $key => $value ) {
			
			if ( is_array( $value ) ) {
				
				if ( is_numeric( $key ) ) { // Only check against Post IDs
					
					$type = $option_type_map[ get_post_type( $key ) ];
					
					if ( ! isset( $formatted_data[ $type ][ $key ] ) ) {
							
						$formatted_data[ $type ][ $key ] = self::get_component_title( array_merge( $components_array, array( $key ) ) );

					}
					else { // Shared Component

						$formatted_data[ $type ][ $key ] .= ', ' . self::get_component_title( array_merge( $components_array, array( $key ) ) );

					}
					
					if ( ! empty( $value ) ) {
				
						self::get_formatted_course_heirarchy( $value, $formatted_data, array_merge( $components_array, array( $key ) ) );
						
					}
					
				}
				else { // If not a Post ID, we need to go a level Deeper
					
					self::get_formatted_course_heirarchy( $value, $formatted_data, $components_array );
					
				}
				
			}
			
		}
		
	}

	/**
	 * Retrieves the latest component ID AND saves it to the DB.
	 *
	 * Be warned: Even calling this function will increment the last component ID.
	 *
	 * @since 1.3.6
	 * @access private
	 *
	 * @param int|string $gradebook_ID
	 *
	 * @return int
	 */
	private function get_latest_component_id( $gradebook_ID ) {

		$last = (int) get_post_meta( $gradebook_ID, 'last_component_id', true );
		update_post_meta( $gradebook_ID, 'last_component_id', $last + 1 );

		return $last;
	}

	/**
	 * Retrieves all options for a course (lessons, topics, quizzes, assignments).
	 *
	 * @since 1.2.0
	 * @access private
	 */
	function ajax_get_component_options() {
		
		// Fix specific to the Instructor Roles plugin by WisdmLabs
		remove_filter( 'pre_get_posts', 'wdm_set_author' );

		$course = $_POST['course'];

		if ( $_POST['postStatus'] == 'auto-draft' && ! $course ) {

			// If it is a new post, we do not need to worry about a legacy fallback to 'all' for an empty Course ID
			$options = array(
				'lessons' => array(),
				'topics' => array(),
				'quizzes' => array(),
				'assignments' => array(),
				'assignment_lessons' => array(),
				'assignment_topics' => array(),
			);

		}
		else {

			$options = self::get_component_options( $course );

		}
		
		add_filter( 'pre_get_posts', 'wdm_set_author' );

		wp_send_json_success( $options );
	}

	/**
	 * Retrieves a new component ID.
	 *
	 * @since 1.2.0
	 * @access private
	 */
	function ajax_get_new_component_id() {

		$gradebook_ID = $_POST['gradebook_id'];

		$last = $this->get_latest_component_id( $gradebook_ID );

		wp_send_json_success( array( 'id' => $last + 1 ) );
	}
	
	/**
	 * Allows the Author of a Gradebook to be reassigned to any other User, provided they can Edit Gradebooks
	 * 
	 * @param		array $query_args    Args for WP_User_Query/get_users()
	 * @param		array $function_args Args provided to wp_dropdown_users()
	 *                                               
	 * @access		public
	 * @since		1.4.1
	 * @return		array Args for WP_User_Query/get_users()
	 */
	public function wp_dropdown_users_args( $query_args, $function_args ) {
		
		if ( get_post_type() !== 'gradebook' ) return $query_args;
		
		// Do not restrict to only Authors/Administrators
		$query_args['who'] = 'all';
		
		global $wp_roles;

		if ( ! isset( $wp_roles ) ) $wp_roles = new WP_Roles();

		// All Roles
		$role_names = $wp_roles->get_names();

		$can_edit_gradebooks = array();
		
		foreach ( $role_names as $role_key => $role_name ) {
			
			$role = get_role( $role_key );
			
			if ( isset( $role->capabilities['edit_gradebook'] ) && $role->capabilities['edit_gradebook'] == 1 ) {
				$can_edit_gradebooks[] = $role_key;
			}
			
		}
		
		$query_args['role__in'] = $can_edit_gradebooks;
		
		return $query_args;
		
	}
	
	public function fix_instructors_not_seeing_course_components( $post_types ) {
		
		return $post_types[] = 'gradebook';
		
	}
	
}