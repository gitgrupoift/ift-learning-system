<?php

namespace IFT;

class Talk {
    
    public function __construct() {
        
        add_action( 'acf/save_post', array($this, 'group_room') );
        add_shortcode( 'ift-talk', array($this, 'group_room_link'));
        
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
        
        $this->group_public($token);
        // $this->add_group($token, 'ana.seg');
        update_field( 'sala_sincrona', $token, $post_id);
        
    }
    
    
    /*
     * Torna público um grupo adicionado ao Nextcloud
     * @since 1.1.0
     *
     */
    public function group_public($token) {
        
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://carlos:Lipsw0rld@app.grupoift.pt/ocs/v2.php/apps/spreed/api/v1/room/' . $token . '/public');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = 'Ocs-Apirequest: true';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        
    }    
    
    public function group_room_link($atts, $content = null) {
        $atts = shortcode_atts( array(
            'token'  => 'blue'
        ), $atts, 'ift-talk' );
            
        $button = '<button class="ift-talk"><a href="https://app.grupoift.pt/index.php/call/' . esc_attr( $atts['token'] ) . '">' . $content . '</a></button>';
        
        return $button;
        
    }
    
    
}