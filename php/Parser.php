<?php
/**
 * Created by PhpStorm.
 * User: richardhorn
 * Date: 21.03.17
 * Time: 19:10
 */

abstract class Parser
{
    protected $databaseConfig;


    public function __construct(){
        include_once("config.php");
        $this->databaseConfig = mysqli_connect(
            MYSQL_HOST,
            MYSQL_USER,
            MYSQL_PASSWORD,
            MYSQL_DATABASE_NAME
        );

        if($this->databaseConfig){
            return $this->databaseConfig;
        }else{
            echo(mysqli_error());
        }
    }

    protected function getDatabaseConfig(){
        return $this->databaseConfig;
    }
}