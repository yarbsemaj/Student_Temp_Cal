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
    private $valid = false;

    public function __construct($username,$password)
    {
        $url = 'https://www.studenttemp.co.uk/login';
        $options = array(
            'http' => array(
                'method'  => 'GET'
            )
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        $doc = new DomDocument;
        $doc->validateOnParse = true;
        @$doc->loadHTML($result);

        $xp = new DOMXpath($doc);
        $inputs = $xp->query('//input[@name="authenticity_token"]');
        $input = $inputs->item(0);

        $at = $input->getAttribute('value');

        foreach ($http_response_header as $hdr) {
            if (preg_match('/^Set-Cookie:\s*([^;]+)/', $hdr, $matches)) {
                parse_str($matches[1], $tmp);
                $this->cookies += $tmp;
            }
        }

        $data = array('user_session[email]' => $username,
            'user_session[password]' => $password,
            'authenticity_token' => $at,
            'inviscap'=>'true',
            'commit'=>'Login',
            'utf8'=>'✓');

        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n"
                    . $this->getCookies()."\r\n"
            ,
                'method' => 'POST',
                'content' => http_build_query($data)
            )
        );

        $url = 'https://www.studenttemp.co.uk/user_sessions';

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
        }
    }

    public function isValid(){
       return $this->valid;
    }

    public function getJobList(){
        $url = 'https://www.studenttemp.co.uk/bookings#upcoming';
        $options = array(
            'http' => array(
                'header'  => $this->getCookies(),
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
                'header'  => $this->getCookies(),
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

    function getCookies(){
        $string = "Cookie: ";
        foreach ($this->cookies as $index => $cookie)
        {
            $string= $string.$index."=".$cookie.";";
        }
        return substr($string,0,-1);
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