<?php

namespace IFT;

class Backend {
    
    private static $instance;

	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
		}
			return self::$instance;
    }
    
    public function __construct() {
        
        add_action('wp_enqueue_scripts', array($this, 'front_menu_admin'));
        add_action('admin_head', array($this, 'admin_tweak_css'));
        
        remove_role('bbp_blocked');
        remove_role('bbp_participant');
        remove_role('bbp_moderator');
        remove_role('bbp_spectator');
        remove_role('bbp_keymaster');
        
    }
    
    public function front_menu_admin() {
        
        if( current_user_can('administrator') || current_user_can('lecturer') ) {
            
        } else {
            $menu_admin = '.menu-admin-front {display: none;}';
            wp_add_inline_style('astra-theme-css-inline', $menu_admin);
        }
    }

    public function admin_tweak_css() {
      echo '<style>
        .woocommerce-store-alerts {display: none;}
      </style>';
    }

    
}