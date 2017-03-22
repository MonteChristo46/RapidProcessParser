<?php
/**
 * Created by PhpStorm.
 * User: danielhoschele
 * Date: 22.03.17
 * Time: 11:26
 */
require_once("OutParser.php");
$outParser = new outParser();

echo("This is a test");
echo("<pre>");
$result = $outParser->getDataFromDataBase();

print_r($result);