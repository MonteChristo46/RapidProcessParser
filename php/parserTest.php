<?php
/**
 * Created by PhpStorm.
 * User: richardhorn
 * Date: 21.03.17
 * Time: 19:33
 */

require("outParser.php");
require("inParser.php");

$inParser = new inParser("../Beispiele Rapidminer/Decision Tree 1.xml");
$root = $inParser->parseInDatabase();

echo "<pre>";
print_r($root);

echo "<pre>";
print_r($inParser);