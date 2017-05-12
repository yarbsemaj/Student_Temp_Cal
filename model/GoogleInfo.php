<?php

/**
 * Created by PhpStorm.
 * User: yarbs
 * Date: 11/05/2017
 * Time: 08:21 PM
 */
require_once (__DIR__ . '/../google-api-php-client-2.1.3/vendor/autoload.php');
class GoogleInfo
{
    public $client;

    public function __construct($token)
    {
        $this->client = new Google_Client();

        $this->client->setAuthConfig(__DIR__ . '/../oath/client_secret.json');
        $this->client->setAccessType("offline");
        $this->client->addScope(Google_Service_Plus::USERINFO_PROFILE);
        $this->client->addScope(Google_Service_Oauth2::USERINFO_EMAIL);

        $this->client->setAccessToken($token);
    }

    public function get(){
        $google_oauthV2 = new Google_Service_Oauth2($this->client);

        return $google_oauthV2->userinfo->get();
    }
}