<?php

/**
 * Archive Forum Content Part
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

?>

<style>
    .entry-title {display: none;}
</style>

<div id="bbpress-forums" class="bbpress-wrapper">

	<?php bbp_get_template_part( 'form', 'search' ); ?>

	<div class="ld-breadcrumbs"><div class="ld-breadcrumbs-segments"><?php bbp_breadcrumb(); ?></div></div>

	<?php bbp_forum_subscription_link(); ?>

	<?php do_action( 'bbp_template_before_forums_index' ); ?>

	<?php if ( bbp_has_forums() ) : ?>

		<?php bbp_get_template_part( 'loop',     'forums'    ); ?>

	<?php else : ?>

		<?php bbp_get_template_part( 'feedback', 'no-forums' ); ?>

	<?php endif; ?>

	<?php do_action( 'bbp_template_after_forums_index' ); ?>

</div>