<?php

namespace App\Src\Service\Entity;

use App\Src\Service\Converter\NamingConventionConverter;

abstract class Entity
{

    protected $id;

    public function __construct($data)
    {
        $this->hydrate($data);
    }

    /**
     * Hydrate the object
     * @param $data
     * @return void
     */
    public function hydrate($data):void
    {
        //Permet de convertir les nommages entre les attributs de table et propriétés de class
        $conventionConverter = new NamingConventionConverter();
        foreach ($data as $key => $value)
        {
            $method = $conventionConverter->snakeCaseToCamelCase($key);
            if(method_exists($this,'get'.$method))
            {
                $method = 'set'.$method;
                $this->$method($value);
            }
        }
    }

    public function getId()
    {
        return $this->id;

    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }
    public function getProperties()
    {
       return get_object_vars($this);
    }
    public function getClass()
    {
        $ncc = new NamingConventionConverter();
        $className = explode('\\',get_called_class());
        $className = $ncc->pascalCaseToSnakeCase(array_pop($className));
        return $className = substr($className, 0, -7);
    }




}