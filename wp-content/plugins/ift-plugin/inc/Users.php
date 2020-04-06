<?php

namespace IFT;

class Users {
    
    
    public function __construct() {

      
        $this->remove_fields();
        
        add_action( 'init', array($this, 'remove_admin_bar') );
        add_action( 'admin_init', array($this, 'add_limited_admin_role') );
  
        
        add_action( 'publish_groups', array($this, 'generate_cloud_groups') );   
   
        
    }
    
    private function remove_fields() {
        
        if(is_admin()){         
            remove_action("admin_color_scheme_picker", "admin_color_scheme_picker");
        }
        
    }
    

    
    public function add_limited_admin_role() {

        add_role( 'entity', __( 'Entidade', 'ift-plugin' ) );
        
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
    
    
    /*
     * Cria grupos na nuvem a partir dos grupos criados no IFT Learning e adiciona utilizadores
     * @since 1.1.0
     * @updated 1.1.2
     *
     */
    function generate_cloud_groups() {
        
        $args = array(
            'post_type' => 'groups',
            'post_status' => 'publish'
        );
        
        
        $rooms = get_posts( $args );
        
        foreach($rooms as $room) {
        
        $fields = array(
            'groupid' => $room->post_title
        );
        
        foreach($fields as $key=>$value) { 
            $fields_string .= $key.'='.$value.'&'; 
        }
        
        rtrim($fields_string, '&');
        
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://carlos:Lipsw0rld@app.grupoift.pt/ocs/v1.php/cloud/groups');
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
        
        $group_users = learndash_get_groups_users($room->ID);
        
        foreach($group_users as $user) {
                
            $user_name = $user->user_login;
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://carlos:Lipsw0rld@app.grupoift.pt/ocs/v1.php/cloud/users/' . $user_name . '/groups');
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
    }

}