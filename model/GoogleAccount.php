<?php

/**
 * Created by PhpStorm.
 * User: yarbs
 * Date: 11/05/2017
 * Time: 04:36 PM
 */

require_once (__DIR__ . '/SQLModel.php');
class GoogleAccount extends SQLModel
{
    public function add($userID,$token){
        $stmt1 = $this->link->prepare("INSERT INTO googlelogin (GoogleID, RefreshToken) 
                                    VALUES (?,?)");

        /* bind parameters for markers */
        $stmt1->bind_param("ss", $userID, json_encode($token));

        /* execute query */
        $stmt1->execute();

        $stmt1->close();
    }

    public function get($googleID){
        $stmt =  $this->link->prepare("SELECT * FROM googlelogin AS G 
                            LEFT JOIN studenttemplogin AS S ON S.GoogleID = G.GoogleID WHERE G.GoogleID=?");

        $stmt->bind_param("s", $googleID);
        $stmt->execute();

        $result = $stmt->get_result();

        $stmt->close();
        $result=$result->fetch_array(MYSQLI_ASSOC);

        return $result;
    }

    public function getAll(){
        $stmt =  $this->link->prepare("SELECT * FROM googlelogin AS G 
                            JOIN studenttemplogin AS S ON S.GoogleID = G.GoogleID");

        $stmt->execute();

        $result = $stmt->get_result();

        $stmt->close();

        $results = array();
        while ($row = $result->fetch_array(MYSQLI_ASSOC)){
            $results[] = $row;
        };

        return $results;
    }

    public function getNumber($googleID){
        $stmt =  $this->link->prepare("SELECT * FROM googlelogin WHERE GoogleID=?");

        $stmt->bind_param("s", $googleID);
        $stmt->execute();
        $stmt->store_result();
        $numRows = $stmt->num_rows;


        $stmt->close();

        return $numRows;
    }

}