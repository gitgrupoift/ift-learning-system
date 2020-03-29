<?php

namespace IFT;

class Talk {
    
    public function __construct() {
        
        add_action( 'acf/save_post', array($this, 'group_room') );
        
    }
    
    public function xml2array($xml){
        $arr = array();

        foreach ($xml->children() as $r)
        {
            $t = array();
            if(count($r->children()) == 0)
            {
                $arr[$r->getName()] = strval($r);
            }
            else
            {
                $arr[$r->getName()][] = $this->xml2array($r);
            }
        }
        return $arr;
        }
    
    /*
     * Cria um novo grupo no Nextcloud Talks sempre que um grupo novo é adicionado. Ignora grupos existentes e gera o token de acesso para o URL
     * @since 1.1.0
     *
     */
    public function group_room($post_id) {
        
        $types = get_post_type($post_id);
        
        if ( $types !== 'groups' ) {
            return;
        }
                
        $group= urlencode(get_the_title($post_id));
        $check = get_field('sala_sincrona', $post_id);
        
        if ( $check !== '' ) {
            return;
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://carlos:Lipsw0rld@app.grupoift.pt/ocs/v2.php/apps/spreed/api/v1/room');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "roomType=2&roomName=" . $group);

        $headers = array();
        $headers[] = 'Ocs-Apirequest: true';
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        
        $xmlx = simplexml_load_string($result);
        $response = $this->xml2array($xmlx);
        $json = json_encode($response);
        $json2 = json_decode($json, true);
        $token = $json2['data'][0]['token'];
        
        update_field( 'sala_sincrona', $token, $post_id);
        
    }
    
    public function group_room_link($user_id) {
        
        $user = new WP_User(get_current_user_id());
        $user_id = $user-ID;
        //learndash_get_users_group_ids
    }
    
}