<?php
/**
 * Created by PhpStorm.
 * User: richardhorn
 * Date: 20.03.17
 * Time: 20:07
 */

class Parser
{
    public $file;

    public function parseInDatabase(){
        /**TO DO**/
    }

    public function parseOutOfDatabase(){
        /**TO DO**/
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }


}