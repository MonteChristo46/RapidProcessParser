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
    }

    private function readParameters($operator){
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
                $this->readProcess($child);
            }
        }
        return $attributeArray;
    }

    private function readProcess($processTag)
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
            $attributes = $this->readParameters($operator);
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
            $instance = new ProcessInstance($useCase);
            //Get Process Tag as StartPoint
            $xPathToProcess = new DOMXPath($xml);
            $pathToProcess = "/process/operator/process";
            $processTag= $xPathToProcess->query($pathToProcess);

            //Get complete Activities of process
            $result = $this->readProcess($processTag);

            //Add all Activities to ProcessInstance and upload it to database
            $instance->addActivities($result);
            $instance->addLabels($parameters);
            array_push($instanceArray, $instance);
        }

        $this->storeDataInDatabase($instanceArray);
        return $instanceArray;
    }

    private function storeDataInDatabase($instances){
        $dbi = new DataBaseInterface();
        $dbi->addProcessInstances($instances);
        $dbi->uploadProcessInstancesToDatabase();
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