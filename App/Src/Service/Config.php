<?php


namespace App\Src\Service;
/**
 * Class Config
 * @package App\Src\Service
 */

Class Config
{
    static private  $host = "localhost";
    static private  $dbName = "p5_blog";
    static private  $user = "root";
    static private  $passxord ="";


    static public function getVar($targetVar)
    {

            $json = json_decode(file_get_contents("App/Config/config.json"),true);

        $pathToValue = explode(' ',$targetVar);
        if(count($pathToValue) === 2)
            return $json[$pathToValue[0]][$pathToValue[1]];

        return $json[$targetVar];


    }
    static function getDBconnexion()
    {
        return [
            "host"=>self::$host,
            "dbname"=>self::$dbName,
            "user"=>self::$user,
            "password"=>self::$passxord];
    }
}