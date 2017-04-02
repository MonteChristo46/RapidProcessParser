<?php
/**
 * Created by PhpStorm.
 * User: danielhoschele
 * Date: 31.03.17
 * Time: 23:26
 */
require_once ("DataBaseInterface.php");

    $databaseInterface = new DataBaseInterface();
    $numberAttributes = $databaseInterface->getNumberOfAttributes();
    $numberActivities = $databaseInterface->getNumberOfActivities();
    $numberProcesses = $databaseInterface->getNumberOfProcessInstances();

    $forJson = array(
        array( 'name' => 'Processes', 'number' => $numberProcesses),
        array( 'name' => 'Activities', 'number' => $numberActivities),
        array( 'name' => 'Attributes', 'number' => $numberAttributes),
    );
    $jsonData =  json_encode($forJson);
    //file_put_contents('myfile.json', $jsonData);
    echo $jsonData;
