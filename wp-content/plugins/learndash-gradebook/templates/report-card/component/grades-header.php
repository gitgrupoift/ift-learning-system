<?php
/**
 * Report card component grades table header.
 *
 * @since 1.1.0
 *
 * @var array $component
 * @var LD_GB_UserGrade $user_grade
 * @var int $gradebook_id
 */

defined( 'ABSPATH' ) || die();
?>

<th class="ld-gb-report-card-grades-column-type">
	<?php _e( 'Type', 'learndash-gradebook' ); ?>
</th>

<th class="ld-gb-report-card-grades-column-name">
	<?php _e( 'Name', 'learndash-gradebook' ); ?>
</th>

<th class="ld-gb-report-card-grades-column-score">
	<?php _e( 'Score', 'learndash-gradebook' ); ?>
</th>