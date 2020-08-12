<?php 

namespace IFT\ACF;

class Repeater extends acf_field {
    
    public function __construct() {  

        add_action('acf/include_field_types', array($this, 'include_field_types'));
        
    }
    
    
}