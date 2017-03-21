<?php
/**
 * Created by PhpStorm.
 * User: richardhorn
 * Date: 20.03.17
 * Time: 20:07
 */
require_once("Parser.php");
require_once ("ProcessInstance.php");
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
        if(!$xml){
            return "No XML available for parsing!";
        }
        //Welcher Name soll hier gesetzt werden?
        $instance = new ProcessInstance("Process Instance 1");

        //Process Tag
        $root = $xml->documentElement;

        function readAndStoreParameters($operator){
            //Read Parameters of an Operator and push in Database as Attributes
            echo "done"."<br/>";
        }

        function hasSubprocess($operator){
            $subprocess = $operator->getElementsByTagName("process");
            if($subprocess->length > 0){
                return true;
            }else{
                return false;
            }
        }
        //Jump directly to correct process tag (CHECK IF ALWAYS CORRECT!!)
        /*
        $xPath = new DOMXPath($xml);
        $xpathQuery = "/process/operator/process"; //Take the second process tag!
        $queryResult = $xPath->query($xpathQuery);
        //Always correct (If more than one Process Tag can be found here, the first will be the right start)
        $start = $queryResult[0];*/

        //Loop through all Operators beneath "process" and put them as Activity -> call readAndStoreParameters
        $xpathToOperators = new DOMXPath($xml);
        $pathToOperators = "/process/operator/process/operator";
        $tasks = $xpathToOperators->query($pathToOperators);

        foreach($tasks as $task){
            //Check the operator on parameters and sub-operators/processes
            readAndStoreParameters($task);
            if(hasSubprocess($task)){
                echo $task->getAttribute("name")." hat Subprozesse!";
            }
        }

    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

}