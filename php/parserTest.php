<?php
/**
 * Created by PhpStorm.
 * User: richardhorn
 * Date: 21.03.17
 * Time: 19:33
 */

require_once("OutParser.php");
require_once("InParser.php");

$inParser = new inParser("../Beispiele Rapidminer/NeuralNet 1.xml");
$root = $inParser->parseInDatabase("UseCase 1", "", "", "", "");

echo "<pre>";
print_r($root);
