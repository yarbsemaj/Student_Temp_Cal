<?php
require_once (__DIR__ . '/SQLModel.php');
/**
 * Created by PhpStorm.
 * User: yarbs
 * Date: 11/05/2017
 * Time: 06:23 PM
 */
class StudentTempModal extends SQLModel
{
    public function add($userID,$username,$password){
        $stmt1 = $this->link->prepare("INSERT INTO studenttemplogin (Username, Password,GoogleID) 
                                    VALUES (?,?,?)");

        /* bind parameters for markers */
        $stmt1->bind_param("sss", $username, $password,$userID);

        /* execute query */
        $stmt1->execute();

        $stmt1->close();
    }

    public function isLinked($googleID){
        $stmt =  $this->link->prepare("SELECT * FROM studenttemplogin WHERE GoogleID=?");

        $stmt->bind_param("s", $googleID);
        $stmt->execute();
        $stmt->store_result();
        $numRows = $stmt->num_rows;


        $stmt->close();

        return $numRows !=0;
    }

    public function get($googleID){
        $stmt =  $this->link->prepare("SELECT * FROM studenttemplogin WHERE GoogleID=?");

        $stmt->bind_param("s", $googleID);
        $stmt->execute();

        $result = $stmt->get_result();

        $stmt->close();
        $result=$result->fetch_array(MYSQLI_ASSOC);

        return $result;
    }
}