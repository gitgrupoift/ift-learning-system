<?php

namespace IFT\Reports;

use IFT\Reports\Factory;
use IFT\Reports\Table;
use IFT\Reports\Field;

class Courses {
    
    public function __construct() {
                
    }
    
    public function course_finished() {
        
        $current_user = wp_get_current_user(); 
        if ( !($current_user instanceof WP_User) ) 
            return; 
        $user_id = $current_user->ID;
        $database = new \PDO('sqlite:' . IFT_REPORTS . '0.sqlite');
        $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $database->exec(
                "CREATE TABLE myTable (
                    id INTEGER PRIMARY KEY, 
                    title TEXT, 
                    value TEXT)"
                );
        
    }

}