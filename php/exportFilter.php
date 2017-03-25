<?php
/**
 * Created by PhpStorm.
 * User: richardhorn
 * Date: 25.03.17
 * Time: 11:51
 */
if(isset($_POST['export'])){
    if($_POST['export'] == true){
        createFilterSettings();
    }
}

function createFilterSettings(){
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    $range = $_POST['range'];
    $allAttr = $_POST['allAttr'];
    $xes = $_POST['xes'];
    $csv = $_POST['csv'];

    $filter = array(
        "startDate" => $startDate,
        "endDate" => $endDate,
        "range" => $range,
        "allAttr" => $allAttr,
        "asXES" => $xes,
        "asCSV" => $csv
    );

    echo "<pre>";
    print_r($filter);
    //Start Parsing from filter!
}

