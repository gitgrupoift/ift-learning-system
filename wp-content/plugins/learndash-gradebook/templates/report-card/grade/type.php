<?php
/**
 * Report card grade type.
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

<td class="ld-gb-report-card-grades-column-type">
	<?php echo ld_gb_get_grade_type_name( $grade['type'] ); ?>
</td>