<?php
/**
 * Handles plugin upgrades.
 *
 * @since 1.2.0
 */

defined( 'ABSPATH' ) || die();

/**
 * Class LD_GB_Upgrade
 *
 * Handles plugin upgrades.
 *
 * @since 1.2.0
 */
class LD_GB_Upgrade {

	/**
	 * LD_GB_Upgrade constructor.
	 *
	 * @since 1.2.0
	 *
	 * @return bool True if needs to upgrade, false if does not.
	 */
	function __construct() {

		add_action( 'admin_init', array( $this, 'check_upgrades' ) );

		if ( isset( $_GET['ld_gb_upgrade'] ) ) {

			add_action( 'admin_init', array( $this, 'do_upgrades' ) );
		}
	}

	/**
	 * Checks for upgrades and migrations.
	 *
	 * @since 1.2.0
	 * @access private
	 */
	function check_upgrades() {

		$version = get_option( 'ld_gb_version', 0 );

		if ( version_compare( $version, LEARNDASH_GRADEBOOK_VERSION ) === - 1 ) {

			LD_GB_Install::install();
			update_option( 'ld_gb_version', LEARNDASH_GRADEBOOK_VERSION );
		}

		$last_upgrade = get_option( 'ld_gb_last_upgrade', 0 );
		
		// If LD GB Version isn't at 2.0.0 or higher but Last Upgrade is, assume bad upgrade script
		if ( $last_upgrade == '2.0.0' && 
		   version_compare( LEARNDASH_GRADEBOOK_VERSION, '2.0.0' ) === -1 ) {
			$last_upgrade = '1.2.0';
			update_option( 'ld_gb_last_upgrade', $last_upgrade );
		}

		foreach ( $this->get_upgrades() as $upgrade_version => $upgrade_callback ) {

			if ( version_compare( $last_upgrade, $upgrade_version ) === - 1 ) {

				add_action( 'admin_notices', array( $this, 'show_upgrade_nag' ) );
				break;
			}
		}
	}

	/**
	 * Runs upgrades.
	 *
	 * @since 1.2.0
	 * @access private
	 */
	function do_upgrades() {

		$last_upgrade = get_option( 'ld_gb_last_upgrade', 0 );

		foreach ( $this->get_upgrades() as $upgrade_version => $upgrade_callback ) {

			if ( version_compare( $last_upgrade, $upgrade_version ) === - 1 ) {

				call_user_func( $upgrade_callback );
				update_option( 'ld_gb_last_upgrade', $upgrade_version );
			}
		}

		wp_safe_redirect( admin_url( 'index.php?page=learndash-gradebook-welcome' ) );
		exit();
	}

	/**
	 * Returns an array of all versions that require an upgrade.
	 *
	 * @since 1.2.2
	 * @access private
	 *
	 * @return array
	 */
	function get_upgrades() {

		return array(
			'1.2.0' => array( $this, 'upgrade_1_2_0' ),
			'1.3.7' => array( $this, 'upgrade_1_3_7' ),
			'1.4.0' => array( $this, 'upgrade_1_4_0' ),
		);
	}

	/**
	 * Displays upgrade nag.
	 *
	 * @since 1.2.0
	 * @access private
	 */
	function show_upgrade_nag() {
		?>
        <div class="notice notice-warning">
            <p>
				<?php _e( 'LearnDash Gradebook needs to upgrade the database. It is strongly recommended you backup your database first.', 'learndash-gradebook' ); ?>
                <a href="<?php echo add_query_arg( 'ld_gb_upgrade', '1' ); ?>"
                   class="button button-primary">
					<?php _e( 'Upgrade', 'learndash-gradebook' ); ?>
                </a>
            </p>
        </div>
		<?php
	}

	/**
	 * Displays the upgraded complete message.
	 *
	 * @since 1.2.0
	 * @access private
	 */
	function show_upgraded_message() {
		?>
        <div class="notice notice-success">
            <p>
				<?php _e( 'LearnDash Gradebook has successfully upgraded!', 'learndash-gradebook' ); ?>
            </p>
        </div>
		<?php
	}

