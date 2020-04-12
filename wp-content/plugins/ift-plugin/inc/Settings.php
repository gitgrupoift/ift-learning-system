<?php

namespace IFT;

class Settings {
    
    public function __construct() {
        
        if ( is_admin() ) {
            add_action('admin_menu', array($this, 'add_admin_menu'));
        }
        
    }
    
    public static function get_options() {}
    
    public static function get_option( $id ) {}
    
    public static function add_admin_menu() {
        
        add_menu_page(
            esc_html__( 'Integrações', 'ift-plugin' ),
            esc_html__( 'Integrações', 'ift-plugin' ),
            'manage_options',
            'ift-settings',
            array( $this, 'create_admin_page' )
        );
        
    }
    
    public static function register_settings() {}
    
    public static function sanitize( $options ) {}
    
    public function create_admin_page() {
        ?>

        <div class="wrap">
            <h1><?php esc_html_e( 'Integrações e Definições - IFT Learning', 'ift-plugin' ); ?></h1>
            <hr>
        </div>

        <?php
    }
    
}