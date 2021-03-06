<?php
/**
 * Created by PhpStorm.
 * User: danielhoschele
 * Date: 27.03.17
 * Time: 10:34
 */
require_once("OutParser.php");
require_once("InParser.php");
if(!empty($_POST)) {
    //Converting strings to boolean
    function convertingToBoolean($string)
    {
        if ($string === "false") {
            return false;
        } else if ($string === "true") {
            return true;
        }
    }
    //Describes Logic when XES or CSV is parsed
    function parserLogic($xes, $csv, $outParser){
        if ($xes && $csv) {
            $outParser->parseDataToCSV();
            $outParser->parseDataToXES();
            createZipFile(array("ProcessData.csv", "ProcessData.xes"));
            echo json_encode(array("a" => "ProcessData.zip"));
        } else if ($xes) {
            $outParser->parseDataToXES();
            echo json_encode(array("a" => "ProcessData.xes"));
        } else if ($csv) {
            $outParser->parseDataToCSV();
            echo json_encode(array("a" => "ProcessData.csv"));
        }
    }
    //Zip Download Option
    function createZipFile($files){
        $zip = new ZipArchive();
        $fileName = "ProcessData.zip";
        if(file_exists($fileName)){
            unlink($fileName);
        }
        $zip->open("ProcessData.zip", ZipArchive::CREATE);
        foreach($files as $file){
            $zip->addFile($file);
        }
        $zip->close();
    }
    //Global Variables
    $xes = convertingToBoolean($_POST['xes']);
    $csv = convertingToBoolean($_POST['csv']);
    $outParser = new OutParser();
    if($_POST['type'] == "exportLastButton"){

        $files = scandir("../upload");
        $relevantFiles = array_values(array_diff($files, array('.', '..', '.DS_Store')));
        for($i=0; $i<count($relevantFiles); $i++){
            $relevantFiles[$i] = "../upload/".$relevantFiles[$i];
        }
        $processParser = new inParser($relevantFiles);
        $instanceArray = $processParser->parseToProcess();
        //$createParser = new outParser();
        $outParser->setProcessInstances($instanceArray);

        parserLogic($xes, $csv, $outParser);

    }else {
        //Delete fix value - because AJAX Call can't contain empty array
        function deleteHashFromArray($arr){
            unset($arr[0]);
            return $arr;
        }
        //var_dump($_POST);
        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];
        $range = (int)$_POST['range'];
        $allAttr = convertingToBoolean($_POST['allAttr']);
        $useCases = deleteHashFromArray($_REQUEST['useCases']);
        $labels = deleteHashFromArray($_REQUEST['labels']);

        /*Why? I dont know - I think it is because of wrong format*/
        $neu = array(); //$useCases
        foreach($useCases as $us){
            $neu[]=$us;
        }
        $neu2 = array();
        foreach($labels as $l){
            $neu2[]=$l;
        }
        //Transforming Dates
        $startDateNew = new DateTime($startDate);
        $endDateNew = new DateTime($endDate);

        // Calling Parse Function and get the Data from MySQL Database depending of input parameters
        if ($startDate != "" || $endDate != "") {
            $outParser->getDataFromDatabase($startDateNew->format('d.m.Y'), $endDateNew->format('d.m.Y'), $allAttr, $neu, $neu2);
            //print_r($outParser->getDataFromDatabase($startDateNew->format('d.m.Y'), $endDateNew->format('d.m.Y'), $allAttr, $useCases, $labels));
            parserLogic($xes, $csv, $outParser);
        } else if ($range > 0) {
            $outParser->getDataFromDatabase($range, $allAttr, $neu, $neu2);
            //print_r( $outParser->getDataFromDatabase($range, $allAttr, $useCases, $labels));
            parserLogic($xes, $csv, $outParser);
        }else if(count($neu)<=0){
            $outParser->getDataFromDatabase($neu2, "labels");
        }else if(count($neu2)<=0){
            $outParser->getDataFromDatabase($neu, "useCases");
        }
        else {
            echo("Please adjust the range");
        }
    }
}


