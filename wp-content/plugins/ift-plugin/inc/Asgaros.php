<?php

namespace IFT;

class Asgaros {
    
    public function __construct() {
        
        add_action('asgarosforum_after_forum', array($this, 'forum_to_course_resume'));
        add_action('asgarosforum_after_topic', array($this, 'forum_to_course_resume'));

    }

    
    public function forum_to_course_resume() {
        echo do_shortcode('[ld_course_resume]');
    }
    

/* Actions Asgaros

asgarosforum_after_post_author
asgarosforum_after_post_message
asgarosforum_after_post

asgarosforum_after_category

asgarosforum_content_top
asgarosforum_content_header
asgarosforum_bottom_navigation
asgarosforum_content_bottom

asgarosforum_after_forum
asgarosforum_after_topic
asgarosforum_custom_topic_column{$topic_object->id}

asgarosforum_before_delete_topic
asgarosforum_after_delete_topic
asgarosforum_before_delete_post
asgarosforum_after_delete_post

asgarosforum_custom_profile_content
asgarosforum_profile_row
asgarosforum_custom_profile_menu

SQL Statements

Foruns (id, name, parent_id, parent_forum, description, icon, sort, forum_status)


*/
}