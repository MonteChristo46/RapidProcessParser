<?php
/**
 * Created by PhpStorm.
 * User: danielhoeschele
 * Date: 21.03.17
 * Time: 19:27
 */
require_once("Parser.php");
require_once("ProcessInstance.php");
require_once("Activity.php");
require_once("Attribute.php");
class outParser extends Parser
{
    private $db;
    private $processInstances;

    public function __construct(){
        include("config.php");
        $this->db = mysqli_connect(
            MYSQL_HOST,
            MYSQL_USER,
            MYSQL_PASSWORD,
            MYSQL_DATABASE_NAME
        );
    }

    public function getDataFromDatabase(){
        //First query the processInstances
        $sqlForProcessInstances = "SELECT `P_ID`,`Name`,`Date` FROM `Process_Instance`";
        $resultForProcessInstances = mysqli_query($this->db, $sqlForProcessInstances);
        while($pi =  mysqli_fetch_array($resultForProcessInstances, MYSQLI_ASSOC)){
            $processInstance = new ProcessInstance($pi["Name"]);
            $processInstance->setId($pi["P_ID"]);
            $processInstance->setDate($pi["Date"]);
            $processInstanceID = $pi["P_ID"];
            //Get Corresponding Activities for ProcessInstance
            $sqlForActivities = "SELECT `Activity_name`, `A_ID` FROM `Activity` WHERE `P_ID` = '$processInstanceID'";
            $resultForActivities = mysqli_query($this->db, $sqlForActivities);
            while($ac = mysqli_fetch_array($resultForActivities, MYSQLI_ASSOC)){
                $activity = new Activity($ac["Activity_name"]);
                $activityID = $ac["A_ID"];
                //Get Corresponding Attributes to Activity
                $sqlForAttributes = "SELECT `Attr_Name`, `Attr_Value` FROM `Attribute` WHERE `A_ID` = '$activityID'";
                $resultForAttributes = mysqli_query($this->db, $sqlForAttributes);
                while($at =  mysqli_fetch_array($resultForAttributes, MYSQLI_ASSOC)){
                    $activity->addAttribute($at["Attr_Name"], $at["Attr_Value"]);
                }
                $processInstance->addActivity($activity);
            }
            $this->processInstances[] = $processInstance;
        }
        return $this->processInstances;
    }

    public function parseDataToXES(){
        $processInstances =  $this->processInstances;

        $dom = new DOMDocument("1.0", "UTF-8");
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $root = $dom->createElement("log");
            $root->setAttribute("xmlns", "http://code.deckfour.org/xes");
            $root->setAttribute("xes.version", "2.0");
            $root->setAttribute("xes.creator", "RapidMiner Parser");
        $dom->appendChild($root);
        foreach($processInstances as $processInstance){
            $trace = $dom->createElement("trace");
            $root->appendChild($trace);
            $activities = $processInstance->getActivities();
            foreach($activities as $activity){
                $eventTag = $dom->createElement("event");
                $trace->appendChild($eventTag);
                $stringName = $dom->createElement("string");
                    $stringName->setAttribute("key","concept:name");
                    $stringName->setAttribute("value",$activity->getName());
                $eventTag->appendChild($stringName);
                $attributes = $activity->getAttributes();
                /*foreach($attributes as $arr){
                    $stringAttribute = $dom->createElement("string");
                    echo($arr->getValue());
                       // $stringAttribute->setAttribute("key", "$attribute->getName()");
                        //$stringAttribute->setAttribute("value","$attribute->getValue()");
                    $eventTag->appendChild($stringAttribute);
                }*/

            }

        }
        $dom->save("ProcessData.xes");
    }

}