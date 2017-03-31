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
    public $files;

    public function __construct($uploads)
    {
        foreach($uploads as $upload){
            $file = $this->loadFile($upload);
            $this->files[] = $file;
        }
    }

    public function loadFile($upload){
        if(!file_exists($upload)){
            return false;
        }
        $pathParts = pathinfo($upload);
        if(!$pathParts['extension'] == "xml"){
            return false;
        }
        $doc = new DOMDocument();
        $doc->preserveWhiteSpace = false; //Don't mess with whiteSpace in output
        $doc->load($upload);
        return $doc;
        //Ohne Konstruktor (um den in- und out-Parser in der gleichen Klasse zu halten)
        //$this->file = $doc;
    }

    private function readParameters($xml, $operator){
        //Read Parameters of an Operator and push in Database as Attributes
        //$parameterXPath = new DOMXPath($xml);
        //$parameterQuery = "parameter";
        //$parameters = $parameterXPath->query($parameterQuery, $operator);
        $childs = $operator->childNodes;
        $attributeArray = array();

        foreach($childs as $child){
            if($child->tagName == "parameter"){
                $attribute = new Attribute($child->getAttribute("key"), $child->getAttribute("value"));
                array_push($attributeArray, $attribute);
            }else if($child->tagName == "list"){
                $listElements = $child->childNodes;
                foreach($listElements as $listElement){
                    $attribute = new Attribute($listElement->getAttribute("key"), $listElement->getAttribute("value"));
                    array_push($attributeArray, $attribute);
                }
            }else if($child->tagName == "process"){
                $this->readProcess($xml, $child);
            }
        }
        return $attributeArray;
    }

    private function hasList($operator){
        $lists = $operator->getElementsByTagName("list");
        if($lists->length > 0){
            return $lists;
        }else{
            return null;
        }
    }

    private function readProcess($xml, $processTag)
    {
        $activityArray = array();
        if(get_class($processTag) == "DOMNodeList"){
            $process = $processTag[0];
        }else{
            $process = $processTag;
        }

        $operators = $process->getElementsByTagName("operator");
        foreach ($operators as $operator) {
            //Check the operator on parameters and sub-operators/processes
            $activity = new Activity($operator->getAttribute("name"));
            $attributes = $this->readParameters($xml, $operator);
            //Add Attributes to activity
            $activity->addAttributes($attributes);
            $activityArray[] =  $activity;
        }
        return $activityArray;
    }

    public function parseInDatabase()
    {
        $parameters  =  func_get_args();
        $useCase = $parameters[0];
        unset($parameters[0]);

        $instanceArray = array();
        $xmls = $this->files;
        if(!$xmls){
            return "No XMLs available for parsing!";
        }
        foreach($xmls as $xml){
            //Welcher Name soll hier gesetzt werden?
            $instance = new ProcessInstance($useCase);

            //Get Process Tag as StartPoint
            $xPathToProcess = new DOMXPath($xml);
            $pathToProcess = "/process/operator/process";
            $processTag= $xPathToProcess->query($pathToProcess);

            //Get complete Activities of process
            $result = $this->readProcess($xml, $processTag);

            //Add all Activities to ProcessInstance and upload it to database
            $instance->addActivities($result);
            $instance->addLabels($parameters);
            array_push($instanceArray, $instance);
        }

        $dbi = new DataBaseInterface();
        $dbi->addProcessInstances($instanceArray);
        $dbi->uploadProcessInstancesToDatabase();
        return $instanceArray;
    }

    public function parseToProcess()
    {
        $instanceArray = array();
        $xmls = $this->files;
        if(!$xmls){
            return "No XMLs available for parsing!";
        }
        foreach($xmls as $xml){
            //Welcher Name soll hier gesetzt werden?
            $instance = new ProcessInstance("directParse");

            //Get Process Tag as StartPoint
            $xPathToProcess = new DOMXPath($xml);
            $pathToProcess = "/process/operator/process";
            $processTag= $xPathToProcess->query($pathToProcess);

            //Get complete Activities of process
            $result = $this->readProcess($xml, $processTag);

            //Add all Activities to ProcessInstance and upload it to database
            $instance->addActivities($result);
            array_push($instanceArray, $instance);
        }
        return $instanceArray;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->files;
    }

}