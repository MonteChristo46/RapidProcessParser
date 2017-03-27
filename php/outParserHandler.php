<?php
/**
 * Created by PhpStorm.
 * User: danielhoschele
 * Date: 27.03.17
 * Time: 10:34
 */
require_once("OutParser.php");

if(!empty($_POST)){
    //Converting Strings to boolean
    function convertingToBoolean($string){
        if($string === "false"){
            return false;
        }else if($string === "true") {
            return true;
        }
    }

    var_dump($_POST);
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    $range = (int)$_POST['range'];
    $allAttr = convertingToBoolean($_POST['allAttr']);
    $xes = convertingToBoolean($_POST['xes']);
    $csv = convertingToBoolean($_POST['csv']);



    //Transforming Dates
    $startDateNew = new DateTime($startDate);
    $startDateNew->format('d.m.Y');
    $endDateNew = new DateTime($startDate);
    $endDateNew->format('d.m.Y');


    $outParser = new OutParser();
    if($startDate != "" || $endDate != ""){
        $outParser->getDataFromDatabase($startDateNew, $endDateNew, $allAttr);
        if($xes){
            $outParser->parseDataToXES();
        }
        if($csv){
            $outParser->parseDataToCSV();
        }
    }else if($range > 0){
        $outParser->getDataFromDatabase($range, $allAttr);
        if($xes){
            $outParser->parseDataToXES();
        }
        if($csv){
            $outParser->parseDataToCSV();
        }
    }else{
        echo("Please adjust the range");
    }
}
