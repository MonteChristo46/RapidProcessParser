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
        $parameters  =  func_get_args();
        $numberOfParameters = func_num_args();

        $readAttributes = true;
        $sqlForProcessInstances = "SELECT `P_ID`,`Name`,`Date` FROM `Process_Instance`";

        if($numberOfParameters == 3 && is_string( $parameters [1])){
            //echo("First Case");
            $startDate =  $parameters [0];
            $endDate =  $parameters [1];
            $allAttributesBoolean =  $parameters [2];
            $sqlForProcessInstances =  $sqlDate = "SELECT * FROM `Process_Instance` WHERE `Date` BETWEEN '$startDate' AND '$endDate'";
            if($allAttributesBoolean == false){
                $readAttributes = false;
            }
        }else if($numberOfParameters == 2 && (bool)strtotime($parameters[0]) == true && (bool)strtotime($parameters[1]) == true){
            //echo("Second Case - Just Dates and Attribute are displayed");
            $startDate =  $parameters [0];
            $endDate =  $parameters [1];
            $sqlForProcessInstances =  $sqlDate = "SELECT * FROM `Process_Instance` WHERE `Date` BETWEEN '$startDate' AND '$endDate'";

        }else if($numberOfParameters == 2 && gettype($parameters[0]) == "integer" && gettype($parameters[1]) == "boolean"){
            //echo("Hello");
            $sqlForProcessInstances = "SELECT * FROM `Process_Instance` WHERE `P_ID` <= '$parameters[0]'";
            $readAttributes = false;
        }else if($numberOfParameters == 1){
            if(gettype($parameters[0]) == "boolean"){
                $readAttributes = false;
            }
            if(gettype($parameters[0])== "integer"){
                $sqlForProcessInstances = "SELECT * FROM `Process_Instance` WHERE `P_ID` <= '$parameters[0]'";
            }
        }



        //First query the processInstances
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
                if($readAttributes == true) {
                    while ($at = mysqli_fetch_array($resultForAttributes, MYSQLI_ASSOC)) {
                        $attribute = new Attribute($at["Attr_Name"], $at["Attr_Value"]);
                        $activity->addAttribute($attribute);
                    }
                }
                $processInstance->addActivity($activity);
            }
            $this->processInstances[] = $processInstance;
        }
        return $this->processInstances;
    }

    public function parseDataToXES(){
        $dom = new DOMDocument("1.0", "UTF-8");
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            $root = $dom->createElement("log");
            $root->setAttribute("xmlns", "http://code.deckfour.org/xes");
            $root->setAttribute("xes.version", "2.0");
            $root->setAttribute("xes.creator", "RapidMiner Parser");
            $dom->appendChild($root);
        if($this->processInstances != NULL){
            $processInstances =  $this->processInstances;
            foreach($processInstances as $processInstance) {
                $trace = $dom->createElement("trace");
                $root->appendChild($trace);
                $activities = $processInstance->getActivities();
                foreach ($activities as $activity) {
                    $eventTag = $dom->createElement("event");
                    $trace->appendChild($eventTag);
                    $stringName = $dom->createElement("string");
                    $stringName->setAttribute("key", "concept:name");
                    $stringName->setAttribute("value", $activity->getName());
                    $eventTag->appendChild($stringName);
                    $attributes = $activity->getAttributes();
                    for ($i = 0; $i < count($attributes); $i++) {
                        $arr = $attributes[$i];
                        $stringAttribute = $dom->createElement("string");
                        //echo($arr->getValue());q
                        $stringAttribute->setAttribute("key", $arr->getName());
                        $stringAttribute->setAttribute("value", $arr->getValue());
                        $eventTag->appendChild($stringAttribute);
                    }

                }
            }
        }
        $dom->save("ProcessData.xes");

    }

    public function parseDataToCSV(){
        $processInstances = $this->processInstances;
        $delimiter = ",";

        $csv = fopen("ProcessData.csv", "w");
        $headerValueArray = array("CaseID", "Activity");
        for($i=0; $i<count($processInstances); $i++){
            $activities = $processInstances[$i]->getActivities();
            for($j=0; $j<count($activities); $j++){
                $attributes = $activities[$j]->getAttributes();
                for($k=0; $k<count($attributes); $k++){
                    array_push($headerValueArray, $attributes[$k]->getName());
                }
            }
        }
        $headerValuesUnIndexed = array_unique($headerValueArray, SORT_STRING);
        $headerValues = array_values(array_filter($headerValuesUnIndexed)); //Head of CSV
        fputcsv($csv, $headerValues, $delimiter);

        //Loop through everything again and push data in csv at the right place

        for($i=0; $i<count($processInstances); $i++){
            $activities = $processInstances[$i]->getActivities();

            for($j=0; $j<count($activities); $j++){
                $attributes = $activities[$j]->getAttributes();
                $dataSet = array();
                array_push($dataSet, $i); //Case ID
                array_push($dataSet, $activities[$j]->getName()); //Name
                for($k=0; $k<count($attributes); $k++){
                    $attr = $attributes[$k];
                    $attrName = $attr->getName();
                    $index = array_search($attrName, $headerValues);
                    if($attr->getValue() != ""){
                        $dataSet[$index] = $attr->getValue();
                    }else{
                        $dataSet[$index] = "";
                    }
                }

                //Fill up all other attributes with null
                for($m=0; $m<count($headerValues); $m++){
                    $exists = array_key_exists($m, $dataSet);
                    if(!$exists){
                        $dataSet[$m] = "";
                    }
                }
                ksort($dataSet);
                fputcsv($csv, $dataSet, $delimiter);
            }
        }
        fclose($csv);
    }
}