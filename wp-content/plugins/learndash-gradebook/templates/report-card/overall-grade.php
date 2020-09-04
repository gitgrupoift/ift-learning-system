<?php
/**
 * Report card overall grade.
 *
 * @since 1.1.0
 *
 * @var LD_GB_UserGrade $user_grade
 * @var int $gradebook_id
 */

defined( 'ABSPATH' ) || die();
?>

<div id="ld-gb-report-card-overall" class="ld-gb-report-card-overall">
	<div class="ld-gb-report-card-section-info">
		<span class="ld-gb-report-card-section-title">
			<?php _e( 'Overall Grade', 'learndash-gradebook' ); ?>
		</span>

		<span class="ld-gb-report-card-section-grade"
		      style="background-color: <?php $user_grade->display_user_grade_color(); ?>;">
			<?php echo $user_grade->display_user_grade(); ?>
		</span>
	</div>
</div>