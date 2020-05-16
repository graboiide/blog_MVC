<?php


namespace App\Src\Service\DataBase;


use PDO;
use PDOException;

class DBFactory
{

   static public function PDOMysqlDB($params)
   {
       try {
           $db = new PDO('mysql:host='.$params['host'].';dbname='.$params['dbname'], $params['user'], $params['password']);
           $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

           return $db;
       }catch (PDOException $e) {
          die($e->getMessage());
       }

   }

}