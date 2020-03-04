<?php

namespace IFT;

class Optimize {

    private static $instance;

	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
		}
			return self::$instance;
    }
    
    public function __construct() {   
        
        add_filter( 'script_loader_src', array($this, '_remove_script_version'), 15, 1 );
        add_filter( 'style_loader_src', array($this, '_remove_script_version'), 15, 1 );
        
        $this->wp_head_cleanup();
        
    }
    
    public function wp_head_cleanup() {
        
        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action('wp_head', 'feed_links', 2);
        remove_action('wp_head', 'feed_links_extra', 3);
        remove_action('wp_head', 'start_post_rel_link');
        remove_action('wp_head', 'index_rel_link');
        remove_action('wp_head', 'adjacent_posts_rel_link');
        
    }
    
    public function _remove_script_version( $src ){ 
        $parts = explode( '?', $src ); 	
        return $parts[0]; 
    } 

}