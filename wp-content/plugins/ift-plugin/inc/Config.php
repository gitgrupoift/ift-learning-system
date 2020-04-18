<?php

namespace IFT;

class Config {
    
    public function __construct() {

        add_filter( 'site_transient_update_plugins', array($this, 'plugin_no_updates') );
    }
    
    public function plugin_no_updates( $value ) {
        
        unset( $value->response['learndash-course-grid/learndash_course_grid.php'] );
        return $value;
    }
    
}