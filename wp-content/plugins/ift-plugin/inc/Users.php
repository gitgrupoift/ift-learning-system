<?php

namespace IFT;

class Users {
    
    
    public function __construct() {

        $this->avatar = $user_photo;
        $this->remove_fields();
        
        add_action( 'after_setup_theme', array($this, 'remove_admin_bar') );
        add_action( 'admin_init', array($this, 'add_limited_admin_role') );
        
    }
    
    private function remove_fields() {
        
        if(is_admin()){         
            remove_action("admin_color_scheme_picker", "admin_color_scheme_picker");
        }
        
    }
    

    
    public function add_limited_admin_role() {

        add_role( 'entity', __( 'Entidade', 'ift-plugin' ), $capabilities );
        
        $entity = get_role('entity');
        $entity->add_cap('edit_users');
        $entity->add_cap('list_users');
        $entity->add_cap('promote_users');
        $entity->add_cap('create_users');
        $entity->add_cap('add_users');
        $entity->add_cap('delete_users');
        
        $entity->remove_cap('edit_themes');
        $entity->remove_cap('edit_posts');
        
        
        if ( current_user_can( 'entity' ) ) {
            
            remove_menu_page('options-general.php');
            remove_menu_page('import.php');
            remove_menu_page('themes.php');
            remove_menu_page('edit.php?post_type=acf-field-group');
            remove_menu_page('edit.php?post_type=page');
            remove_menu_page('edit-tags.php?taxonomy=category');
            remove_menu_page('sfwd-transactions-options');
            remove_menu_page('pwaforwp');
            remove_menu_page('loco');
            remove_menu_page('moove-gdpr');
            remove_menu_page('uncanny-toolkit');
            remove_menu_page('elementor');
            remove_menu_page('wpforms-overview');
            
        }            
        
    }
    
    function remove_admin_bar() {
        
        if (!current_user_can('administrator') && !is_admin()) {
            if (!current_user_can('entity') && !is_admin()) {
                show_admin_bar(false);
            }
        }
    }


}