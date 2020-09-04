<?php
/**
 * Shortcode: User Grade
 *
 * @since 1.0.0
 *
 * @package LearnDash_Gradebook
 * @subpackage LearnDash_Gradebook/includes/shortcodes
 */

defined( 'ABSPATH' ) || die();

/**
 * Class LD_GB_SC_ReportCard
 *
 * Contains the grade for a given user.
 *
 * @since 1.0.0
 *
 * @package LearnDash_Gradebook
 * @subpackage LearnDash_Gradebook/includes/shortcodes
 */
class LD_GB_SC_ReportCard extends LD_GB_Shortcode {

	/**
	 * Whether or not this shortcode was used.
	 *
	 * @since 1.0.0
	 *
	 * @var bool
	 */
	private $used = false;

	/**
	 * LD_GB_SC_ReportCard constructor.
	 *
	 * @since 1.0.0
	 */
	function __construct() {

		parent::__construct( 'ld_report_card', array(
			'user'               => get_current_user_id(),
			'gradebook'          => false,
			'logged_out_message' => __( 'Please log in to view your report card.', 'learndash-gradebook' ),
		) );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'wp_print_footer_scripts', array( $this, 'unload_assets' ), 1 );

		// Template actions
		add_action( 'report-card-expand-collapse', array( __CLASS__, 'template_expand_collapse' ), 10, 2 );
		add_action( 'report-card-title', array( __CLASS__, 'template_title' ), 10, 2 );
		add_action( 'report-card-overall-grade', array( __CLASS__, 'template_overall_grade' ), 10, 2 );
		add_action( 'report-card-component', array( __CLASS__, 'template_component' ), 10, 3 );
		add_action( 'report-card-component-toggle', array( __CLASS__, 'template_component_toggle' ), 10, 4 );
		add_action( 'report-card-component-info', array( __CLASS__, 'template_component_title' ), 10, 4 );
		add_action( 'report-card-component-info', array( __CLASS__, 'template_component_grade' ), 20, 4 );
		add_action( 'report-card-grades-header', array( __CLASS__, 'template_grades_header' ), 10, 3 );
		add_action( 'report-card-grade', array( __CLASS__, 'template_grade' ), 10, 5 );
		add_action( 'report-card-grade-content', array( __CLASS__, 'template_grade_type' ), 10, 5 );
		add_action( 'report-card-grade-content', array( __CLASS__, 'template_grade_name' ), 20, 5 );
		add_action( 'report-card-grade-content', array( __CLASS__, 'template_grade_score' ), 30, 5 );
	}

	/**
	 * Loads report card assets.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	function enqueue_assets() {

		/**
		 * Change loading of report card styles.
		 *
		 * @since 1.0.0
		 */
		$load_report_card_styles = apply_filters( 'ld_gb_report_card_styles', true );

		if ( $load_report_card_styles ) {

			wp_enqueue_style( 'ld-gb-report-card' );

			// LearnDash v3.x changed how their CSS was loaded, so we need to load in the images we were using from them before like this

			if ( defined( 'LEARNDASH_VERSION' ) ) {
			
				// Pad in a Patch version if necessary
				$ld_version = ( substr_count( LEARNDASH_VERSION, '.' ) == 1 ) ? LEARNDASH_VERSION . '.0' : LEARNDASH_VERSION;
				
				if ( version_compare( $ld_version, '3.0.0', '>=' ) ) : ?>
					<style type="text/css">
						.ld-gb-report-card-component-expand.list_arrow.collapse {
							background: url("<?php echo LEARNDASH_LMS_PLUGIN_URL; ?>assets/images/gray_arrow_collapse.png") no-repeat scroll 0 50% transparent;
							padding: 5px;
						}
						.ld-gb-report-card-component-expand.list_arrow.expand {
							background: url("<?php echo LEARNDASH_LMS_PLUGIN_URL; ?>assets/images/gray_arrow_expand.png") no-repeat scroll 0 50% transparent;
							padding: 5px;
						}
					</style>
				<?php endif;

			}

		}
	}

	/**
	 * Unloads report card assets if the shortcode was not used.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	function unload_assets() {

		if ( ! $this->used ) {
			wp_dequeue_script( 'ld-gb-report-card' );
		}
	}

	/**
	 * Outputs the shortcode.
	 *
	 * @since 1.0.0
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return mixed
	 */
	function shortcode( $atts = array(), $content = '' ) {

		// Tell LearnDash to load template assets
		global $learndash_shortcode_used;
		$learndash_shortcode_used = true;

		// Tell LD GB to load the report card assets
		$this->used = true;

		$this->default_atts( $atts );

		if ( ! is_user_logged_in() ) {
			return $this->atts['logged_out_message'];
		}

		// Get user
		if ( ! ( $user = get_user_by( 'id', $this->atts['user'] ) ) ) {
			return __( 'Cannot get user.', 'learndash-gradebook' );
		}

		$gradebooks = array();

		// Load all Gradebooks that the Student is in specifically
		if ( ! $this->atts['gradebook'] ) {

			$user_progress = SFWD_LMS::get_course_info( $this->atts['user'], array(
				'user_id' => $this->atts['user'],
				'return' => true,
				'type' => 'registered',
			) );

			if ( isset( $user_progress['courses_registered'] ) && 
				! empty( $user_progress['courses_registered'] ) ) {

				foreach ( $user_progress['courses_registered'] as $course_id ) {

					// Get all Gradebooks that have a specific Course set that this Student is enrolled in and have begun (This includes Open courses)
					$gradebook_query = new WP_Query( array(
						'post_type' => 'gradebook',
						'posts_per_page' => -1,
						'fields' => 'ids',
						'meta_query' => array(
							array(
								'key' => 'ld_gb_course',
								'value' => $course_id,
							)
						)
					) );

					if ( $gradebook_query->have_posts() ) {

						foreach ( $gradebook_query->posts as $gradebook_id ) {
							$gradebooks[] = $gradebook_id;
						}

					}

				}

			}

			// If the Gradebook has "All Courses" set, then we should show that too
			$gradebook_query = new WP_Query( array(
				'post_type' => 'gradebook',
				'posts_per_page' => -1,
				'fields' => 'ids',
				'meta_query' => array(
					'relation' => 'OR',
					array(
						'key' => 'ld_gb_course',
						'value' => '',
					),
					array(
						'key' => 'ld_gb_course',
						'value' => 'all',
					)
				)
			) );

			if ( $gradebook_query->have_posts() ) {

				foreach ( $gradebook_query->posts as $gradebook_id ) {
					$gradebooks[] = $gradebook_id;
				}

			}

			$gradebooks = array_unique( $gradebooks );

		}
		else {
			$gradebooks = array( $this->atts['gradebook'] );
		}

		ob_start();

		foreach ( $gradebooks as $gradebook_id ) {

			if ( ! get_post( $gradebook_id ) ) {
	
				ld_gb_locate_template( 'report-card/report-card-error.php' );
				
			}
	
			$user_grade = new LD_GB_UserGrade( $user, $gradebook_id );
	
			// Legacy template override
			if ( $template_file = locate_template( array( '/learndash/report-card.php' ) ) ) {
	
				include $template_file;
	
			} else {
	
				// Allow the theme to load the template instead of the plugin
				ld_gb_locate_template( 'report-card/report-card.php', array(
					'user_grade' => $user_grade,
					'gradebook_id' => $gradebook_id,
				) );
			}

		}

		return ob_get_clean();
	}

	/**
	 * Outputs the report card expand/collapse.
	 *
	 * @since 1.1.0
	 * @updated 1.6.0
	 *
	 * @param LD_GB_UserGrade $user_grade
	 * @param intereger       $gradebook_id
	 */
	static function template_expand_collapse( $user_grade, $gradebook_id ) {

		ld_gb_locate_template( 'report-card/expand-collapse.php', array(
			'user_grade' => $user_grade,
			'gradebook_id' => $gradebook_id,
		) );
	}

	/**
	 * Outputs the report card title.
	 *
	 * @since 1.1.0
	 * @updated 1.6.0
	 *
	 * @param LD_GB_UserGrade $user_grade
	 * @param int $gradebook_id
	 */
	static function template_title( $user_grade, $gradebook_id ) {

		ld_gb_locate_template( 'report-card/title.php', array(
			'user_grade' => $user_grade,
			'gradebook_id' => $gradebook_id,
		) );
	}

	/**
	 * Outputs the report card overall grade.
	 *
	 * @since 1.1.0
	 * @updated 1.6.0
	 *
	 * @param LD_GB_UserGrade $user_grade
	 * @param int $gradebook_id
	 */
	static function template_overall_grade( $user_grade, $gradebook_id ) {

		ld_gb_locate_template( 'report-card/overall-grade.php', array(
			'user_grade' => $user_grade,
			'gradebook_id' => $gradebook_id,
		) );
	}

	/**
	 * Outputs the report card component.
	 *
	 * @since 1.1.0
	 * @updated 1.6.0
	 *
	 * @param array $component
	 * @param LD_GB_UserGrade $user_grade
	 * @param int $gradebook_id
	 */
	static function template_component( $component, $user_grade, $gradebook_id ) {

		ld_gb_locate_template( 'report-card/component/component.php', array(
			'user_grade' => $user_grade,
			'component'  => $component,
			'gradebook_id' => $gradebook_id,
		) );
	}

	/**
	 * Outputs the report card toggle.
	 *
	 * @since 1.1.0
	 * @updated 1.6.0
	 *
	 * @param array $component
	 * @param LD_GB_UserGrade $user_grade
	 * @param int $gradebook_id
	 * @param string $component_handle
	 */
	static function template_component_toggle( $component, $user_grade, $gradebook_id, $component_handle ) {

		ld_gb_locate_template( 'report-card/component/toggle.php', array(
			'user_grade' => $user_grade,
			'component'  => $component,
			'gradebook_id' => $gradebook_id,
			'component_handle' => $component_handle,
		) );
	}

	/**
	 * Outputs the report card title.
	 *
	 * @since 1.1.0
	 * @updated 1.6.0
	 *
	 * @param array $component
	 * @param LD_GB_UserGrade $user_grade
	 * @param int $gradebook_id
	 * @param string $component_handle
	 */
	static function template_component_title( $component, $user_grade, $gradebook_id, $component_handle ) {

		ld_gb_locate_template( 'report-card/component/title.php', array(
			'user_grade' => $user_grade,
			'component'  => $component,
			'gradebook_id' => $gradebook_id,
			'component_handle' => $component_handle,
		) );
	}

	/**
	 * Outputs the report card grade.
	 *
	 * @since 1.1.0
	 * @updated 1.6.0
	 *
	 * @param array $component
	 * @param LD_GB_UserGrade $user_grade
	 * @param int $gradebook_id
	 * @param string $component_handle
	 */
	static function template_component_grade( $component, $user_grade, $gradebook_id, $component_handle ) {

		ld_gb_locate_template( 'report-card/component/grade.php', array(
			'user_grade' => $user_grade,
			'component'  => $component,
			'gradebook_id' => $gradebook_id,
			'component_handle' => $component_handle,
		) );
	}

	/**
	 * Outputs the report card grades header.
	 *
	 * @since 1.1.0
	 * @updated 1.6.0
	 *
	 * @param array $component
	 * @param LD_GB_UserGrade $user_grade
	 * @param int $gradebook_id
	 */
	static function template_grades_header( $component, $user_grade, $gradebook_id ) {

		ld_gb_locate_template( 'report-card/component/grades-header.php', array(
			'user_grade' => $user_grade,
			'component'  => $component,
			'gradebook_id' => $gradebook_id,
		) );
	}

	/**
	 * Outputs the report card grade.
	 *
	 * @since 1.1.0
	 * @updated 1.6.0 
	 *
	 * @param string $grade
	 * @param int $grade_i
	 * @param array $component
	 * @param LD_GB_UserGrade $user_grade
	 * @param int $gradebook_id
	 */
	static function template_grade( $grade, $grade_i, $component, $user_grade, $gradebook_id ) {

		ld_gb_locate_template( 'report-card/grade/grade.php', array(
			'grade'      => $grade,
			'grade_i'    => $grade_i,
			'user_grade' => $user_grade,
			'component'  => $component,
			'gradebook_id' => $gradebook_id,
		) );
	}

	/**
	 * Outputs the report card grade type.
	 *
	 * @since 1.1.0
	 * @updated {{VERSION}
	 *
	 * @param string $grade
	 * @param int $grade_i
	 * @param array $component
	 * @param LD_GB_UserGrade $user_grade
	 * @param int $gradebook_id
	 */
	static function template_grade_type( $grade, $grade_i, $component, $user_grade, $gradebook_id ) {

		ld_gb_locate_template( 'report-card/grade/type.php', array(
			'grade'      => $grade,
			'grade_i'    => $grade_i,
			'user_grade' => $user_grade,
			'component'  => $component,
			'gradebook_id' => $gradebook_id,
		) );
	}

	/**
	 * Outputs the report card grade name.
	 *
	 * @since 1.1.0
	 * @updated 1.6.0
	 *
	 * @param string $grade
	 * @param int $grade_i
	 * @param array $component
	 * @param LD_GB_UserGrade $user_grade
	 * @param int $gradebook_id
	 */
	static function template_grade_name( $grade, $grade_i, $component, $user_grade, $gradebook_id ) {

		ld_gb_locate_template( 'report-card/grade/name.php', array(
			'grade'      => $grade,
			'grade_i'    => $grade_i,
			'user_grade' => $user_grade,
			'component'  => $component,
			'gradebook_id' => $gradebook_id,
		) );
	}

	/**
	 * Outputs the report card grade core.
	 *
	 * @since 1.1.0
	 * @updated 1.6.0
	 *
	 * @param string $grade
	 * @param int $grade_i
	 * @param array $component
	 * @param LD_GB_UserGrade $user_grade
	 * @param int $gradebook_id
	 */
	static function template_grade_score( $grade, $grade_i, $component, $user_grade, $gradebook_id ) {

		ld_gb_locate_template( 'report-card/grade/score.php', array(
			'grade'      => $grade,
			'grade_i'    => $grade_i,
			'user_grade' => $user_grade,
			'component'  => $component,
			'gradebook_id' => $gradebook_id,
		) );
	}
}