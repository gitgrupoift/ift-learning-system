<?php

/*
CLI Wrapper

curl --header "Content-Type: application/json" --header "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhdWQiOm51bGwsImlzcyI6IjdnVXloM1BnVHB5ZFZfSHpSdnVTdGciLCJleHAiOjE2NTA0ODEyMDAsImlhdCI6MTU4NzM5MDc2M30.6P6TSGljCx7kLpxUcvFBM52kqAHMaR76zK4CouRE1DE" --request POST --data '{"topic":"VIG 003","type":2,"start_time":"2020-04-06T19:22:00Z","settings":{"watermark": true}}'  https://api.zoom.us/v2/users/me/meetings

Authorizing URL ---- IMPORTANTE!!!
https://zoom.us/oauth/authorize?response_type=code&client_id=cdXqEIyKRg2I9Nua8ZMpZA&redirect_uri=https%3A%2F%2Faulas.grupoift.pt%2Fift-learning%2Flrs%2Fv1%2Fzoom

Retorna POST no endpoint correto do LRS

*/

namespace IFT;

class Zoom {

    protected $api_token;
    
    protected $user = 'luisferreira@grupoift.pt';
    
    public function __construct() {
        
        $this->api_token = get_option( 'ift-plugin' );
        $this->user = $user;
                
    }
    
    public static function add_meeting($topic) {
        
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.zoom.us/v2/users/luisferreira@grupoift.pt/meetings');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"topic\":\"" . $topic . "\",\"type\":3,\"start_time\":\"2020-04-06T19:22:00Z\",\"settings\":{\"watermark\": true}}");

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer ' . $this->api_token['zoom_token'] . '';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        
    } 

}