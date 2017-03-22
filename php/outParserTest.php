<?php
/**
 * Created by PhpStorm.
 * User: danielhoschele
 * Date: 22.03.17
 * Time: 11:26
 */
require_once("outParser.php");
$outParser = new outParser();

echo("This is a test");
echo("<pre>");
$result = $outParser->getDataFromDataBase();

while($row =  mysqli_fetch_array($result, MYSQLI_ASSOC)){
    echo("ID: ".$row["P_ID"]."  ");
    echo("Process Name: ".$row["Name"]."</br>");
}
