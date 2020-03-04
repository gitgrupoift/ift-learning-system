<?php

/**
 * Add sidebars to the appropriate LearnDash do_action hooks.
 *
 * @copyright Copyright (c) 2019, Escape Creative, LLC
 * @license   GPL2+
 */

/**
 * Focus Mode: Sidebar: Before Navigation
 *
 * @since 1.0
 */
function ldxw_focus_sidebar_between_heading_navigation( $course_id, $user_id ) {

	if ( is_active_sidebar( 'ldxw_fm_nav_before' ) ) { ?>

		<div class="ldx-widget-area fm-nav-before">
			<?php dynamic_sidebar( 'ldxw_fm_nav_before' ); ?>
		</div>

	<?php }

}
add_action( 'learndash-focus-sidebar-between-heading-navigation', 'ldxw_focus_sidebar_between_heading_navigation', 10, 2 );


/**
 * Focus Mode: Sidebar: After Navigation
 * 
 * @since 1.0
 */
function ldxw_focus_sidebar_after_nav_wrapper( $course_id, $user_id ) {

	if ( is_active_sidebar( 'ldxw_fm_nav_after' ) ) { ?>

		<div class="ldx-widget-area fm-nav-after">
			<?php dynamic_sidebar( 'ldxw_fm_nav_after' ); ?>
		</div>

	<?php }

}
add_action( 'learndash-focus-sidebar-after-nav-wrapper', 'ldxw_focus_sidebar_after_nav_wrapper', 10, 2 );


/**
 * Focus Mode: Content: Start
 * 
 * @since 1.0
 */
function ldxw_focus_content_title_before( $course_id, $user_id ) {

	if ( is_active_sidebar( 'ldxw_fm_content_start' ) ) { ?>

		<div class="ldx-widget-area fm-content-start">
			<?php dynamic_sidebar( 'ldxw_fm_content_start' ); ?>
		</div>

	<?php }

}
add_action( 'learndash-focus-content-title-before', 'ldxw_focus_content_title_before', 10, 2 );


/**
 * Focus Mode: Below Content
 * 
 * @since 1.0
 */
function ldxw_focus_content_content_after( $course_id, $user_id ) {

	if ( is_active_sidebar( 'ldxw_fm_content_bottom' ) ) { ?>

		<div class="ldx-widget-area fm-content-bottom">
			<?php dynamic_sidebar( 'ldxw_fm_content_bottom' ); ?>
		</div>

	<?php }

}
add_action( 'learndash-focus-content-content-after', 'ldxw_focus_content_content_after', 10, 2 );


/**
 * Course Page: Content: Start
 * 
 * @since 1.0
 */
function ldxw_learndash_course_before( $course_id, $user_id ) {

	if ( is_active_sidebar( 'ldxw_course_start' ) ) { ?>

		<div class="ldx-widget-area course-content-start">
			<?php dynamic_sidebar( 'ldxw_course_start' ); ?>
		</div>

	<?php }

}
add_action( 'learndash-course-before', 'ldxw_learndash_course_before', 10, 2 );


/**
 * Course Page: Content: End
 * 
 * @since 1.0
 */
function ldxw_learndash_course_after( $course_id, $user_id ) {

	if ( is_active_sidebar( 'ldxw_course_end' ) ) { ?>

		<div class="ldx-widget-area course-content-end">
			<?php dynamic_sidebar( 'ldxw_course_end' ); ?>
		</div>

	<?php }

}
add_action( 'learndash-course-after', 'ldxw_learndash_course_after', 10, 2 );
