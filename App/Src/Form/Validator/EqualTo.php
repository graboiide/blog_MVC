<?php


namespace App\Src\Form\Validator;


use App\Src\Form\Field\Field;

/**
 * EqualTo Compare la valeur de deux champs les deux champs doivent avoir déja été crée dans le builder avant
 * d'ajouter le validator
 * Class EqualTo
 * @package App\Src\Form\Validator
 */
class EqualTo extends Validator
{
    private $targetField;

    public function __construct($data)
    {
        parent::__construct($data);

    }


    /**
     * @param mixed $targetField
     */
    public function setTargetField($targetField): void
    {
        $this->targetField = $targetField;
    }
    /*
     * Verifie l'galité de la valeur de deux champs (exemple: mot de passe)
     */
    private function equal(Field $field,Field $fieldTarget)
    {
        if($field->getValue() !== $fieldTarget->getValue()){
            $this->errorMessage["error_message"] = "Le champ ".$field->getName()." Doit correspondre avec le champ ".$fieldTarget->getName();
            $this->errorMessage["target"] = $fieldTarget->getName();
            return false;
        }
        return true;
    }
    public function isValid()
    {
       return $this->equal($this->fieldChild,$this->targetField);
    }
}