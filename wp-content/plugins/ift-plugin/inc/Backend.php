<?php

namespace IFT;

use IFT\Helpers\CustomType;

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
        add_action('wp_head', array($this, 'analytics'), 99);
        
        add_action( 'do_meta_boxes', array( $this, 'remove_astra_metabox' ) );
        
        remove_role('bbp_blocked');
        remove_role('bbp_participant');
        remove_role('bbp_moderator');
        remove_role('bbp_spectator');
        remove_role('bbp_keymaster');
        
        $this->create_cpts();
        
    }
    
    public function create_cpts() {
        
        $cpt = new CustomType( array(
            'post_type_name' => 'zoom',
            'singular' => 'Zoom Meeting',
            'plural' => 'Zoom Meeting',
            'slug' => 'zoom',
            'show_in_rest' => true        
        ));
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
        .woocommerce-store-alerts, #myCarousel {display: none;}
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
        
        add_submenu_page( 'learndash-lms', 'Zoom Meetings', 'Zoom Meetings', 'manage_options','edit.php?post_type=zoom');
 
    }
    
    public function analytics() {
        
        ?>

        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-5S5GHLJ');</script>
        <!-- End Google Tag Manager -->

        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-162895494-1"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
     
          gtag('config', 'UA-162895494-1');
  
        </script>

        <?php
    }
    
    public static function remove_astra_metabox( $post_type ) {

        remove_meta_box( 'astra_settings_meta_box', $post_type, 'side' );	

    }

}