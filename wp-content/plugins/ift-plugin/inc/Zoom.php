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

    protected $api_token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhdWQiOm51bGwsImlzcyI6IjdnVXloM1BnVHB5ZFZfSHpSdnVTdGciLCJleHAiOjE2NTA0ODEyMDAsImlhdCI6MTU4NzM5MDc2M30.6P6TSGljCx7kLpxUcvFBM52kqAHMaR76zK4CouRE1DE';
    
    protected $user = 'luisferreira@grupoift.pt';
    
    public function __construct() {
        
        $this->api_token = $api_token;
        $this->user = $user;
                
    }
    
    public function set_meeting( $topic, $type = 3, $start = '', $end = '', $duration = 60, $recording = true, $frequency = 1) {
        
        $config = Swagger\Client\Configuration::getDefaultConfiguration()->setApiKey('access_token', $this->api_token);

        $apiInstance = new Swagger\Client\Api\MeetingsApi(

        new GuzzleHttp\Client(),
            $config
        );
        $user_id = $this->user;
        
        $body = new \stdClass; 
        $body->topic = $topic;
        $body->type = $type;

        try {
            $result = $apiInstance->meetingCreate($user_id, $body);
            print_r($result);
        } catch (Exception $e) {
            echo 'Exception when calling MeetingsApi->meetingCreate: ', $e->getMessage(), PHP_EOL;
        }
        
    } 

}