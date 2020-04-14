<?php

namespace IFT\Woocommerce;

class Nif {

    
    public function __construct() {

        add_filter( 'woocommerce_states', array($this, 'add_portugal_districts') );
        
        add_filter('woocommerce_get_country_locale', array($this, 'woocommerce_portugal_localization'));
        
    }
    
    public function add_portugal_districts( $states ) {

        $states['PT'] = array(
            'AV' => 'Distrito de Aveiro',
            'BE' => 'Distrito de Beja',
            'BR' => 'Distrito de Braga',
            'BG' => 'Distrito de Bragança',
            'CB' => 'Distrito de Castelo Branco',
            'CO' => 'Distrito de Coimbra',
            'EV' => 'Distrito de Évora',
            'FA' => 'Distrito de Faro',
            'GU' => 'Distrito da Guarda',
            'LE' => 'Distrito de Leiria',
            'LI' => 'Distrito de Lisboa',
            'PO' => 'Distrito do Porto',
            'PA' => 'Distrito de Portalegre',
            'SA' => 'Distrito de Santarém',
            'SE' => 'Distrito de Setúbal',
            'VC' => 'Distrito de Viana do Castelo',
            'VR' => 'Distrito de Vila Real',
            'VI' => 'Distrito de Viseu',
        );

        return $states;

    }
    
    
    public function woocommerce_portugal_localization($countries) {
        
		$countries['PT']['postcode_before_city'] = true;
		$countries['PT']['state']['label'] = __('District', 'woocommerce');
		$countries['PT']['state']['required'] = true;
        
		return $countries;
        
	}

    
}