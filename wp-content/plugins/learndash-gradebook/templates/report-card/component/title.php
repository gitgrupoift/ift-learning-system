<?php
/**
 * Report card component title.
 *
 * @since 1.1.0
 * @updated 1.6.0
 *
 * @var array $component
 * @var LD_GB_UserGrade $user_grade
 * @var int $gradebook_id
 * @var string $compontent_handle
 */

defined( 'ABSPATH' ) || die();

if ( $user_grade->get_weight_type() == 'weighted' ) {

	/* translators: %1$s is the component name, %2$d is the component weight */
	$name = sprintf( __( '%1$s (Weight - %2$d%%)', 'learndash-gradebook' ),
		esc_attr( $component['name'] ),
		$component['weight']
	);
} else {

	$name = esc_attr( $component['name'] );
}
?>

<span class="ld-gb-report-card-section-title"
      onclick='return flip_expand_collapse("#ld-gb-report-card-<?php echo $gradebook_id; ?>-component", "<?php echo $component_handle; ?>");'>
	<?php echo $name; ?>
</span>


