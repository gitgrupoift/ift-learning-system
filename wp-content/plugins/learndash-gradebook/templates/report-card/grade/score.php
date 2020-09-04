<?php
/**
 * Report card grade score.
 *
 * @since 1.1.0
 *
 * @var string $grade
 * @var int $grade_i
 * @var array $component
 * @var LD_GB_UserGrade $user_grade
 * @var int $gradebook_id
 */

defined( 'ABSPATH' ) || die();
?>

<td class="ld-gb-report-card-grades-column-score">
	<?php echo $grade['score_display']; ?>
</td>