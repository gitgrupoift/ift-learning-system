<?php

namespace IFT;

class Synology {
    
    public function __construct() {}
    
    public static function check() {}
    
    /* 
    CURL Login Synology -----
    curl -v 'https://grupoift.synology.me:5001/webapi/auth.cgi?api=SYNO.API.Auth&version=3&method=login&account=IFT%20-%20Carlos%20Marketing&passwd=Hugo0109luis%40&session=FileStation&format=cookie'
    
    @params     user (urlenconded), password (urlenconded)
    @param      session     FileStation
    @param      format      cookie
    
    @return     sid
    */
    public static function login() {}
    
}