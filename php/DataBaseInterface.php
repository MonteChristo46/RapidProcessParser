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
    private $labels;

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

    public function getAllLabels(){
        $sql = "SELECT DISTINCT `Label_1`, `Label_2`, `Label_3`, `Label_4` FROM `Label`";
        $query = mysqli_query($this->db, $sql) or die("Request failed:".mysqli_error());
        $labels = array();
        while($value = mysqli_fetch_assoc($query)){
            $labels[] = $value['Label_1'];
            $labels[] = $value['Label_2'];
            $labels[] = $value['Label_3'];
            $labels[] = $value['Label_4'];
        }
        $result = array_filter(array_unique($labels));
        return $result;
    }

    public function getAllUseCaseNames(){
        $sql = "SELECT DISTINCT `UseCase` FROM `Process_Instance` ";
        $query = mysqli_query($this->db, $sql) or die("Request failed:".mysqli_error());
        $names = array();
        while($value = mysqli_fetch_assoc($query)){
            $names[] = $value['UseCase'];
        }
        $result = array_filter(array_unique($names));
        return $result;
    }

    public function getNumberOfAttributes(){
        $sql = "SELECT COUNT(`Attr_Name`) FROM `Attribute`";
        $query = mysqli_query($this->db, $sql) or die("Request failed:".mysqli_error());
        $result = mysqli_fetch_assoc($query);
        return $result["COUNT(`Attr_Name`)"];
    }
    public function getNumberOfActivities(){
        $sql = "SELECT COUNT(`Activity_name`) FROM `Activity`";
        $query = mysqli_query($this->db, $sql) or die("Request failed:".mysqli_error());
        $result = mysqli_fetch_assoc($query);
        return $result["COUNT(`Activity_name`)"];
    }
    public function getNumberOfProcessInstances(){
        $sql = " SELECT COUNT(`P_ID`) FROM `Process_Instance`";
        $query = mysqli_query($this->db, $sql) or die("Request failed:".mysqli_error());
        $result = mysqli_fetch_assoc($query);
        return $result["COUNT(`P_ID`)"];

    }
    public function getMostFrequentlyAttributes(){
        $sql = "SELECT `Attr_Name`, COUNT(`Attr_Name`) as aNum
                FROM `Attribute`
                GROUP BY `Attr_Name`
                ORDER BY aNum DESC
                LIMIT 1";
        $query = mysqli_query($this->db, $sql) or die("Request failed:".mysqli_error());
        $result = mysqli_fetch_assoc($query);
        return $result["Attr_Name"];
    }
    public function getMostFrequentlyActivity(){
        $sql = "SELECT `Activity_name`, COUNT(`Activity_name`) as aNum
                FROM `Activity`
                GROUP BY `Activity_name`
                ORDER BY aNum DESC
                LIMIT 1";
        $query = mysqli_query($this->db, $sql) or die("Request failed:".mysqli_error());
        $result = mysqli_fetch_assoc($query);
        return $result["Activity_name"];

    }
    public function getMostFrequentlyProcessUseCase(){
        $sql = "SELECT `UseCase`, COUNT(`UseCase`) as uNum
                FROM `Process_Instance`
                GROUP BY `UseCase`
                ORDER BY uNum DESC
                LIMIT 1";
        $query = mysqli_query($this->db, $sql) or die("Request failed:".mysqli_error());
        $result = mysqli_fetch_assoc($query);
        return $result["UseCase"];
    }


    public function uploadSingleProcessInstanceToDatabase(){
        /*To-Dos*/
    }

    public function uploadProcessInstancesToDatabase(){
        foreach($this->processInstances as $processInstance){
            //Process Instance Part
            $processInstanceName = $processInstance->getName();
            $processInstanceDate =  $processInstance->getDate();
            $sqlForAddingProcessInstance = "INSERT INTO `Process_Instance`(`UseCase`, `Date`) 
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
            $countedValues = array_count_values($labels);
            if(array_key_exists(null, $countedValues)){
                if($countedValues[null] == count($labels)){
                    $status = false;
                }
            }
            if($status){
                $sqlForAddingLabels = "INSERT INTO `Label`(`P_ID`, `Label_1`, `Label_2`, `Label_3`, `Label_4`)
                                    VALUES ($instanceId, '$labels[0]', '$labels[1]', '$labels[2]', '$labels[3]')";

                mysqli_query($this->db, $sqlForAddingLabels) or die("Request failed: ".mysqli_error());
            }
            /*
             *Labels always has a length of 4 because $_GET gives back null when not entered..
             * --> When ever more labels are needed just a new $_GET statement has to putted in
             */
        }
    }
}