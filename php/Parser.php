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

    public function __construct($upload)
    {
        $file = $this->loadFile($upload);
        $this->file = $file;
    }

    public function loadFile($upload){
        if(!file_exists($upload)){
            return "File not found!";
        }
        $pathParts = pathinfo($upload);
        if(!$pathParts['extension'] == "xml"){
            return "Please upload a XML File!";
        }
        $doc = new DOMDocument();
        $doc->load($upload);
        return $doc;
    }

    public function parseInDatabase()
    {
        /**TO DO**/
    }

    public function parseOutOfDatabase()
    {
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