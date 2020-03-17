<?php

namespace IFT;

class Rest {

    private static $instance;

	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
		}
			return self::$instance;
    }
    
    public function __construct() {
        

    }
    
    function api_prefix_change( $slug ) {
        
        
    }
    
 

}