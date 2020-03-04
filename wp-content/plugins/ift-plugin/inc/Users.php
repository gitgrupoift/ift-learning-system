<?php

namespace IFT;

class Users {
    
    
    public function __construct() {

        $this->avatar = $user_photo;
        $this->remove_fields();
        
        add_action( 'after_setup_theme', array($this, 'remove_admin_bar') );
        
    }
    
    private function remove_fields() {
        
        if(is_admin()){         
            remove_action("admin_color_scheme_picker", "admin_color_scheme_picker");
        }
        
    }
    
    function remove_admin_bar() {
        
        if (!current_user_can('administrator') && !is_admin()) {
            show_admin_bar(false);
        }
    }


}