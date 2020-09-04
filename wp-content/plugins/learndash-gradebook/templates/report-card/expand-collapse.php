<?php
/**
 * Report card expand/collapse.
 *
 * @since 1.1.0
 * @updated 1.6.0
 *
 * @var LD_GB_UserGrade $user_grade
 * @var int             $gradebook_id
 */

defined( 'ABSPATH' ) || die();
?>

<div class="expand_collapse">
	<a href="#" onclick='return flip_expand_all("#ld-gb-report-card-<?php echo $gradebook_id; ?>-component-list");'>
        <?php _e( 'Expand All', 'learndash-gradebook' ); ?>
    </a>&nbsp;|&nbsp;<a href="#" onclick='return flip_collapse_all("#ld-gb-report-card-<?php echo $gradebook_id; ?>-component-list");'>
        <?php _e( 'Collapse All', 'learndash-gradebook' ); ?>
    </a>
</div>
