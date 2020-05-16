<?php


namespace App\Src\Service\Converter;


class NamingConventionConverter
{
    private $saveConverting = [];

    public function snakeCaseToCamelCase($name):string
    {
        $convert = '';
        //snake_case to camelCase
        foreach (explode('_',$name) as $word){
            $convert .= ucfirst($word);

        }
        $this->saveConverting[$convert] = $name;
        return $convert;
    }

    public function camelCaseToSnakeCase($name):string
    {
        return strtolower(preg_replace('/[A-Z]/', '_$0', $name));
    }

    public function pascalCaseToSnakeCase($name):string
    {
        return substr($this->camelCaseToSnakeCase($name),1);
    }
}