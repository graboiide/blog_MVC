<?php

namespace App\Src\Service\Entity;

use App\Src\Service\Converter\NamingConverter;
use App\Src\Service\Hydrator;

abstract class Entity
{

    protected $id;

    public function __construct($data = [])
    {
        $this->hydrate($data);
    }

    /**
     * Hydrate the object
     * @param $data
     * @return void
     */
    use Hydrator;

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


    public function extractAttributes($onlyKey = true,$filter = true)
    {
        $attributes = [];
        $propertiesEntity = $filter ? array_filter(get_object_vars($this)) : get_object_vars($this);
        foreach ($propertiesEntity as $key => $value )
            $attributes[NamingConverter::toSnakeCase($key)] = $value;

            return $onlyKey ? array_keys($attributes) : $attributes;

    }

    /**
     * Récupere le nom de la table dans la base de donnée a partir du nom de la class
     * @return string
     */
    public function extractTable()
    {
        $className = explode('\\',get_called_class());
        $className = substr(array_pop($className), 0, -6);
        return NamingConverter::toSnakeCase($className);
    }
    public function getProperties()
    {
       return get_object_vars($this);
    }

    public function getClass()
    {
        $className = explode('\\',get_called_class());
        return $className = substr(array_pop($className), 0, -6);
    }




}