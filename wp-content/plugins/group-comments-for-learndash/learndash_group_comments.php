<?php
/*
Plugin Name: Group Comments for LearnDash
Plugin URI: http://learning-templates.com/index.php/product/dash-group-comments/
Description: LearnDash Group Comments filters comments by LearnDash Group. Activating this plugin will pick up the WordPerss Discussion settings and the LearnDash Groups restrictions and combine them to filter user comments by group. Check out <a href="http://learning-templates.com">out other LearnDash Group plugins</a> at Learning Templates.
Version: 1.0.1
Author: Dennis Hall
Author URI: http://learning-templates.com
License: GPLv2 or later.
*/

if(!function_exists('lcr_init')):
function lcr_init() {
	// read filters
	add_filter( 'comments_array','lcr_comments_array' , 10, 2 );
}
endif;
add_action( 'init', 'lcr_init' );
if(!function_exists('lcr_comments_array')):
function lcr_comments_array( $comments, $post_id ) {
	 $result = array();
	 foreach($comments as $comment){
		$commenter_id	=	$comment->user_id;
		if ( lcr_user_can_read_comments( get_current_user_id(), $post_id ,$commenter_id) ) {
			$result[] 	= 	$comment;
		}
	}
	return $result;
}
endif;
if(!function_exists('lcr_user_can_read_comments')):
function lcr_user_can_read_comments( $user_id = null, $post_id = null,$commenter_id = null ) {
		$result = true;
		if ( $user_id === null ) {
			$user_id = get_current_user_id();
		}
		if ( $commenter_id === null ) {
			$result = true;
		}
		$post_type = get_post_type( $post_id );
		//check post types for leardash
		if($post_type == 'sfwd-courses' || $post_type == 'sfwd-lessons' || $post_type == 'sfwd-topic' || 
		$post_type == 'sfwd-quiz' ||
		$post_type == 'sfwd-assignment' ||
		$post_type == 'groups' 
		){
			// check user's comments
			/*
			if ( is_user_logged_in() && ( current_user_can( 'moderate_comments' ) || current_user_can( "publish_${post_type}", $post_id ) ) ) {
				$result = true;
			} 
			*/
			//check for user groups 
			//  group logic: show comments to same group users
			if ( ! function_exists( 'wp_get_current_user' ) ) {
					include ABSPATH . 'wp-includes/pluggable.php';
			}
			$current_user = wp_get_current_user();
			$userid = $current_user->ID;
			if ( ! function_exists( 'learndash_get_users_group_ids' ) ) {
				include_once ( WP_PLUGIN_DIR . '/sfwd-lms/includes/ld-groups.php');
			}
			$my_groups = learndash_get_users_group_ids($userid);
			$ass_users_groups = learndash_get_users_group_ids($commenter_id);
			//skip if group is not the same group the user is in.
			if($ass_users_groups[0]==$my_groups[0]) {
				$result = true;
			} else {
				$result = false;
			}
		}
		return $result;
	}
endif;