<?php

namespace IFT\Tools;

use IFT\Tools\Cloner;

class Tools {
    
    public function __construct() {
        
        self::init();
        
    }
    
    protected static function init() {
        
        new Cloner();
        
    }
    
}