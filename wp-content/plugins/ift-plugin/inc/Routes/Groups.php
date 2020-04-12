<?php

namespace IFT\Routes;

class Groups {
    
    public function __construct() {  
        
        add_action( 'rest_api_init', array($this, 'routes'));
            
    }
    
    public function routes() {
        
        register_rest_route(
            'groups/v1',
            '/get',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_groups')
            )
        );
        
        register_rest_route(
            'groups/v1',
            '/post',
            array(
                'methods' => 'POST',
                'callback' => array($this, 'post_groups')
            )
        );
        
    }
    
    protected function get_groups() {}
    
    protected function post_groups() {}
    
}