<?php

/**
 * Created by PhpStorm.
 * User: yarbs
 * Date: 10/05/2017
 * Time: 06:55 PM
 */
require_once (__DIR__."/../model/Job.php");
require_once (__DIR__."/../controler/Event.php");
class StudentTempConnection
{
    public $cookies = array();
    private $getHeaders;
    private $valid = false;

    public function __construct($username,$password)
    {
        $url = 'https://www.studenttemp.co.uk/user_sessions';
        $data = array('user_session[email]' => $username, 'user_session[password]' => $password);

        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context = stream_context_create($options);
        file_get_contents($url, false, $context);

        foreach ($http_response_header as $hdr) {
            if (preg_match('/^Set-Cookie:\s*([^;]+)/', $hdr, $matches)) {
                parse_str($matches[1], $tmp);
                $this->cookies += $tmp;
            }
        }
        if(count($this->cookies) ==2){
            $this->valid=true;
            $this->getHeaders ="Cookie:_786_dt_=".$this->cookies['_786_dt_']."; _dt_=".$this->cookies['_dt_'];
        }
    }

    public function isValid(){
       return $this->valid;
    }

    public function getJobList(){
        $url = 'https://www.studenttemp.co.uk/bookings#upcoming';
        $options = array(
            'http' => array(
                'header'  => $this->getHeaders,
                'method'  => 'GET'
            )
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        $doc = new DomDocument;
        $doc->validateOnParse = true;
        @$doc->loadHTML($result);

        $finder = new DomXPath($doc);
        $classname="rw ui-accordion-header ui-helper-reset ui-state-default";
        $jobs = $finder->query("//*[contains(@class, '$classname')]");

        $return = array();
        foreach($jobs as $job) {
            $docs = new DOMDocument();
            $docs->appendChild($docs->importNode($job, true));
            $docs->strictErrorChecking = true;
            $xpath = new DOMXPath($docs);
            $table_rows = $xpath->query("//*[starts-with(@id, 'expand_')]");
            $idList = explode("_", $table_rows->item(0)->getAttribute("id"));
            $detailsID=$idList[1];

            $basicInfo = $xpath->query("//div[starts-with(@class, 'small-cell')]");

            $jobID = trim(preg_replace('/\s\s+/','', $basicInfo->item(0)->nodeValue));
            $data = array("DetailsID"=>$detailsID, "JobID"=>$jobID);
            $return[]=$data;
        }
        return $return;
    }

    function getJobExtendedInfo($detailsID){
        $url = 'https://www.studenttemp.co.uk/bookings/'.$detailsID;


        $options = array(
            'http' => array(
                'header'  => $this->getHeaders,
                'method'  => 'GET'
            )
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        $doc = new DomDocument;
        $doc->validateOnParse = true;
        @$doc->loadHTML($result);

        $finder = new DomXPath($doc);
        $className="data";
        $jobData = $finder->query("//*[contains(@class, '$className')]");

        $return = array();
        $fieldNames = array("ID","Type","Pay","Building","Supervisor","Address","DressCode","Info","StartDate","EndDate","StartTime","EndTime","Repeat","ShortDesc","ShortDesc");
        $i = 0;
        foreach($jobData as $job){
            $return[$fieldNames[$i]]=$job->nodeValue;
            $i++;
        }
        return $return;

    }

    function addJobs($user,$googleInfo){
        $jobModel = new Job();
        if($this->isValid()){
            foreach ($this->getJobList() as $job){
                if(!$jobModel->jobExist($user['StudentTempID'],$job['JobID'])){
                    $jobModel->add($user['StudentTempID'],$job['JobID']);

                    $event = new Event();

                    $event->add($googleInfo->client,$this->getJobExtendedInfo($job['DetailsID']));
                }
            }
        }
    }

}