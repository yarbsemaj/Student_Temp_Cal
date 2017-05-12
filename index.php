<title>ST Calender</title>
<?php
/**
 * Created by PhpStorm.
 * User: yarbs
 * Date: 11/05/2017
 * Time: 09:06 PM
 */
session_start();
require_once ('model/GoogleInfo.php');
require_once ('model/StudentTempModal.php');
if(isset($_SESSION['Token'])){
    $googleInfo = new GoogleInfo($_SESSION["Token"]);

    $googleData=$googleInfo->get();

    $studentTemp = new StudentTempModal();

    if($studentTemp->isLinked($googleData->id)) require_once ('views/joblist.html'); else require_once ('views/account.html');
}else require_once ('views/login.html');