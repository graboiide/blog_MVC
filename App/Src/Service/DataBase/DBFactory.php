<?php


namespace App\Src\Service\DataBase;


use PDO;
use PDOException;

class DBFactory
{

   static private  $instancePDO;

    /**
     * @param $params
     * @return PDO
     */
   static public function PDOMysqlDB($params):PDO
   {
       if(self::$instancePDO === null){
           try {
               $db = new PDO('mysql:host='.$params['host'].';dbname='.$params['dbname'], $params['user'], $params['password']);
               $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
               self::$instancePDO = $db;
               return $db;
           }catch (PDOException $e) {
               die($e->getMessage());
           }
       }
       else {
           return self::$instancePDO;
       }


   }

}