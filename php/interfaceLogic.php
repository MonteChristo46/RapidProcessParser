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
require_once ("DataBaseInterface.php");
require_once ("InParser.php");

//Get relevant numbers for interface
$dbi = new DataBaseInterface();
$instances = $dbi->getLastInstanceID();
$activities = $dbi->getLastActivityID();
$attributes = $dbi->getLastAttributeID();
$oldest = $dbi->getDateOfFirstInstance();
$newest = $dbi->getDateOfLastInstance();

//When Database is empty data should not throw errors but be set on 0
//If clause because division by 0 throws errors
if($instances != 0 && $attributes != 0){
    $instancesHeight = ($instances/($attributes*1.2))*100;
}else{
    $instancesHeight = 0;
}

if($activities != 0 && $attributes != 0){
    $activitiesHeight = ($activities/($attributes*1.2))*100;
}else{
    $activitiesHeight = 0;
}

if($attributes != 0){
    $attributesHeight = ($attributes/($attributes*1.2))*100;
}else{
    $attributesHeight = 0;
}

//Upload the given files
if(isset($_POST['submit'])){
    $count = 0;
    $uploadedFiles = count($_FILES['upload']['name']);

    if($uploadedFiles > 0){
        for($i=0; $i<count($_FILES['upload']['name']); $i++) {

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
    if($count == $uploadedFiles){
        alert("Success: Uploaded and stored ".$count." File(s) in Database!");
    }
}


