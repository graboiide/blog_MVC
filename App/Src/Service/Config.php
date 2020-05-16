<?php


namespace App\Src\Service;
/**
 * Class Config
 * @package App\Src\Service
 */

class Config
{
    private $vars = [];

    public function getVar($targetVar)
    {
        if(!$this->vars)
        {
            $this->vars = $json = json_decode(file_get_contents("../Config/config.json"),true);
        }
        $pathToValue = explode(' ',$targetVar);
        if(sizeof($pathToValue) === 2)
            return $this->vars[$pathToValue[0]][$pathToValue[1]];
        else
        {
            return $this->vars[$targetVar];
        }

    }
}