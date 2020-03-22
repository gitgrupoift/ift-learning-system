<?php

namespace IFT;
use NextcloudApiWrapper\Wrapper;

class Users {
    
    
    public function __construct() {

      
        $this->remove_fields();
        
        add_action( 'init', array($this, 'remove_admin_bar') );
        add_action( 'admin_init', array($this, 'add_limited_admin_role') );
        
        add_action( 'user_register', array($this, 'generate_cloud_user') );
        
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
        
        if (!current_user_can('administrator')) {
                show_admin_bar(false);
        }
    }
    
    function generate_cloud_user( $user_id ) {
        
        
        $user = get_user_by( 'id', $user_id );
        $login_cloud = $user->user_email;
        $pass_cloud = $user->user_pass;
        $fields = array(
            'userid' => $login_cloud,
            'password' => $pass_cloud,
            'email' => $login_cloud
        );
        
        foreach($fields as $key=>$value) { 
            $fields_string .= $key.'='.$value.'&'; 
        }
        
        rtrim($fields_string, '&');
        
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'http://carlos:Lipsw0rld001@app.grupoift.pt/ocs/v1.php/cloud/users');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string );

        $headers = array();
        $headers[] = 'Ocs-Apirequest: true';
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
    }


}