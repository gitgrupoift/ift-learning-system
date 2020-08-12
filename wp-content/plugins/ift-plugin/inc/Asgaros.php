<?php

namespace IFT;

class Asgaros {
    
    public function __construct() {
        
        add_action('asgarosforum_after_forum', array($this, 'forum_to_course_resume'));
        add_action('asgarosforum_after_topic', array($this, 'forum_to_course_resume'));
        // Notificações - fix a partir da versão 1.7.0
        add_action('asgarosforum_after_add_post_submit', array($this, 'adding_reply_notice'), 10, 6);
        add_action('asgarosforum_after_add_topic_submit', array(&$this, 'adding_topic_notice'), 10, 6);

    }

    
    public function forum_to_course_resume() {
        echo do_shortcode('[ld_course_resume]');
    }
    /*
     * @since 1.7.0
     * @desc Associa notificação a cada nova resposta a um tópico
     *
     */
    public function adding_reply_notice($post_id, $topic_id, $subject, $content, $link, $author_id) {
        
        global $asgarosforum;
        
    }
    
    public function adding_topic_notice($post_id, $topic_id, $subject, $content, $link, $author_id) {
        
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