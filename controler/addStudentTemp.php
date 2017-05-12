<?php
/**
 * Created by PhpStorm.
 * User: yarbs
 * Date: 10/05/2017
 * Time: 06:42 PM
 */

session_start();

$username = $_POST['email'];
$password = $_POST['password'];

require_once ("../model/StudentTempConnection.php");
require_once ("../model/StudentTempModal.php");
require_once ("../model/GoogleInfo.php");
require_once ("../model/GoogleAccount.php");


$studentTempCTRL = new StudentTempConnection($username,$password);
$studentTemp = new StudentTempModal();

$googleInfo = new GoogleInfo($_SESSION['Token']);

$googleData = $googleInfo->get();

    if($studentTempCTRL->isValid()&&!$studentTemp->isLinked($googleData->id)){


        $studentTemp->add($googleData->id,$username,$password);
        $data = array("Success"=>true);

        $userModel = new GoogleAccount();

        $studentTempCTRL->addJobs($userModel->get($googleData->id),$googleInfo);

    } else $data = array("Success"=>false);
header('Content-Type: application/json');
echo json_encode($data);
