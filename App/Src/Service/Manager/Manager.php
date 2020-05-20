<?php


namespace App\Src\Service\Manager;


use App\Src\Service\Entity\Entity;
use PDO;

class Manager
{
    protected $propertiesEntity;
    protected $db;

    public function __construct($db)
    {
        /**
         * @var PDO $db
         */
        $this->db = $db;

    }

    public function getEntityManager($className)
    {

        if(class_exists($className)){

            /**
             * @var Entity $entity
             */
            $entity = new $className();
            $manager = ''.__NAMESPACE__.'\\'.$entity->getClass().'Manager';

            if(class_exists($manager)){
                /**
                 * @var Manager $manager
                 */
                $manager = new $manager($this->db);
                $manager->propertiesEntity = array_keys($entity->getProperties());
                return $manager;
            }

        }
        return null;
    }
}