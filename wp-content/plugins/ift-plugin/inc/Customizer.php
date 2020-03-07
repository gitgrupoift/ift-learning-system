<?php

namespace IFT;

if ( ! class_exists( 'Astra_Customizer_Config_Base' ) ) {
	return;
}

class Customizer  {
    
    private static $instance;
    
    private $wp_customize;

	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
		}
			return self::$instance;
    }
    
    public function __construct() {
        
        add_action( 'after_setup_theme', array( $this, 'customizer_settings_extra') );

    }
    
    function customizer_settings_extra( $wp_customize ) {
        
        $wp_customize->add_section( 'auth_data' , array(
            'title'      => 'Chaves e Tokens',
            'priority'   => 130,
        ) );
        
        
    }
    
}