<?php

/**
 * Created by PhpStorm.
 * User: danielhoschele
 * Date: 20.03.17
 * Time: 13:59
 */

class DataBaseInterface
{
    private $db;
    private $processInstances; //List of processInstances

    public function __construct(){
        include("config.php");
        $this->db = mysqli_connect(
            MYSQL_HOST,
            MYSQL_USER,
            MYSQL_PASSWORD,
            MYSQL_DATABASE_NAME
        );

        if($this->db){
            return $this->db;
        }else{
            echo(mysqli_error());
        }
    }

    public function addProcessInstance($processInstance){
        $this->processInstances[] = $processInstance;
    }
    //Adding array of processInstance objects
    public function addProcessInstances($processInstances){
        foreach($processInstances as $processInstance){
            $this->processInstances[] = $processInstance;
        }
    }
    //Getting the lastInstanceID that was stored in the database
    public function getLastInstanceID(){
        $sqlForGettingId = "SELECT max(P_ID) FROM `Process_Instance`";
        $query = mysqli_query($this->db, $sqlForGettingId) or die("Request failed:".mysqli_error());
        $result = mysqli_fetch_assoc($query);
        return $result['max(P_ID)'];
    }

    public function getLastActivityID(){
        $sqlForGettingId = "SELECT max(A_ID) FROM `Activity`";
        $query = mysqli_query($this->db, $sqlForGettingId) or die("Request failed:".mysqli_error());
        $result = mysqli_fetch_assoc($query);
        return $result['max(A_ID)'];
    }
    //Benötigt? --> Eigentlich nicht --> Löschen?
    public function getLastAttributeID(){
        $sqlForGettingId = "SELECT max(Attr_ID) FROM `Attribute`";
        $query = mysqli_query($this->db, $sqlForGettingId) or die("Request failed:".mysqli_error());
        $result = mysqli_fetch_assoc($query);
        return $result['max(Attr_ID)'];
    }

    public function uploadSingleProcessInstanceToDatabase(){
        /*To-Dos*/
    }


    /*PROBLEM: Die ID für ProcessInstance und Aktivität werden automatisch von der Datenbank vergeben, Wie kriege ich die raus?
    *Ist wichtig um eine Aktivität einer Prozessinstanz zuzuordnen oder Attribute einer Aktivität
    */
    public function uploadProcessInstancesToDatabase(){
        foreach($this->processInstances as $processInstance){
            //Process Instance Part
            $processInstanceName = $processInstance->getName();
            $processInstanceDate =  $processInstance->getDate();
            $sqlForAddingProcessInstance = "INSERT INTO `Process_Instance`(`Name`, `Date`) 
                    VALUES ('$processInstanceName','$processInstanceDate')";
            mysqli_query($this->db, $sqlForAddingProcessInstance) or die("Request failed: " . mysqli_error());

            //Activity Part
            $instanceId = $this->getLastInstanceID();
            $activities = $processInstance->getActivities();
            foreach($activities as $activity){
                $activityName = $activity->getName();
                $sqlForAddingActivity = "INSERT INTO `Activity`(`P_ID`, `Activity_name`) 
                                        VALUES ($instanceId, '$activityName')";
                mysqli_query($this->db, $sqlForAddingActivity) or die("Request failed: " . mysqli_error());

                //Attribute Part
                $activityID = $this->getLastActivityID();
                $attributes = $activity->getAttributes();
                foreach($attributes as $attribute){
                    $attributeName = $attribute->getName();
                    $attributeValue = $attribute->getValue();

                    $sqlForAddingAttribute = "INSERT INTO `Attribute`(`A_ID`, `Attr_Name`, `Attr_Value`) 
                                              VALUES ($activityID, '$attributeName', '$attributeValue')";

                    mysqli_query($this->db, $sqlForAddingAttribute) or die("Request failed: " . mysqli_error());


                }
            }

        }
    }



}