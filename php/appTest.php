<?php
/**
 * Created by PhpStorm.
 * User: danielhoschele
 * Date: 20.03.17
 * Time: 15:17
 */

require_once("Attribute.php");
require_once("Activity.php");
require_once("ProcessInstance.php");
require_once("DataBaseInterface.php");
require_once("InParser.php");

echo("<h1>This is just a test and a example</h1>");
echo("<h3>Heute Abend mach ich es dann fertig - bugt noch mit der Zuordnung der IDs</h3>");

//Aktivitäten anlegen
/*$activityStart = new Activity("Start");
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

*/
//Prozessinstanzen der Datenbankschnitstelle zuweisen und upload. Auch hier gibt es noch andere Methoden
$databaseInterface = new DataBaseInterface();
//$databaseInterface->addProcessInstance($processInstance); // Auch hier gibt es den Plural der Methode
//$databaseInterface->uploadProcessInstancesToDatabase(); //Lädt alle Prozessinstanzen im databaseInterface object in die DB

//Auslesen der letzten IDs in der Datenbank
$lastInstanceID = $databaseInterface->getLastInstanceID();
$lastActivityID = $databaseInterface->getLastActivityID();
$lastAttrID = $databaseInterface->getLastAttributeID();
$numberOfActivities = $databaseInterface->getNumberOfActivities();
$numberOfAttributes = $databaseInterface->getNumberOfAttributes();
$numberOfProcessInstances = $databaseInterface->getNumberOfProcessInstances();
$fActivity = $databaseInterface->getMostFrequentlyActivity();
$fAttr = $databaseInterface->getMostFrequentlyAttributes();
$fProcess = $databaseInterface->getMostFrequentlyProcessUseCase();



echo "Status:"."<br/>";
echo "Last InstanceID: ".$lastInstanceID."<br/>";
echo "Last ActivityID: ".$lastActivityID."<br/>";
echo "Last AttributeID: ".$lastAttrID."<br/>";
echo "Activity Numbers: ".$numberOfActivities."<br/>";
echo "Attribut Numbers: ".$numberOfAttributes."<br/>";
echo "Process Instance Numbers: ".$numberOfProcessInstances."<br/>";
echo "Frequently Activity: ".$fActivity."<br/>";
echo "Frequently Attr: ".$fAttr."<br/>";
echo "Frequently ProcessU: ".$fProcess."<br/>";

function createJson(){
    $databaseInterface = new DataBaseInterface();
    $numberAttributes = $databaseInterface->getNumberOfAttributes();
    $numberActivities = $databaseInterface->getNumberOfActivities();
    $numberProcesses = $databaseInterface->getNumberOfProcessInstances();

    $forJson = array(
        array( 'name' => 'Processes', 'number' => $numberProcesses),
        array( 'name' => 'Activities', 'number' => $numberActivities),
        array( 'label' => 'Attributes', 'number' => $numberAttributes),
    );
    $jsonData =  json_encode($forJson);
    file_put_contents('myfile.json', $jsonData);
}

createJson();



/*echo("Labels Test");
print_r($databaseInterface->getAllLabels());
print_r($databaseInterface->getAllUseCaseNames());*/
/*
echo("<pre>");
print_r($processInstance);
echo("<pre>");
print_r($databaseInterface);*/


