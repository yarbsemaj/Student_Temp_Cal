<?php
/**
 * Created by PhpStorm.
 * User: yarbs
 * Date: 09/05/2017
 * Time: 11:29 PM
 */
require(__DIR__ . "/dbDetts.php");
class DAO{
    function  __construct(){
        global $link;
        $link = mysqli_connect(ADDRESS, USERNAME, PASSWORD, DATABASE);
        if (!$link) {
            echo "Error: Unable to connect to MySQL." . PHP_EOL;
            echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
            echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
        }
    }

    function getLink(){
        global $link;
        return $link;
    }
}