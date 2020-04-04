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
        add_action('admin_menu', array($this, 'admin_main'), 999);
        
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
    
    public function admin_main() {
        
        global $menu;
        global $current_user;
        get_currentuserinfo();

        if($current_user->user_login !== 'carlos')
        {
            remove_menu_page('tools.php');
            remove_menu_page('themes.php');
            remove_menu_page('options-general.php');
            remove_menu_page('plugins.php');
            remove_menu_page('edit-comments.php');
            remove_menu_page('page.php');
            remove_menu_page('upload.php');
            remove_menu_page( 'edit.php?post_type=page' ); 
            remove_menu_page( 'edit.php?post_type=videos' );
            remove_menu_page( 'edit.php' );
            remove_menu_page( 'pwaforwp' );
            remove_menu_page( 'moove-gdpr' );
            remove_menu_page( 'uncanny-toolkit' );
            remove_menu_page( 'edit.php?post_type=acf-field-group' );
            remove_menu_page( 'elementor' );
            remove_menu_page( 'loco' );
            remove_menu_page( 'edit.php?post_type=elementor_library' );
            remove_menu_page( 'wpforms-overview' );

        }
    }

    
}