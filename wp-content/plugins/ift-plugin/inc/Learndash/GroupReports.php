<?php 

namespace IFT\Learndash;

class GroupReports {

    public static $instance = null;
    private $data_slug = 'user-courses-xml';
    
    public function __construct() {  
        
    }
    
    public static function getInstance() {
        if ( ! isset( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }


    
}