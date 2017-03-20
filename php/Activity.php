<?php

/**
 * Created by PhpStorm.
 * User: danielhoschele
 * Date: 20.03.17
 * Time: 14:30
 */
class Activity
{
    private $name;
    private $attributes;

    public function __construct($name){
        $this->name = $name;
    }


    //Muss vom Typ Attribute sein --> Drecks untypisierte Scheiße!
    //Sollte was zurückgeben
    public function addAttribute($attribute){
        $this->attributes[] = $attribute;
    }

    //Parameter is array of attribute objects
    public function addAttributes($attributes){
        foreach($attributes as $attribute){
            $this->attributes[] = $attribute;
        }
    }
    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
}