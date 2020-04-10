<?php

/*
CLI Wrapper

curl --header "Content-Type: application/json" --header "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhdWQiOm51bGwsImlzcyI6InVFUW82OHJRUlgtMUFFbXZFTDBzU3ciLCJleHAiOjE2NjgxOTgwNjAsImlhdCI6MTU4NjE5NTUzN30.nThpTURN-WwVDEdJZYwveyiVyBsaj6OW_ACdMwQgDy4" --request POST --data '{"topic":"VIG 003","type":2,"start_time":"2020-04-06T19:22:00Z","settings":{"watermark": true}}'  https://api.zoom.us/v2/users/me/meetings

*/

namespace IFT;

class Zoom {

    public $meetings_url = 'https://api.zoom.us/v2/users/me/meetings';
    
    public function __construct() {

        $this->meetings_url = $meetings_url;
                
    }
 
    public function meetings_request( $data ) {
        
        $data_string = json_encode($data);
        
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->meetings_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"topic\":\"xyz\",\"type\":1}");

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhdWQiOm51bGwsImlzcyI6InVFUW82OHJRUlgtMUFFbXZFTDBzU3ciLCJleHAiOjE2NjgxOTgwNjAsImlhdCI6MTU4NjE5NTUzN30.nThpTURN-WwVDEdJZYwveyiVyBsaj6OW_ACdMwQgDy4';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        
    }   

}