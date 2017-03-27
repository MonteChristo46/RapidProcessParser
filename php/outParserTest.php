<?php
/**
 * Created by PhpStorm.
 * User: danielhoschele
 * Date: 22.03.17
 * Time: 11:26
 */
require_once("OutParser.php");
echo("<h1>This is a test </h1>");
$outParser = new outParser();
$outParser->getDataFromDatabase("25.03.2017","25.03.2017", true);
$outParser->parseDataToXES();
//$outParser->parseDataToCSV();
