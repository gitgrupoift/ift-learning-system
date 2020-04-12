<?php

namespace IFT;
use IFT\Routes\Questions;
use IFT\Routes\Groups;
use IFT\Routes\Topics;

class Rest {

    private static $instance;
    public $api_base;

	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
		}
			return self::$instance;
    }
    
    public function __construct() {
        
        $this->api_base = 'ift-learning';
        
        add_filter( 'rest_url_prefix', array($this, 'api_prefix_change'));
        
        new Questions();
        new Groups();
        new Topics();

    }
    
    function api_prefix_change( $slug ) {
        
        return $this->api_base;
        
    }
    
 

}