<?php


namespace App\Src\Form\Validator;


use App\Src\Service\Config;
use App\Src\Service\DataBase\DBFactory;
use App\Src\Service\Manager\BackManager;
use App\Src\Service\Manager\Manager;

class InDataBase extends Validator
{
    private $entityClassName;
    private $attribute;

    /**
     * @param mixed $entityClassName
     */
    public function setEntityClassName($entityClassName): void
    {
        $this->entityClassName = $entityClassName;
    }

    private function setAttribute($attr)
    {
        $this->attribute = $attr;
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
        $attribute = !is_null($this->attribute) ? $this->attribute : $this->fieldChild->getName();
        $empty = $managerEntity->isEmpty(new $this->entityClassName(),$attribute,$this->fieldChild->getValue());

        if(!$empty){
            if(is_null($this->customErrors))
                $this->listErrors['empty'] = "La valeur ". $this->fieldChild->getValue()." est déja prise veuillez en choisir une autre";
            else
                $this->listErrors['empty'] = $this->customErrors;
            return false;
        }
        return true;
    }
}