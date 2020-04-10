<?php

namespace IFT\Woocommerce;

use Automattic\WooCommerce\Client;
use IFT\Woocommerce\Nif;

class Woocommerce {
    
    public function __construct() {
        
        self::init();
        
        $client = new Client(
            'https://aulas.grupoift.pt/', 
            'ck_6acbb745d31774e5c81eee79669d38fe695e4d3c', 
            'cs_58034c10050f3e70b4dc9fe4e702d84e4f056f1a',
            [
                'version' => 'wc/v3',
            ]
        );
        
    }
    
    protected static function init() {
        
        new Nif();
        
    }
    
}