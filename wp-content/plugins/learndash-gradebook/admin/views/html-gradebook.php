<?php
/**
 * HTMl for the Gradebook reports page.
 *
 * @since 1.0.0
 *
 * @var LD_GB_GradebookListTable $gradebook The table object for the gradebook report.
 * @var string|bool $hide_rows
 * @var array $gradebook_options
 * @var array $group_options
 * @var string $active_gradebook
 * @var string $active_group_ID
 */

defined( 'ABSPATH' ) || die();
?>

<?php settings_errors(); ?>

    <form method="get">

        <input type="hidden" name="page" value="learndash-gradebook"/>

		<?php if ( ! LD_GB_QuickStart::inside_quickstart() ) : ?>

			<?php $gradebook->search_box( __( 'Search Users', 'learndash-gradebook' ), 'gradebook-users' ); ?>

			<?php $gradebook->gradebook_select( $gradebook_options, $active_gradebook ); ?>


			<?php if ( ! empty( $group_IDs ) ) : ?>

				<?php $gradebook->group_select( $group_IDs, $active_group_ID ); ?>

			<?php endif; ?>

		<?php endif; ?>
        
    </form>

<?php $gradebook->display(); ?>