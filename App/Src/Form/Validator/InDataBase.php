<?php


namespace App\Src\Form\Validator;


use App\Src\Service\Config;
use App\Src\Service\DataBase\DBFactory;
use App\Src\Service\Manager\BackManager;
use App\Src\Service\Manager\Manager;

class InDataBase extends Validator
{
    private $entityClassName;

    /**
     * @param mixed $entityClassName
     */
    public function setEntityClassName($entityClassName): void
    {
        $this->entityClassName = $entityClassName;
    }
    /*
     * Va verifier que la valeur du champ n'existe deja pas en base de donnée
     */
    public function isValid()
    {
        /**
         * @var Manager $manager
         */
        $manager = new Manager(DBFactory::PDOMysqlDB(Config::getVar('database')));
        $managerEntity =  $manager->getEntityManager($this->entityClassName);
        /**
         * @var BackManager $managerEntity
         */

        $empty = $managerEntity->isEmpty(new $this->entityClassName(),$this->fieldChild->getName(),$this->fieldChild->getValue());

        if(!$empty){
            if(is_null($this->customErrors))
                $this->errorMessage['empty'] = "La valeur ". $this->fieldChild->getValue()." est déja prise veuillez en choisir une autre";
            else
                $this->errorMessage['empty'] = $this->customErrors;
            return false;
        }
        return true;
    }
}