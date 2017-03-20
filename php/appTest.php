<?php
/**
 * Created by PhpStorm.
 * User: danielhoschele
 * Date: 20.03.17
 * Time: 15:17
 */

require_once ("Attribute.php");
require ("Activity.php");
require ("ProcessInstance.php");
require ("DataBaseInterface.php");


echo("<h1>This is just a test and a example</h1>");
echo("<h3>Heute Abend mach ich es dann fertig - bugt noch mit der Zuordnung der IDs</h3>");

//Aktivitäten anlegen
$activityStart = new Activity("Start");
$activityEnd = new Activity("Ende");

//Attribute erstellen
$attributeForStart0 = new Attribute("opacity", "0.5");
$attributeForStart1 = new Attribute("background-color", "#fff");

$attributeForEnd = new Attribute("Duration","3 min");

//Attribute Aktivität zuweisen --> Achtung gibt noch addAttributes: erlaubt ein array von Attributen
// reinzuladen für eine Aktivität
$activityStart->addAttribute($attributeForStart0);
$activityStart->addAttribute($attributeForStart1);
$activityEnd->addAttribute($attributeForEnd);

//Aktivitäten einer Prozessinstanz zuweisen --> Auch hier gibt es addActivities
$processInstance = new ProcessInstance("Prozessinstanz 1");
$processInstance->addActivity($activityStart);
$processInstance->addActivity($activityEnd);


//Prozessinstanzen der Datenbankschnitstelle zuweisen und upload. Auch hier gibt es noch andere Methoden
$databaseInterface = new DataBaseInterface();
$databaseInterface->addProcessInstance($processInstance); // Auch hier gibt es den Plural der Methode
$databaseInterface->uploadProcessInstancesToDatabase(); //Lädt alle Prozessinstanzen im databaseInterface object in die DB

//Die Objekte zum Anschauen --> Achtung ich muss noch date fixen
echo("<pre>");
print_r($processInstance);
echo("<pre>");
print_r($databaseInterface);