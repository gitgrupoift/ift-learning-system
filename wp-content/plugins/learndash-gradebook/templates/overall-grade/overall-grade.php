<?php
/**
 * Template for the Overall Grade
 *
 * @since 1.6.4
 *
 * @var LD_GB_UserGrade $user_grade
 * @var integer			$gradebook_id
 */

defined( 'ABSPATH' ) || die();

?>

<div class="ld-gb-overall-grade">
    <?php $user_grade->display_user_grade(); ?>
</div>