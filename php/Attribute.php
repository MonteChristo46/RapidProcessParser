<?php

/**
 * Created by PhpStorm.
 * User: danielhoschele
 * Date: 20.03.17
 * Time: 14:31
 */
class Attribute
{
    private $name;
    private $value;
    public function __construct($name, $value){
        $this->name = $name;
        $this->value = $value;
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
    public function getValue()
    {
        return $this->value;
    }

}