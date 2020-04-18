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
                'args' => array(
                    'keyword' => array(
                        'description' => __( '(opcional) Termo ou termos de busca nos grupos.' ),
                        'type'        => 'string',
                    ),
                    'course' => array(
                        'description' => __( '(opcional) Busca por formação associada ao grupo.' ),
                        'type'        => 'string',
                    ),
                    'actor' => array(
                        'description' => __( '(opcional) Procura os grupos a partir do formando, formador ou líder presente nestes grupos.' ),
                        'type'        => 'string',
                    ),
                ),
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
                'args' => array(
                    'id' => array(
                        'description' => __( 'ID do grupo a atualizar.', 'ift-plugin' ),
                        'type'        => 'integer',
                    ),
                ),
                'callback' => array($this, 'edit_group')
            )
        );
        
        register_rest_route(
            'lrs/v1',
            '/delete_group/(?P<id>\d+)',
            array(
                'methods' => 'DELETE',
                'args' => array(
                    'id' => array(
                        'description' => __( 'ID do grupo a apagar.', 'ift-plugin' ),
                        'type'        => 'integer',
                    ),
                ),
                'callback' => array($this, 'delete_group')
            )
        );
        

        
    }
    
    protected function get_groups($request) {
        
        $posts_list = get_posts( array( 
                'post_type'     => 'groups', 
                'numberposts'   => -1,
                's'             => $_GET['keyword']
            ));
        
    }
    
    protected function post_groups($request) {}
    
    protected function add_group($request) {}
    
    protected function edit_group($request) {}
    
    protected function delete_group($request) {}

    
}