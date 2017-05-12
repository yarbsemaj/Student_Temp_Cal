<?php
/**
 * Created by PhpStorm.
 * User: yarbs
 * Date: 09/05/2017
 * Time: 09:05 PM
 */
require_once (__DIR__ . '/../google-api-php-client-2.1.3/vendor/autoload.php');
$client = new Google_Client();
$client->setAuthConfig('client_secret.json');
$client->setAccessType("offline");        // offline access
$client->setIncludeGrantedScopes(true);   // incremental auth
$client->addScope(Google_Service_Calendar::CALENDAR);
$client->addScope(Google_Service_Plus::USERINFO_PROFILE);
$client->addScope(Google_Service_Oauth2::USERINFO_EMAIL);
$client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/oath/oa2cb.php');
$client->setPrompt("consent");
$auth_url = $client->createAuthUrl();
header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));