<?php
/**
 * Report card title.
 *
 * @since 1.1.0
 *
 * @var LD_GB_UserGrade $user_grade
 * @var int $gradebook_id
 */

defined( 'ABSPATH' ) || die();
?>

<div class="ld-gb-report-card-title">
	<?php
	printf( __( 'Report Card for: %s', 'learndash-gradebook' ),
		esc_attr( $user_grade->get_gradebook()->post_title )
	);
	?>
</div>