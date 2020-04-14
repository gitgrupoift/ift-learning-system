<?php

namespace IFT\Woocommerce;

use IFT\Woocommerce\Nif;

class Woocommerce {
    
    public function __construct() {
        
        self::init();
        
    }
    
    protected static function init() {
        
        new Nif();
        
    }
    
}