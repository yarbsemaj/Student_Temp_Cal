<?php
/**
 * Created by PhpStorm.
 * User: yarbs
 * Date: 11/05/2017
 * Time: 10:13 PM
 */

session_start();

require_once ("../model/StudentTempConnection.php");
require_once ("../model/StudentTempModal.php");
require_once ("../model/GoogleInfo.php");
require_once ("../model/GoogleAccount.php");


$studentTemp = new StudentTempModal();

$googleInfo = new GoogleInfo($_SESSION['Token']);

$googleData = $googleInfo->get();

$googleAccount = new GoogleAccount();

$accountData = $googleAccount->get($googleData->id);

$studentTempCTRL = new StudentTempConnection($accountData['Username'],$accountData['Password']);

$data = array();
foreach ($studentTempCTRL->getJobList() as $job){
    $data[]=$studentTempCTRL->getJobExtendedInfo($job['DetailsID']);
}
header('Content-Type: application/json');
echo json_encode($data);