<?php

namespace App\Src\Service\Entity;

class Entity
{

    private $id;

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
        foreach ($data as $key => $value)
        {

            $method = '';
            //snake_case to camelCase
            foreach (explode('_',$key) as $word){
                $method .= ucfirst($word);

            }

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

}