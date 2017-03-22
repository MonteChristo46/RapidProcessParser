<?php
/**
 * Created by PhpStorm.
 * User: danielhoeschele
 * Date: 21.03.17
 * Time: 19:27
 */
require_once("Parser.php");

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
            //$processInstance->setId($pi["P_ID"]);
            $processInstance->setDate($pi["Date"]);
            $processInstanceID = $pi["P_ID"];
            //$this->processInstances[] = $processInstance; --> Erst am Ende
            //Get Corresponding Activities for ProcessInstance
            $sqlForActivities = "SELECT `Activity_name`, `A_ID` FROM `Activity` WHERE `P_ID` = '$processInstanceID'";
            $resultForActivities = mysqli_query($this->db, $sqlForActivities);
            while($ac = mysqli_fetch_array($resultForProcessInstances, MYSQLI_ASSOC)){
                $activity = new Activity($ac["Activity_name"]);
                $activityID = $ac["A_ID"];
                //Get Corresponding Attributes to Activity
                $sqlForAttributes = "SELECT `Attr_Name`, `Attr_Value` FROM `Attribute` WHERE `A_ID` = '$activityID'";
                $resultForAttributes = mysqli_query($this->db, $sqlForAttributes);
                while($at =  mysqli_fetch_array($resultForAttributes, MYSQLI_ASSOC)){
                    $activity->addAttribute($at["Attr_Name"], $ac["Attr_Value"]);
                }
            }
        }

    }

    public function parseDataToXES(){

    }

}