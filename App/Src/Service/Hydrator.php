<?php


namespace App\Src\Service;


use App\Src\Service\Converter\NamingConverter;

trait Hydrator
{
    public function hydrate($data):void
    {
        //Permet de convertir les nommages entre les attributs de table et propriétés de class

        foreach ($data as $key => $value)
        {

            $method = NamingConverter::toCamelCase($key);
            if(method_exists($this,'set'.$method))
            {

                $method = 'set'.$method;
                $this->$method($value);
            }
        }
    }
}