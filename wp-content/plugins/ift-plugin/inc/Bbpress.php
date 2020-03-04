<?php

namespace IFT;

class Bbpress {

    private static $instance;

	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
		}
			return self::$instance;
    }
    
    public function __construct() {
                
        add_filter( 'bbp_after_get_the_content_parse_args', array( $this, 'bbp_enable_visual_editor') );
        //add_filter( 'bbp_before_get_breadcrumb_parse_args', array( $this, 'bbpress_custom_breadcrumb') );
        
        add_action( 'wp_enqueue_scripts', array( $this, 'bb_css_remove') );
        
    }
    
    // Habilita o editor do Wordpress por defeito nos replies dos fóruns
    function bbp_enable_visual_editor( $args = array() ) {
        $args['tinymce'] = true;
        return $args;
    }
    
    // Remove o CSS do BBpress se a página não é de forum
    function bb_css_remove() {
        $classes = get_body_class();
        
        if (in_array('bbpress',$classes)) {
            
        } else {
            wp_deregister_style('bbp-default');
        }
    }
    
    // Modifica os "breadcrumbs" por defeito do BBpress
    function bbpress_custom_breadcrumb() {
	    // HTML
        $args['before']          = '<div class="learndash-wrapper" style="line-height:20px !important;"><div class="ld-status">';
        $args['after']           = '</p></div></div>';

        return $args;
    }
    
    
}