<?php
/**
 * Created by PhpStorm.
 * User: danielhoschele
 * Date: 22.03.17
 * Time: 11:26
 */
require_once("OutParser.php");
$outParser = new outParser();
$outParser->getDataFromDatabase();
$outParser->parseDataToXES();
$outParser->parseDataToCSV();
