<?php
require_once (__DIR__ . '/SQLModel.php');
/**
 * Created by PhpStorm.
 * User: yarbs
 * Date: 11/05/2017
 * Time: 11:43 PM
 */
class Job extends SQLModel
{
    public function add($studentTempID,$jobID){
        $stmt1 = $this->link->prepare("INSERT INTO jobs (StudentTempID,JobNumber) 
                                    VALUES (?,?)");

        /* bind parameters for markers */
        $stmt1->bind_param("ss", $studentTempID, $jobID);

        /* execute query */
        $stmt1->execute();

        $stmt1->close();
    }

    public function jobExist($studentTempID, $jobID){
        $stmt =  $this->link->prepare("SELECT * FROM jobs WHERE StudentTempID=? AND JobNumber=?");

        $stmt->bind_param("ss", $studentTempID, $jobID);
        $stmt->execute();
        $stmt->store_result();
        $numRows = $stmt->num_rows;

        $stmt->close();

        return $numRows!=0;
    }

}