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
echo("VarBoolean: ".$varBoolean ? 'true' : 'false');
$outParser = new outParser();
$outParser->getDataFromDatabase(5, $varBoolean);
$outParser->parseDataToXES();
//$outParser->parseDataToCSV();
