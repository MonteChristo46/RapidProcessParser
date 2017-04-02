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
/*
 * $start, $end, attr
 * $star, end
 * $range, attr
 * $range,
 *
 * labels[], UseCaseNames[] --> Not Empty && is_array()==true
 *
 * --left join
 *
 *SELECT Process_Instance.P_ID,Process_Instance.Date, Process_Instance.UseCase, Label.Label_1, Label.Label_2, Label.Label_3, Label.Label_4
 *FROM `Process_Instance`
 *INNER JOIN Label ON Process_Instance.P_ID = Label.P_ID
 *WHERE (Process_Instance.UseCase = "UseCase1" OR Process_Instance.UseCase = "Use Case 1")
 *AND Process_Instance.Date BETWEEN "30.03.2017" AND "30.03.2017"
 *AND (Label.Label_1="Hallo" OR Label.Label_2="Hallo" OR Label.Label_3="Hallo" OR Label.Label_4="Hallo"
 *OR Label.Label_1="zusammen" OR Label.Label_2="zusammen" OR Label.Label_3="zusammen" OR Label.Label_4="zusammen"
 *OR Label.Label_1="zusammen!!" OR Label.Label_2="zusammen!!" OR Label.Label_3="zusammen!!" OR Label.Label_4="zusammen!!"
 *OR Label.Label_1="asdaf" OR Label.Label_2="asdaf" OR Label.Label_3="asdaf" OR Label.Label_4="asdaf")
 * */
    private function createAdditionalSQLForUseCases($useCases){
        if(count($useCases)>0) {
            $additionalStringForUseCase = " WHERE (";
            for ($i = 0; $i < count($useCases); $i++) {
                $additionalStringForUseCase .= "Process_Instance.UseCase = " . "\"" . $useCases[$i] . "\"" . "";
                if (($i + 1) < count($useCases)) {
                    $additionalStringForUseCase .= " OR ";
                } else {
                    $additionalStringForUseCase .= ")";
                }
            }
            return $additionalStringForUseCase;
        }else{
            return "";
        }

    }
    private function createAdditionalSQLForLabels($labels, $operation){
        if(count($labels>0)){
            $additionalStringForLabels = " $operation (";
            for($i = 0 ; $i<count($labels); $i++){
                for($j=1; $j<=4; $j++) {
                    $x = $j+1;
                    $additionalStringForLabels .= " Label.Label_" . $j . "=" . "\"".$labels[$i]."\" ";
                    if($x <5){
                        $additionalStringForLabels.= "OR ";
                    }
                }
                if(($i+1)<count($labels)){
                    $additionalStringForLabels.= " OR ";
                }
                else{
                    $additionalStringForLabels.=")";
                }

            }
            return $additionalStringForLabels;
        }else{
            return "";
        }
    }
    private function getStandardStringForLabelsAndUseCases(){
        $standardString = "SELECT Process_Instance.P_ID, Process_Instance.Date, Process_Instance.UseCase, Label.Label_1, Label.Label_2, Label.Label_3, Label.Label_4  
                               FROM `Process_Instance` 
                               LEFT JOIN Label 
                               ON Process_Instance.P_ID = Label.P_ID";
        return $standardString;
    }
    public function getDataFromDatabase(){
        $parameters  =  func_get_args();
        $numberOfParameters = func_num_args();

        $readAttributes = true;
        $sqlForProcessInstances = "SELECT `P_ID`,`UseCase`,`Date` FROM `Process_Instance` WHERE 'P_ID' = 2";

        if($numberOfParameters == 5
                &&(bool)strtotime($parameters[0])
                &&(bool)strtotime($parameters[1])
                && is_array($parameters[3])
                && is_array($parameters[4])
                && count($parameters[3])>0
                && count($parameters[4])>0
            ){
            $startDate =  $parameters [0];
            $endDate =  $parameters [1];
            $allAttr=  $parameters [2]; //  Check if $parameter 2 is boolean
            $useCases = $parameters[3];
            $labels = $parameters[4];
            $readAttributes = $allAttr;

            $standardString = $this->getStandardStringForLabelsAndUseCases();
            $dateString = " AND Process_Instance.Date BETWEEN '$startDate' AND '$endDate'";
            $additionalStringForLabels = $this->createAdditionalSQLForLabels($labels, "AND");
            $additionalStringForUseCase = $this->createAdditionalSQLForUseCases($useCases);

            $sqlForProcessInstances = $standardString.$additionalStringForUseCase.$dateString.$additionalStringForLabels;
            //echo($sqlForProcessInstances);
        }else if($numberOfParameters == 4
            &&(bool)strtotime($parameters[0])
            &&(bool)strtotime($parameters[1])
            && is_array($parameters[2])
            && is_array($parameters[3])
            && count($parameters[2])>0
            && count($parameters[3])>0){
                $startDate =  $parameters [0];
                $endDate =  $parameters [1];
                //$allAttr=  $parameters [2]; //  Check if $parameter 2 is boolean
                $useCases = $parameters[3];
                $labels = $parameters[4];
                //$readAttributes = $allAttr;

                $standardString = $this->getStandardStringForLabelsAndUseCases();
                $dateString = " AND Process_Instance.Date BETWEEN '$startDate' AND '$endDate'";
                $additionalStringForLabels = $this->createAdditionalSQLForLabels($labels, "AND");
                $additionalStringForUseCase = $this->createAdditionalSQLForUseCases($useCases);
                $sqlForProcessInstances = $standardString.$additionalStringForUseCase.$dateString.$additionalStringForLabels;
        }else if($numberOfParameters == 4
            && is_int($parameters[0])
            && is_bool($parameters[1])
            && is_array($parameters[2])
            && is_array($parameters[3])
            && count($parameters[2])>0
            && count($parameters[3])>0
            ){
            /*
             * If no date is selected and parameter sequenz is like:
             * (int range, boolean allAttributes, useCases[], labels[])
             * */
            $range = $parameters[0];
            $allAttr = $parameters[1];
            $useCases = $parameters[2];
            $labels = $parameters[3];
            $readAttributes = $allAttr;

            $standardString = $this->getStandardStringForLabelsAndUseCases();
            $rangeString = "AND Process_Instance.P_ID <= '$range'";
            $additionalStringForLabels = $this->createAdditionalSQLForLabels($labels, "AND");
            $additionalStringForUseCase = $this->createAdditionalSQLForUseCases($useCases);

            //echo($standardString.$additionalStringForUseCase.$rangeString.$additionalStringForLabels);
            $sqlForProcessInstances = $standardString.$additionalStringForUseCase.$rangeString.$additionalStringForLabels;
        }else if (is_array($parameters[0])
            && is_array($parameters[1])
            && count($parameters[0])>0
            && count($parameters[1])>0
            ){
            /*
            * If no date or range is selected and parameter sequenze is like:
            * (useCases[], labels[])
            * */
            $useCases = $parameters[0];
            $labels = $parameters[1];

            $standardString = $this->getStandardStringForLabelsAndUseCases();
            $additionalStringForLabels = $this->createAdditionalSQLForLabels($labels, "AND");
            $additionalStringForUseCase = $this->createAdditionalSQLForUseCases($useCases);
            $sqlForProcessInstances = $standardString.$additionalStringForUseCase.$additionalStringForLabels;
           // echo($sqlForProcessInstances);
        }else if ($numberOfParameters == 2
            && is_array($parameters[0])
            && is_string($parameters[1])
            && count($parameters[0])>0
            ){
            /*
            * Only if useCases or labels are selected
            * (useCases[], "useCase")
            * (labels[], "labels")
            * */
            $useCasesOrLabels = $parameters[0];
            $string = $parameters[1];
            $standardString = $this->getStandardStringForLabelsAndUseCases();
            if($string == "useCases"){
                $additionalStringForUseCase = $this->createAdditionalSQLForUseCases($useCasesOrLabels);
                $sqlForProcessInstances = $standardString.$additionalStringForUseCase;
            }else if($string == "labels"){
                $additionalStringForLabels = $this->createAdditionalSQLForLabels($useCasesOrLabels, "WHERE");
                $sqlForProcessInstances = $standardString.$additionalStringForLabels;
               // echo($sqlForProcessInstances);
            }
        }else if($numberOfParameters == 5
            &&(bool)strtotime($parameters[0])
            &&(bool)strtotime($parameters[1])
            && is_array($parameters[3])
            && is_array($parameters[4])
            && count($parameters[3])<=0
            && count($parameters[4])<=0){
                $this->getDataFromDatabase($parameters[0],$parameters[1], $parameters[2]);
        } else if($numberOfParameters == 4
            &&(bool)strtotime($parameters[0])
            &&(bool)strtotime($parameters[1])
            && is_array($parameters[2])
            && is_array($parameters[3])
            && count($parameters[2])<=0
            && count($parameters[3])<=0){
                $this->getDataFromDatabase($parameters[0],$parameters[1]);
        }else if($numberOfParameters == 4
            && is_int($parameters[0])
            && is_bool($parameters[1])
            && is_array($parameters[2])
            && is_array($parameters[3])
            && count($parameters[2])<=0
            && count($parameters[3])<=0){
                $this->getDataFromDatabase($parameters[0],$parameters[1]);
        }else if(is_array($parameters[0])
            && is_array($parameters[1])
            && count($parameters[0])<0
            && count($parameters[1])<0){
                //Throw Exception --> Labels and UseCase are not allowed to be empty
        }
       /*Standard Behavior without labels*/
        else if($numberOfParameters == 3 && is_string( $parameters [1])){
                //echo("First Case");
                $startDate =  $parameters [0];
                $endDate =  $parameters [1];
                $allAttributesBoolean =  $parameters [2];
                $sqlForProcessInstances =  $sqlDate = "SELECT * FROM `Process_Instance` WHERE `Date` BETWEEN '$startDate' AND '$endDate'";
                $readAttributes = $allAttributesBoolean;
                //echo($sqlForProcessInstances);
        }else if($numberOfParameters == 2 && (bool)strtotime($parameters[0]) == true && (bool)strtotime($parameters[1]) == true){
                //echo("Second Case - Just Dates and Attribute are displayed");
                $startDate =  $parameters [0];
                $endDate =  $parameters [1];
                $sqlForProcessInstances =  $sqlDate = "SELECT * FROM `Process_Instance` WHERE `Date` BETWEEN '$startDate' AND '$endDate'";
                //echo($sqlForProcessInstances);
        }else if($numberOfParameters == 2 && gettype($parameters[0]) == "integer" && gettype($parameters[1]) == "boolean"){
                $sqlForProcessInstances = "SELECT * FROM `Process_Instance` WHERE `P_ID` <= '$parameters[0]'";
                $readAttributes = $parameters[1];
                //echo($sqlForProcessInstances);
        }else if($numberOfParameters == 1){
                if(gettype($parameters[0]) == "boolean"){
                    $readAttributes = false;
                }
                if(gettype($parameters[0])== "integer"){
                    $sqlForProcessInstances = "SELECT * FROM `Process_Instance` WHERE `P_ID` <= '$parameters[0]'";
                    //echo($sqlForProcessInstances);
                }
        }else{
            echo("Something went wrong");
        }

        //First query the processInstances
        $resultForProcessInstances = mysqli_query($this->db, $sqlForProcessInstances);
        //return $resultForProcessInstances;
        while($pi =  mysqli_fetch_array($resultForProcessInstances, MYSQLI_ASSOC)){
            $processInstance = new ProcessInstance($pi["UseCase"]);
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

                //Fill up other attributes with null
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

    public function setProcessInstances($processInstanceArray){
        $this->processInstances = $processInstanceArray;
    }
}