<?php
/**
 * Shortcode: Overall Grade
 *
 * @since 1.6.4
 *
 * @package LearnDash_Gradebook
 * @subpackage LearnDash_Gradebook/includes/shortcodes
 */

defined( 'ABSPATH' ) || die();

/**
 * Class LD_GB_SC_OverallGrade
 *
 * Contains the grade for a given user.
 *
 * @since 1.6.4
 *
 * @package LearnDash_Gradebook
 * @subpackage LearnDash_Gradebook/includes/shortcodes
 */
class LD_GB_SC_OverallGrade extends LD_GB_Shortcode {

	/**
	 * Whether or not this shortcode was used.
	 *
	 * @since 1.6.4
	 *
	 * @var bool
	 */
	private $used = false;

	/**
	 * LD_GB_SC_OverallGrade constructor.
	 *
	 * @since 1.6.4
	 */
	function __construct() {

		parent::__construct( 'ld_overall_grade', array(
			'user'               => get_current_user_id(),
			'gradebook'          => false,
			'logged_out_message' => __( 'Please log in to view your overall grade.', 'learndash-gradebook' ),
		) );
	}

	/**
	 * Outputs the shortcode.
	 *
	 * @since 1.6.4
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return mixed
	 */
	function shortcode( $atts = array(), $content = '' ) {

        $this->default_atts( $atts );
        
        if ( ! $atts['gradebook'] ) {
            return __( 'You must define a Gradebook ID.', 'learndash-gradebook' );
        }

		if ( ! is_user_logged_in() ) {
			return $this->atts['logged_out_message'];
		}

		// Get user
		if ( ! ( $user = get_user_by( 'id', $this->atts['user'] ) ) ) {
			return __( 'Cannot get user.', 'learndash-gradebook' );
		}

		$gradebook_id = $this->atts['gradebook'];

		ob_start();

        if ( ! get_post( $gradebook_id ) ) {

            ld_gb_locate_template( 'overall-grade/overall-grade-error.php', array(
                'gradebook_id' => $gradebook_id,
            ) );
            
        }
        else {

            $user_grade = new LD_GB_UserGrade( $user, $gradebook_id );

            // Allow the theme to load the template instead of the plugin
            ld_gb_locate_template( 'overall-grade/overall-grade.php', array(
                'user_grade' => $user_grade,
                'gradebook_id' => $gradebook_id,
            ) );

        }

		return ob_get_clean();
    }
    
}