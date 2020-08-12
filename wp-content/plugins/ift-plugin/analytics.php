<?php

require __DIR__ .'/vendor/autoload.php';

use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;

$client = new Client(HttpClient::create(['timeout' => 60]));

$crawler = $client->request('GET', 'https://marketingplatform.google.com/about/analytics/');
$crawler = $client->click($crawler->selectLink('Sign in to Analytics')->link());
$form = $crawler->selectButton('Próxima')->form();

