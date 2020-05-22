<?php


namespace App\Src\Service\Converter;


class NamingConverter
{

    static public function toCamelCase($name):string
    {
        $convert = '';
        //snake_case to camelCase
        foreach (explode('_',$name) as $word)
            $convert .= ucfirst($word);
        return $convert;
    }

    static public function toSnakeCase($name):string
    {
        $name = lcfirst($name);
        return strtolower(preg_replace('/[A-Z]/', '_$0', $name));
    }

}
