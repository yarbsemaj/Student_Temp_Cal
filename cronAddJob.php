<?php
/**
 * Created by PhpStorm.
 * User: yarbs
 * Date: 11/05/2017
 * Time: 10:53 PM
 */

require_once ('model/GoogleInfo.php');
require_once ('model/GoogleAccount.php');
require_once ('model/Job.php');
require_once ('model/StudentTempConnection.php');
require_once ('controler/Event.php');

$googleAccount= new GoogleAccount();

foreach ($googleAccount->getAll() as $user){
    $googleInfo = new GoogleInfo($user['RefreshToken']);


    $studentTemp = new StudentTempConnection($user['Username'],$user['Password']);

    $studentTemp->addJobs($user,$googleInfo);




}