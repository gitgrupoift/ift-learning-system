<?php

function xml2array($xml){
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
        $arr[$r->getName()][] = xml2array($r);
    }
}
return $arr;
}


$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://carlos:Lipsw0rld@app.grupoift.pt/ocs/v2.php/apps/spreed/api/v1/room');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "roomType=2&roomName=dgdfgdff");

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
$response = xml2array($xmlx);
$json = json_encode($response);
$json2 = json_decode($json, true);
echo $json2['data'][0]['token'];

