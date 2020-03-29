<?php

namespace IFT;

class Learndash {

    private static $instance;

	public static function get_instance() {
        
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
		}
			return self::$instance;
        
    }
    
    public function __construct() {
            
        add_action( 'get_header', array( $this, 'enable_comments' ) );
        
        add_shortcode( 'ld-hours-completed', array($this, 'learndash_course_completed_hours'));
        add_shortcode( 'ld-courses-and-hours', array($this, 'learndash_user_course_enrollment_and_hours'));
        
    }
    
    /**
     * Habilita comentários que, por defeito, não estão presente no modo foco do Learndash.
     *
     * @param   void
     */
    function enable_comments() {
        
        remove_filter( 'comments_array', 'learndash_remove_comments', 1, 2 );
        remove_filter('comments_open', 'learndash_comments_open', 10, 2);
        
    }
    
	public static function dependants_exist() {
        
		global $learndash_post_types;
		if ( ! isset( $learndash_post_types ) ) {
			return 'Plugin: LearnDash';
		}

		return true;
        
	}
    
    public function learndash_course_completed_hours() {
        
        if( is_singular('ld-notification')) {
            $course_id  = get_post_meta( get_the_ID(), '_ld_notifications_course_id', true );            
        } elseif( is_singular('sfwd-courses')) {
            $course_id = get_the_ID();           
        }
    
        $completed_hours = get_post_meta( $course_id, 'course_points', true ) . __(' Horas Concluídas', 'ift-plugin');
        
        return $completed_hours;
    }
    
    public function learndash_user_course_enrollment_and_hours() {
        
        $user_id = get_current_user_id();
        $user_courses = ld_get_mycourses( $user_id );
        
        $item_li = '<ul class="user-course-list">' . PHP_EOL;      
        
        foreach ( $user_courses as $course_item ) {
            
            $is_completed = learndash_course_completed( $user_id, $course_item );           
            $item_hours = get_post_meta( $course_item, 'course_points', true );
            
            $item_li .= '<li class="user-course-list-item">' . get_the_title($course_item);
            
            if ( $item_hours == null ) {                
                 $item_li .= ' | <strong>sem atribuição de horas.</strong>';
                
            } else {                
                $item_li .= ' | <span class="ld-courses-hours">' . $item_hours . '  Horas</span>';
                
                if ($is_completed == true) {
                    $item_li .= ' | <span class="ld-course-finished">Concluído</span>';
                    
                } else {
                    $item_li .= ' | <span class="ld-course-ongoing">Em Curso</span>';
                }
                
            }
            
            $item_li .= '</li>';
                        
        }
        
        
        $item_li .= '</ul>';
        
        return $item_li;
        
    }
    
    public function learndash_user_course_hours() {
        
        $user_id = get_current_user_id();
        $user_courses = ld_get_mycourses( $user_id );
        
        $item_li = '<ul class="user-course-list">' . PHP_EOL;      
        
        foreach ( $user_courses as $course_item ) {
            
            $is_completed = learndash_course_completed( $user_id, $course_item );           
            $item_hours = get_post_meta( $course_item, 'course_points', true );
            
            $item_li .= '<li class="user-course-list-item">' . get_the_title($course_item);
            
            if ( $item_hours == null ) {                
                 $item_li .= ' | <strong>sem atribuição de horas.</strong>';
                
            } else {                
                $item_li .= ' | <span class="ld-courses-hours">' . $item_hours . '  Horas</span>';
                
                if ($is_completed == true) {
                    $item_li .= ' | <strong><span class="ld-course-finished">Concluído</span></strong>';
                    
                } else {
                    $item_li .= ' | <span class="ld-course-ongoing">Em Curso</span>';
                }
                
            }
            
            $item_li .= '</li>';
                        
        }
        
        
        $item_li .= '</ul>';
        
        return $item_li;
        
    }

}