<?php
/**
 * Created by PhpStorm.
 * User: richardhorn
 * Date: 23.03.17
 * Time: 14:43
 */

function alert($message){
    echo '<script type="text/javascript" language="Javascript">';
    echo 'alert("'.$message.'");';
    echo '</script>';
}
require_once ("php/DataBaseInterface.php");
require_once ("php/InParser.php");

//Get relevant numbers for interface
$dbi = new DataBaseInterface();
$instances = $dbi->getLastInstanceID();
$activities = $dbi->getLastActivityID();
$attributes = $dbi->getLastAttributeID();
$oldest = $dbi->getDateOfFirstInstance();
$newest = $dbi->getDateOfLastInstance();
$instancesHeight = ($instances/($attributes*1.2))*100;
$activitiesHeight = ($activities/($attributes*1.2))*100;
$attributesHeight = ($attributes/($attributes*1.2))*100;
//Upload the given files
if(isset($_POST['submit'])){
    $count = 0;
    $uploadedFiles = count($_FILES['upload']['name']);

    if($uploadedFiles > 0){
        for($i=0; $i<count($_FILES['upload']['name']); $i++) {

            if ($_FILES['upload']['extension'] != "xml") {
                echo "Error: Please upload XML Files";
            } else {
                $tmpFilePath = $_FILES['upload']['tmp_name'][$i];
                if ($tmpFilePath != "") {

                    $shortname = $_FILES['upload']['name'][$i];
                    $filePath = "upload/" . $_FILES['upload']['name'][$i];

                    if (move_uploaded_file($tmpFilePath, $filePath)) {

                        $files[] = $shortname;
                        $count += 1;
                        $parser = new inParser($filePath);
                        $parser->parseInDatabase();//Anpassen wenn Abstrakte Klasse fertig!
                    }
                }
            }
        }
    }
    if($count == $uploadedFiles){
        alert("Success: Uploaded and stored ".$count." File(s) in Database!");
    }
}


