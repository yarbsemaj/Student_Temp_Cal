<?php
/**
 * Created by PhpStorm.
 * User: yarbs
 * Date: 09/05/2017
 * Time: 09:18 PM
 */
session_start();
require_once (__DIR__ . '/../model/GoogleAccount.php');
require_once (__DIR__ . '/../google-api-php-client-2.1.3/vendor/autoload.php');
$client = new Google_Client();
$client->setAuthConfig('client_secret.json');
$client->setAccessType("offline");
$client->addScope(Google_Service_Plus::USERINFO_PROFILE);
$client->addScope(Google_Service_Oauth2::USERINFO_EMAIL);
$token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

$google_oauthV2 = new Google_Service_Oauth2($client);

$user = $google_oauthV2->userinfo->get();
$user_id = $user->id;

$googleAccount = new GoogleAccount();
if($googleAccount->getNumber($user_id)==0) {
    $googleAccount->add($user_id, $token);
}else{
    $result=$googleAccount->get($user_id);
    $token = json_decode($result['RefreshToken'],true);
}
    $_SESSION['Token']=$token;

header("Location: ../index.php");