<?php
/**
 * Created by PhpStorm.
 * User: richardhorn
 * Date: 20.03.17
 * Time: 20:07
 */
require_once("Parser.php");
require_once ("ProcessInstance.php");
require_once ("Activity.php");
require_once ("Attribute.php");
require_once ("DataBaseInterface.php");

class inParser extends Parser
{
    public $file;

    public function __construct($upload)
    {
        $file = $this->loadFile($upload);
        $this->file = $file;
    }

    public function loadFile($upload){
        if(!file_exists($upload)){
            return "File not found!";
        }
        $pathParts = pathinfo($upload);
        if(!$pathParts['extension'] == "xml"){
            return "Please upload a XML File!";
        }
        $doc = new DOMDocument();
        $doc->preserveWhiteSpace = false; //Don't mess with whiteSpace in output
        $doc->load($upload);
        return $doc;
        //Ohne Konstruktor (um den in- und out-Parser in der gleichen Klasse zu halten)
        //$this->file = $doc;
    }

    public function parseInDatabase()
    {
        $xml = $this->file;
        $activityArray = array();
        if(!$xml){
            return "No XML available for parsing!";
        }
        //Welcher Name soll hier gesetzt werden?
        //NAME NOT IN XML
        $instance = new ProcessInstance("Process Instance 1");

        function readParameters($xml, $operator){
            //Read Parameters of an Operator and push in Database as Attributes
            $parameterXPath = new DOMXPath($xml);
            $parameterQuery = "parameter";
            $parameters = $parameterXPath->query($parameterQuery, $operator);
            $attributeArray = array();

            foreach($parameters as $parameter){
                $attribute = new Attribute($parameter->getAttribute("key"), $parameter->getAttribute("value"));
                array_push($attributeArray, $attribute);
            }

            return $attributeArray;
        }

        function hasSubprocess($operator){
            $subprocesses = $operator->getElementsByTagName("process");
            if($subprocesses->length > 0){
                return $subprocesses;
            }else{
                return null;
            }
        }

        function readProcess($xml, $processTag, $activityArray)
        {
            if(get_class($processTag) == "DOMNodeList"){
                $process = $processTag[0];
            }else{
                $process = $processTag;
            }

            $operators = $process->getElementsByTagName("operator");

            foreach ($operators as $operator) {
                //Check the operator on parameters and sub-operators/processes
                $activity = new Activity($operator->getAttribute("name"));
                $attributes = readParameters($xml, $operator);
                //Add Attributes to activity
                $activity->addAttributes($attributes);
                array_push($activityArray, $activity);

                $subprocesses = hasSubprocess($operator);
                if ($subprocesses) {
                    foreach ($subprocesses as $subprocess) {
                        readProcess($xml, $subprocess, $activityArray);
                    }
                }
            }
            //Is returning four times because of recursive call of function

        }

        //return $activityArray;
        //Get Process Tag as StartPoint
        $xPathToProcess = new DOMXPath($xml);
        $pathToProcess = "/process/operator/process";
        $processTag= $xPathToProcess->query($pathToProcess);
        //echo "Tasks: ".$process->length."<br/>";

        $activityArray = array();
        readProcess($xml, $processTag, $activityArray);
        echo "<pre>";
        print_r($activityArray);

        //$allActivities = readProcess($xml, $processTag);
        $instance->addActivities($activityArray);

        return $instance;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

}