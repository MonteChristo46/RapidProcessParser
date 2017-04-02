<?php
/**
 * Created by PhpStorm.
 * User: danielhoschele
 * Date: 22.03.17
 * Time: 11:26
 */
require_once("OutParser.php");
echo("<h1>This is a test </h1>");
$varBoolean = true;
//echo("VarBoolean: ".$varBoolean ? 'true' : 'false');
$labels = array("Hallo");
$useCases = array("UseCase1", "Use Case 1", "Process Instance 2");
print_r($useCases);
$outParser = new outParser();
//$outParser->getDataFromDatabase(2, $varBoolean, $useCases, $labels);
//$outParser->getDataFromDatabase(180, $varBoolean, $useCases, $labels);
//$outParser->getDataFromDatabase(180, $varBoolean, $useCases, $labels);
//echo($outParser->getDataFromDatabase($useCases, $labels));
//echo($outParser->getDataFromDatabase($labels, "useCase"));
echo("<pre>");
//print_r($outParser->getDataFromDatabase(192, $varBoolean, $useCases, $labels));
//print_r($outParser->getDataFromDatabase("25.03.2017","29.03.2017", $varBoolean, $useCases, $labels));
print_r($outParser->getDataFromDatabase($labels, "labels"));
echo("</pre>");
$outParser->parseDataToXES();
//$outParser->parseDataToCSV();
