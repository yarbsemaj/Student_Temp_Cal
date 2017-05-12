<?php
/**
 * Created by PhpStorm.
 * User: yarbs
 * Date: 10/05/2017
 * Time: 12:34 AM
 */

session_start();

require_once('../model/GoogleInfo.php');

$googleInfo = new GoogleInfo($_SESSION['Token']);
header('Content-Type: application/json');
echo json_encode($googleInfo->get());