	/**
	 * 1.2.0 upgrade script.
	 *
	 * @since 1.2.0
	 * @access private
	 *
	 * @global WPDB $wpdb
	 */
	function upgrade_1_2_0() {

		global $wpdb;

		$term_rows = $wpdb->get_results(
			"
			SELECT * FROM {$wpdb->prefix}term_taxonomy
			WHERE taxonomy = 'gradebook-type'
			"
		);

		// If Types don't exist, no need to migrate
		if ( is_wp_error( $term_rows ) || empty( $term_rows ) ) {

			return;
		}

		// Also, make sure there are no Gradebooks yet, that's not right
		$gradebooks = get_posts( array(
			'post_type'   => 'gradebook',
			'numberposts' => - 1,
			'post_status' => 'any',
		) );

		if ( ! empty( $gradebooks ) ) {

			return;
		}

		// Migrate license key option
		$old_license = get_option( 'ld_gb_license_key' );
		if ( $old_license ) {

			update_option( 'learndash_gradebook_license_key', $old_license );
			update_option( 'learndash_gradebook_license_status', get_option( 'ld_gb_license_status', '' ) );
			delete_option( 'ld_gb_license_key' );
		}

		// Register so we can get them to use and then delete
		register_taxonomy( 'gradebook-type', array( 'sfwd-quiz', 'sfwd-assignment' ), array(
			'public'            => false,
			'show_ui'           => false,
			'hierarchical'      => true,
			'show_in_menu'      => false,
			'show_in_nav_menus' => false,
			'show_tagcloud'     => false,
		) );

		$gradebook_ID = wp_insert_post( array(
			'post_type'   => 'gradebook',
			'post_status' => 'publish',
			'post_title'  => 'Gradebook',
		) );

		// Migrate Types to Components
		$types = get_terms( array(
			'taxonomy'   => 'gradebook-type',
			'hide_empty' => false,
		) );

		// Should not be possible due to above DB check, but gotta be sure
		if ( ! $types ) {

			return;
		}

		$components = array();
		foreach ( $types as $component_ID => $type ) {

			$component = array(
				'id'              => $component_ID,
				'name'            => $type->name,
				'weight'          => get_term_meta( $type->term_id, 'weight', true ),
				'lessons_all'     => '',
				'topics_all'      => '',
				'quizzes_all'     => '',
				'assignments_all' => '',
				'lessons'         => array(),
				'topics'          => array(),
				'quizzes'         => array(),
				'assignments'     => array(),
			);

			$posts = get_posts( array(
				'post_type'   => array( 'sfwd-assignment', 'sfwd-quiz' ),
				'numberposts' => - 1,
				'tax_query'   => array(
					array(
						'taxonomy' => 'gradebook-type',
						'field'    => 'id',
						'terms'    => $type->term_id,
					),
				),
			) );

			foreach ( $posts as $post ) {

				switch ( $post->post_type ) {

					case 'sfwd-assignment':
						$component['assignments'][] = $post->ID;
						break;

					case 'sfwd-quiz':
						$component['quizzes'][] = $post->ID;
						break;
				}

				// Migrate grade statuses
				$wpdb->get_results(
					"
                    UPDATE {$wpdb->prefix}usermeta
                    SET meta_key = 'ld_gb_grade_status_{$gradebook_ID}_{$post->ID}'
                    WHERE meta_key = 'ld_gb_grade_status_{$post->ID}'
                    "
				);
			}

			$components[] = $component;

			// Migrate manual grades
			$wpdb->get_results(
				"
                UPDATE {$wpdb->prefix}usermeta
                SET meta_key = 'ld_gb_manual_grades_{$gradebook_ID}_{$component_ID}' 
                WHERE meta_key = 'ld_gb_manual_grades_{$type->term_id}'
                "
			);

			wp_delete_term( $type->term_id, 'gradebook-type' );
		}

		// Migrate component grades
		$wpdb->get_results(
			"
            UPDATE {$wpdb->prefix}usermeta
            SET meta_key = 'ld_gb_component_grades_{$gradebook_ID}'
            WHERE meta_key = 'ld_gb_component_grades'
            "
		);

		$component_override_results = $wpdb->get_results(
			"
            SELECT * FROM {$wpdb->prefix}usermeta
            WHERE meta_key = 'ld_gb_component_grades_{$gradebook_ID}'
            "
		);

		if ( $component_override_results ) {

			$types_map     = wp_list_pluck( $types, 'term_id' );
			$new_overrides = array();
			foreach ( $component_override_results as $result ) {

				$component_overrides = maybe_unserialize( $result->meta_value );

				foreach ( $component_overrides as $type_ID => $override ) {

					$new_overrides[ array_search( $type_ID, $types_map ) ] = $override;
				}

				update_user_meta( $result->user_id, "ld_gb_component_grades_{$gradebook_ID}", $new_overrides );
			}
		}

		$is_weighted = get_option( 'ld_gb_weight_type' ) === 'weighted';
		delete_option( 'ld_gb_weight_type' );

		update_post_meta( $gradebook_ID, 'ld_gb_gradebook_weighting_enable', $is_weighted ? '1' : '' );
		update_post_meta( $gradebook_ID, 'ld_gb_components', $components );
		update_post_meta( $gradebook_ID, 'last_component_id', $component_ID );

		// Assignment grades to new points system
		$assignments = get_posts( array(
			'post_type'    => 'sfwd-assignment',
			'numberposts'  => - 1,
			'post_status'  => 'any',
			'meta_key'     => 'assignment_grade',
			'meta_compare' => 'EXISTS',
		) );

		if ( $assignments ) {

			foreach ( $assignments as $assignment ) {

				$grade = (int) get_post_meta( $assignment->ID, 'assignment_grade', true );

				if ( $grade === false || $grade === null || $grade === '' ) {

					continue;
				}

				$lesson_ID      = get_post_meta( $assignment->ID, 'lesson_id', true );
				$points_enabled = learndash_get_setting( $lesson_ID, 'lesson_assignment_points_enabled' );
				$points_amount  = (int) learndash_get_setting( $lesson_ID, 'lesson_assignment_points_amount' );

				if ( $points_enabled !== 'on' ) {

					learndash_update_setting( $lesson_ID, 'lesson_assignment_points_enabled', 'on' );
				}

				if ( ! $points_amount ) {

					$points_amount = 100;
					learndash_update_setting( $lesson_ID, 'lesson_assignment_points_amount', $points_amount );
				}

				update_post_meta( $assignment->ID, 'points', $points_amount / 100 * round( $grade ) );

				delete_post_meta( $assignment->ID, 'assignment_grade' );
			}
		}
	}
	
	/**
	 * 1.3.7 upgrade script.
	 *
	 * @since 1.3.7
	 * @access private
	 */
	function upgrade_1_3_7() {
		
		// Fix edit_others_gradebooks not being granted to Administrators
		LD_GB_Install::setup_capabilities();
		
	}
	
	/**
	 * 1.4.0 upgrade script.
	 *
	 * @since 1.4.0
	 * @access private
	 */
	function upgrade_1_4_0() {
		
		// Fix view_gradebook possibly being revoked from Group Leaders
		LD_GB_Install::setup_capabilities();
		
	}
	
}