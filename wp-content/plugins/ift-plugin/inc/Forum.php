<?php

namespace IFT;

use AsgarosForum;
use AsgarosForumUserGroups;

class Forum {
    
    private $prefix;
    private $forum_table;

    public function __construct() {
        
        global $wpdb;        
        $this->prefix = $wpdb->prefix;
        $this->forum_table = $this->prefix . 'forum_forums';
        
        add_action( 'publish_sfwd-courses', array($this, 'forum_category_for_course') );
        add_action( 'publish_groups', array($this, 'forum_usergroup_for_group') );
        add_action( 'after_forum_category_creation', array($this, 'forum_course_steps_to_topics') );
        
        
    }
    
    public function forum_category_for_course($post_id) {
        
        $types = get_post_type($post_id);
        
        if ( $types !== 'sfwd-courses' ) {
            return;
        }
        
        $group= get_the_title($post_id);
        
        if (!term_exists($group, 'asgarosforum-category')) {
            wp_insert_term($group, 'asgarosforum-category');
        }
        
    }
    
    public function forum_usergroup_for_group($post_id) {
        
        $types = get_post_type($post_id);
        
        if ( $types !== 'groups' ) {
            return;
        }
        
        $group= get_the_title($post_id);
        
        $new_group = wp_insert_term(
            $group, 
            'asgarosforum-usergroup',
            array(
                'parent' => 234
            )
        );
        
    }
    
    public function forum_course_steps_to_topics($post_id) {
        
        $types = get_post_type($post_id);
        
        if ( $types !== 'sfwd-courses' ) {
            return;
        }
        
        $lessons = learndash_get_course_lessons_list($post_id);
        
        if ( ( is_array( $lessons ) )  && ( ! empty( $lessons ) ) ) {
				// Loop course's lessons.
				foreach ( $lessons as $lesson ) {
                    $lesson['post']->post_title;
                }
        }
        
    }
    
    // Início de função para inserir utilizadores nos grupos do fórum, com base no ingresso em grupos do LEarndash
    public function add_users_to_forum_group($group_id, $asgaros_group_id) {
        
        $group_users = learndash_get_groups_user_ids($group_id);
        
        foreach($group_users as $user_id) {
            $this->add_user_to_forum_group($user_id, $asgaros_group_id);
        }
            
    }
    
    // Adicionará um utilizador específico a um grupo específico do fórum
    public function add_user_to_forum_group($user_id, $asgaros_group_id) {
        
    }
    
    // Função designada a criar novos fóruns
    public function add_forum($forum_id, $category_id, $name, $description, $parent_forum, $icon, $order, $status = 'normal') {}
    
}