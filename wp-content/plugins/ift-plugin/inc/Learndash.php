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
        
    }
    
    // Habilita comentários que, por defeito, não estão presente no modo foco do Learndash
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

}