<?php

namespace IFT;

class Ads {

    private static $instance;

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
		}
			return self::$instance;
    }
    
    public function __construct() {

    }

    public function ads_head() {
        
 
    }
    
    public function page_timer() {

        
    }
    
}