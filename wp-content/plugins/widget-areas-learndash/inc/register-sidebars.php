<?php

/**
 * Register all the sidebars that we'll be using.
 *
 * @copyright Copyright (c) 2019, Escape Creative, LLC
 * @license   GPL2+
 */

/**
 * 1. Focus Mode: Sidebar: Before Navigation
 * 2. Focus Mode: Sidebar: After Navigation
 * 3. Focus Mode: Content: Start
 * 4. Focus Mode: Below Content
 * 5. Focus Mode: Content: End (coming soon)
 * 6. Course Page: Start
 * 7. Course Page: End
 */

add_action( 'widgets_init', 'ldxw_register_sidebars' );

function ldxw_register_sidebars() {

	/* 1. Focus Mode: Sidebar: Before Navigation */
	register_sidebar(
		array(
			'id'            => 'ldxw_fm_nav_before',
			'name'          => __( 'LD Focus Mode: Sidebar: Before Navigation', 'widget-areas-learndash' ),
			'description'   => __( 'In the sidebar of all Focus Mode pages, just before the navigation.', 'widget-areas-learndash' ),
			'before_widget' => '<div id="%1$s" class="ldx-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

	/* 2. Focus Mode: Sidebar: After Navigation */
	register_sidebar(
		array(
			'id'            => 'ldxw_fm_nav_after',
			'name'          => __( 'LD Focus Mode: Sidebar: After Navigation', 'widget-areas-learndash' ),
			'description'   => __( 'At the bottom of the sidebar of all Focus Mode pages, after the navigation.', 'widget-areas-learndash' ),
			'before_widget' => '<div id="%1$s" class="ldx-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

	/* 3. Focus Mode: Content: Start */
	register_sidebar(
		array(
			'id'            => 'ldxw_fm_content_start',
			'name'          => __( 'LD Focus Mode: Content: Start', 'widget-areas-learndash' ),
			'description'   => __( 'At the very top of Focus Mode page content, directly above the page title.', 'widget-areas-learndash' ),
			'before_widget' => '<div id="%1$s" class="ldx-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

	/* 4. Focus Mode: Below Content */
	register_sidebar(
		array(
			'id'            => 'ldxw_fm_content_bottom',
			'name'          => __( 'LD Focus Mode: Below Content', 'widget-areas-learndash' ),
			'description'   => __( 'Below all Focus Mode content. In between next/previous navigation &amp; comments (if enabled).', 'widget-areas-learndash' ),
			'before_widget' => '<div id="%1$s" class="ldx-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

	/* 6. Course Page: Start */
	register_sidebar(
		array(
			'id'            => 'ldxw_course_start',
			'name'          => __( 'LD Course: Content: Start', 'widget-areas-learndash' ),
			'description'   => __( 'At the very top of course pages, just below the page title & before any course status or content.', 'widget-areas-learndash' ),
			'before_widget' => '<div id="%1$s" class="ldx-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

	/* 7. Course Page: End */
	register_sidebar(
		array(
			'id'            => 'ldxw_course_end',
			'name'          => __( 'LD Course: Content: End', 'widget-areas-learndash' ),
			'description'   => __( 'At the very bottom of course pages, after the course content listing.', 'widget-areas-learndash' ),
			'before_widget' => '<div id="%1$s" class="ldx-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

} // ldxw_register_sidebars()