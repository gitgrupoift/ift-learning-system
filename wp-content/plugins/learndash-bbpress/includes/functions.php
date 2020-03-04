<?php

//restrict participation in forum ( replies & topic creation )
add_filter('bbp_current_user_can_publish_topics', 'ld_restrict_forum_participation');
add_filter('bbp_current_user_can_publish_replies', 'ld_restrict_forum_participation');
function ld_restrict_forum_participation( $can_post ){
	
	// Always allow keymasters
	if ( bbp_is_user_keymaster() ){
		return true;
	}

	$forum_id = bbp_get_forum_id();
	$user_id = get_current_user_ID();
	
	$associated_courses = get_post_meta( $forum_id, '_ld_associated_courses', true );
	$allow_post = get_post_meta( $forum_id, '_ld_post_limit_access', true );
	$allow_forum_view = get_post_meta( $forum_id, '_ld_allow_forum_view', true );
	
	if ( current_user_can( 'publish_topics' ) ) {
		$can_post = true;

		if ( is_array( $associated_courses ) ) {
			foreach( $associated_courses as $associated_course ) {	
				// Default expected value of $allow_post == 'all'
				if ( $allow_post == 'all' ) {
					if ( ! sfwd_lms_has_access( $associated_course, $user_id ) || ! is_user_logged_in() ) {
						return false;
					}
				} else {
					if ( sfwd_lms_has_access( $associated_course, $user_id ) && is_user_logged_in() ) {
						$access_found = true;
						break;
					} else {
						$can_post = false;
					}
				}
			}

			if ( isset( $access_found ) && $access_found ) {
				$can_post = true;
			}
		}
	}
	
	else{
		$can_post = false;
	}

	return $can_post;
}

//disable topic subscription & favorite link for users except course students.
add_filter( 'bbp_get_user_subscribe_link', 'ld_disable_topic_subscription', 10, 4);
add_filter( 'bbp_get_user_favorites_link', 'ld_disable_topic_subscription', 10, 4);
function ld_disable_topic_subscription( $html, $r, $user_id, $topic_id ) {
	// Always allow keymasters
	if ( bbp_is_user_keymaster() ){
		return $html;
	}

	$forum_id = bbp_get_forum_id();
	$user_id = get_current_user_ID();
	
	$associated_courses = get_post_meta( $forum_id, '_ld_associated_courses', true );
	$allow_post = get_post_meta( $forum_id, '_ld_post_limit_access', true );
	$allow_forum_view = get_post_meta( $forum_id, '_ld_allow_forum_view', true );
	
	$can_subscribe = true;

	if ( is_array( $associated_courses ) ) {
		foreach( $associated_courses as $associated_course ) {	
			// Default expected value of $allow_post == 'all'
			if ( $allow_post == 'all' ) {
				if ( ! sfwd_lms_has_access( $associated_course, $user_id ) || ! is_user_logged_in() ) {
					return '';
				}
			} else {
				if ( sfwd_lms_has_access( $associated_course, $user_id ) && is_user_logged_in() ) {
					$access_found = true;
					break;
				} else {
					$can_subscribe = false;
				}
			}
		}

		if ( isset( $access_found ) && $access_found ) {
			$can_subscribe = true;
		}
	}

	if ( $can_subscribe ) {
		return $html;
	} else {
		return '';
	}
}

// Restrict access to forum & topics completely & show take course message in place

add_filter( 'bbp_user_can_view_forum', 'ld_restrict_forum_access', 15, 3 );
function ld_restrict_forum_access( $retval, $forum_id, $user_id ){
	// Always allow keymasters
	if ( bbp_is_user_keymaster() ){
		return true;
	}
	
	$associated_courses = get_post_meta( $forum_id, '_ld_associated_courses', true );

	$allow_post = get_post_meta( $forum_id, '_ld_post_limit_access', true );
	$allow_forum_view = get_post_meta( $forum_id, '_ld_allow_forum_view', true );
	$message_without_access = get_post_meta( $forum_id, '_ld_message_without_access', true );

	$has_access = true;

	if ( is_array( $associated_courses ) && ! empty( $associated_courses ) ) {
		$has_access = true;

		foreach( $associated_courses as $associated_course ) {	
			// Default expected value of $allow_post == 'all'
			if ( $allow_post == 'all' ) {
				if ( ! sfwd_lms_has_access( $associated_course, $user_id ) || ! is_user_logged_in() ) {
					$has_access = false;
					break;
				}
			} else {
				if ( sfwd_lms_has_access( $associated_course, $user_id ) && is_user_logged_in() ) {
					$has_access = true;
					break;
				} else {
					$has_access = false;
				}
			}
		}
	}
	
	$go_back_url = isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : home_url();

	$content = "<div id='bbpress-forums' class='ld-bbpress-forums'>
					<p class='pre-message'>" . $message_without_access . "</p>
				</div>";

	if ( $allow_forum_view == '1' ) {
		$has_access = true;
	}
	
	if ( $has_access === false ) {
		$retval = false;
		echo apply_filters('ld_forum_access_restricted_message', $content, $forum_id, $associated_courses );
	}
	return $retval;
}

//show associated course below the forum title in forum archive
add_action( 'bbp_theme_after_forum_description', 'ld_associated_course_link' );
function ld_associated_course_link(){
	$content = "<span class='ld-bbpress-desc-link'><small><strong>" . __( 'Associated Courses', 'learndash-bbpress' ) . ":</strong>";
	$courses = get_post_meta(get_the_ID(), '_ld_associated_courses', true);
	if(is_array($courses)){
		foreach($courses as $course_id){
			if($course_id != null && $course_id > 0)
			$content .= "<br /><a href='".get_permalink($course_id)."'>".get_the_title($course_id)."</a>";
		}
	}
	
	$content .= "</small></span>";
	if(!empty($courses))
	echo $content;
}

//remove repetation of private twice in private forum titles
add_filter( 'bbp_get_forum_title', 'wdm_title', 10, 2 );
function wdm_title( $title, $forum_id){
	return str_replace("Private:","", $title);
}

// Assign participant forum role to new students
add_action( 'learndash_update_course_access', 'ld_bbp_assign_role', 10, 4 );
function ld_bbp_assign_role( $user_id, $course_id, $access_list, $remove ) {
	if ( true === $remove ) {
		return;
	}

	$role = bbp_get_user_role( $user_id );
	if ( empty( $role ) || false === $role || 'bbp_spectator' === $role ) {
		bbp_set_user_role( $user_id, 'bbp_participant' );
	}
}