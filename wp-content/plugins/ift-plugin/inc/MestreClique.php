<?php

namespace IFT;

class MestreClique {
    
    public function __construct( $mestre_url ) {
        
        self::client( $mestre_url );
        
    }
    
    public static function client( $url ) {
        
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        
        $data = file_get_contents($url);
        $response = json_decode($data);
        
        foreach ($response as $cods) {
            echo $cods->CODCURSO;
        }
 
    }

}