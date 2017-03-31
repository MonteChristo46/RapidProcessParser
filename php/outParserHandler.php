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

    //Always relevant
    $xes = convertingToBoolean($_POST['xes']);
    $csv = convertingToBoolean($_POST['csv']);

    if($_POST['type'] == "exportLastButton"){

        $files = scandir("../upload");
        $relevantFiles = array_values(array_diff($files, array('.', '..', '.DS_Store')));
        for($i=0; $i<count($relevantFiles); $i++){
            $relevantFiles[$i] = "../upload/".$relevantFiles[$i];
        }
        $processParser = new inParser($relevantFiles);
        $instanceArray = $processParser->parseToProcess();
        $createParser = new outParser();
        $createParser->setProcessInstances($instanceArray);

        if($xes && $csv){
            $createParser->parseDataToCSV();
            $createParser->parseDataToXES();
            createZipFile(array("ProcessData.csv", "ProcessData.xes"));
            echo json_encode(array("a" => "ProcessData.zip"));
        } else if ($xes) {
            $createParser->parseDataToXES();
            echo json_encode(array("a" => "ProcessData.xes"));
        } else if ($csv) {
            $createParser->parseDataToCSV();
            echo json_encode(array("a" => "ProcessData.csv"));
        }

    }else {
        //var_dump($_POST);
        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];
        $range = (int)$_POST['range'];
        $allAttr = convertingToBoolean($_POST['allAttr']);

        //Transforming Dates
        $startDateNew = new DateTime($startDate);
        $endDateNew = new DateTime($endDate);

        $outParser = new OutParser();
        if ($startDate != "" || $endDate != "") {
            $outParser->getDataFromDatabase($startDateNew->format('d.m.Y'), $endDateNew->format('d.m.Y'), $allAttr);
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
        } else if ($range > 0) {
            $outParser->getDataFromDatabase($range, $allAttr);
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

        } else {
            echo("Please adjust the range");
        }
    }
}

//Multidownload only possible with Zip file
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
