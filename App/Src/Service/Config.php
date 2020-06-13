<?php


namespace App\Src\Service;
/**
 * Class Config
 * @package App\Src\Service
 */

class Config
{

    static public function getVar($targetVar)
    {

            $json = json_decode(file_get_contents("App/Config/config.json"),true);

        $pathToValue = explode(' ',$targetVar);
        if(count($pathToValue) === 2)
            return $json[$pathToValue[0]][$pathToValue[1]];

        return $json[$targetVar];


    }
}