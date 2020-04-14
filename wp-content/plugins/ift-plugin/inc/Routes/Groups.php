<?php

namespace IFT\Routes;

class Groups {
    
    public function __construct() {  
        
        add_action( 'rest_api_init', array($this, 'routes'));
            
    }
    
    public function routes() {
        
        register_rest_route(
            'lrs/v1',
            '/get_groups',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_groups')
            )
        );
        
        register_rest_route(
            'lrs/v1',
            '/post_groups',
            array(
                'methods' => 'POST',
                'callback' => array($this, 'post_groups')
            )
        );
        
        register_rest_route(
            'lrs/v1',
            '/add_group',
            array(
                'methods' => 'POST',
                'callback' => array($this, 'add_group')
            )
        );
        
        register_rest_route(
            'lrs/v1',
            '/edit_group/(?P<id>\d+)',
            array(
                'methods' => 'PUT',
                'callback' => array($this, 'edit_group')
            )
        );
        
        register_rest_route(
            'lrs/v1',
            '/delete_group/(?P<id>\d+)',
            array(
                'methods' => 'DELETE',
                'callback' => array($this, 'delete_group')
            )
        );
        
        register_rest_route(
            'lrs/v1',
            '/zoom/(?P<code>\d+)',
            array(
                'methods' => 'POST',
                'callback' => array($this, 'zoom')
            )
        );
        
    }
    
    protected function get_groups() {}
    
    protected function post_groups() {}
    
    protected function add_group() {}
    
    protected function edit_group() {}
    
    protected function delete_group() {}
    
    protected function zoom() {}
    
}