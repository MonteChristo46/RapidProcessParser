<?php

/**
 * Created by PhpStorm.
 * User: danielhoschele
 * Date: 20.03.17
 * Time: 14:23
 */
class ProcessInstance
{
    private $name;
    private $date;
    private $activities; // List of all activities
    private $id;

    public function __construct($name){
        $this->name = $name;
        $this->date = date("d.m.Y", time()); //Returns date as String not as Object

        /*
        $date = $this->getDate();
        $this->date = $date["mday"].".".$date["mon"].".".$date["year"]; //Funzt noch nicht
        */
    }

    //if you want to add a single activity
    public function addActivity($activity){
        $this->activities[] = $activity;
    }

    //If you want to add a list of activities
    public function addActivities($array){
        foreach($array as $arr){
            $this->activities[] = $arr;
        }
    }

    /**
     * @return mixed
     */
    public function getActivities()
    {
        return $this->activities;
    }
    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $Id
     */
    public function setId($Id)
    {
        $this->id = $Id;
    }

}