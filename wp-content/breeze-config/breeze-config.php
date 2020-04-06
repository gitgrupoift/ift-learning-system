<?php 
defined( 'ABSPATH' ) || exit;
return array (
  'homepage' => 'https://aulas.grupoift.pt',
  'cache_options' => 
  array (
    'breeze-active' => '0',
    'breeze-ttl' => 30,
    'breeze-minify-html' => '0',
    'breeze-minify-css' => '1',
    'breeze-minify-js' => '1',
    'breeze-gzip-compression' => '1',
    'breeze-browser-cache' => '1',
    'breeze-desktop-cache' => 1,
    'breeze-mobile-cache' => 1,
    'breeze-disable-admin' => '0',
    'breeze-display-clean' => '1',
    'breeze-include-inline-js' => '0',
    'breeze-include-inline-css' => '0',
  ),
  'disable_per_adminuser' => 0,
  'exclude_url' => 
  array (
    0 => '/carrinho',
    1 => '/finalizar-compra/*',
    2 => '/minha-conta/*',
  ),
); 
