<?php

namespace IFT;

class Learndash {

    private static $instance;
    
    /*
    * Custom Post Types a serem implementados com contagem de horas
    * $timed_post_types     Array
    */
    public static $timed_post_types = array( 'sfwd-courses', 'sfwd-lessons', 'sfwd-topic', 'sfwd-quiz' );

	public static function get_instance() {
        
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
		}
			return self::$instance;
        
    }
    
    public function __construct() {
        
        add_action( 'get_header', array( $this, 'enable_comments' ) );
        
    }
    
    // Habilita comentários que, por defeito, não estão presente no modo foco do Learndash
    function enable_comments() {
        
        remove_filter( 'comments_array', 'learndash_remove_comments', 1, 2 );
        remove_filter('comments_open', 'learndash_comments_open', 10, 2);
        
    }


    
}