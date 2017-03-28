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
        include_once("config.php");
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
    //Für Attribut benötigt? --> Eigentlich nicht --> Löschen?
    public function getLastAttributeID(){
        $sqlForGettingId = "SELECT max(Attr_ID) FROM `Attribute`";
        $query = mysqli_query($this->db, $sqlForGettingId) or die("Request failed:".mysqli_error());
        $result = mysqli_fetch_assoc($query);
        return $result['max(Attr_ID)'];
    }

    public function getDateOfFirstInstance(){
        $sql = "SELECT min(Date) FROM `Process_Instance`";
        $query = mysqli_query($this->db, $sql) or die("Request failed:".mysqli_error());
        $result = mysqli_fetch_assoc($query);
        return $result['min(Date)'];
    }

    public function getDateOfLastInstance(){
        $sql = "SELECT max(Date) FROM `Process_Instance`";
        $query = mysqli_query($this->db, $sql) or die("Request failed:".mysqli_error());
        $result = mysqli_fetch_assoc($query);
        return $result['max(Date)'];
    }


    public function uploadSingleProcessInstanceToDatabase(){
        /*To-Dos*/
    }

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
                if(count($attributes) > 0) {
                    foreach ($attributes as $attribute) {
                        $attributeName = $attribute->getName();
                        $attributeValue = $attribute->getValue();

                        $sqlForAddingAttribute = "INSERT INTO `Attribute`(`A_ID`, `Attr_Name`, `Attr_Value`) 
                                              VALUES ($activityID, '$attributeName', '$attributeValue')";

                        mysqli_query($this->db, $sqlForAddingAttribute) or die("Request failed: " . mysqli_error());


                    }
                }
            }
            //Label part
            $labels = $processInstance->getLabels();
            $status = true;
            //Check if all values in array are null --> If yes, don't push to database
            $countedValues = array_count_values($labels);
            if(array_key_exists(null, $countedValues)){
                if($countedValues[null] == 5){
                    $status = false;
                }
            }

            if($status){
                $sqlForAddingLabels = "INSERT INTO `Label`(`P_ID`, `Label_1`, `Label_2`, `Label_3`, `Label_4`, `Label_5`)
                                    VALUES ($instanceId, '$labels[0]', '$labels[1]', '$labels[2]', '$labels[3]', '$labels[4]')";

                mysqli_query($this->db, $sqlForAddingLabels) or die("Request failed: ".mysqli_error());
            }
            /*
             *Labels always has a length of 5 because $_GET gives back null when not entered..
             * --> When ever more labels are needed just a new $_GET statement has to putted in
             */
        }
    }
}