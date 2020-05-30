<?php

namespace App\Src\Form\Field;
use App\Src\Form\Validator\Validator;
use App\Src\Service\Hydrator;

abstract class Field implements FieldInterface
{
    protected $name;
    protected $value;
    protected $id;
    protected $validators;
    protected $placeholder;
    protected $required = false;
    protected $errors;

    public function __construct($data)
    {
        $this->hydrate($data);
    }
    use Hydrator;

    public function getErrors(){
        return $this->errors;
    }
    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $placeholder
     */
    public function setPlaceholder($placeholder): void
    {

        $this->placeholder = 'placeholder="'.$placeholder.'"';
    }

    /**
     * @param bool $required
     */
    public function setRequired(bool $required): void
    {
        $this->required = 'required="'.$required.'"';
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    public function addValidator(Validator $validator)
    {
        $this->validators[] = $validator;
    }
    public function getValue()
    {
        return isset($this->value) ? $this->value : '' ;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value): void
    {
        $this->value = $value;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }


    /**
     * Boucle les validator et affecte les messages d'erreur dans un tableau
     * @return bool
     */
    public function isValid()
    {
        $valid = true;
        /**
         * @var Validator $validator
         */
        foreach ((array)$this->validators as $validator){
            if (!$validator->isValid()){
                $valid = false;
                $this->errors = $validator->getErrorMessage();
            }

        }
        return $valid;
    }
}