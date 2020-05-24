<?php


namespace App\Src\Form;


use App\Src\Form\Field\Field;
use App\Src\Service\Entity\Entity;
use App\Src\Service\HTTP\HttpRequest;

class FormBuilder
{
    private $fields;
    private $entity;

    public function __construct(Entity $entity)
    {
        $this->entity = $entity;

    }

    /**
     * @param $name
     * @return mixed
     */
    public function getField($name)
    {

        return $this->fields[$name];
    }

    public function getFields()
    {

        return $this->fields;
    }

    public function addField($classNameField,$data)
    {
        $data2 = $this->entity->extractAttributes(false,false);
        //auto complete
        /*if(array_key_exists($data["name"],$data2) && $data2[$data["name"]] != '')
            $data["value"] = $data2[$data["name"]];*/

        $this->fields[$data["name"]] = $this->classNameGenerate($classNameField,$data);

        return $this;
    }

    /**
     * Ajoute un validateur a un champ
     * @param $classNameValidator
     * @return $this
     */
    public function addValidator($classNameValidator,$data=[])
    {
        $lastField = end($this->fields);
        if(isset($data['targetField']))
            $data['targetField']=$this->fields[$data['targetField']];

        $data = array_merge($data, ['fieldChild'=>$lastField]);

        /**
         * @var Field $lastField
         */

       $lastField->addValidator($this->classNameGenerate($classNameValidator,$data));
       return $this;
    }
    private function classNameGenerate($className,$data = [])
    {
        if(class_exists($className)){
            return new $className($data);
        }
        return null;
    }
    public function createForm(HttpRequest $request)
    {
        $attributesEntity = $this->entity->extractAttributes(false,false);
        /**
         * @var Field $field
         */
        //hydratation des field
        foreach ($this->fields as $key => $field)
        {
            if(array_key_exists($key,$attributesEntity))
                $field->setValue($attributesEntity[$key]);
        }
        return new Form($this,$request);
    }
    public function getEntity(){
        return $this->entity;
    }

}