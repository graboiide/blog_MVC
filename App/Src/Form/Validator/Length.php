<?php


namespace App\Src\Form\Validator;


class Length extends Validator
{
    private $minLength;
    private $maxLength;
    private $defaultErrors;

    public function isValid()
    {
        $this->mergeErrors();

        /*
         * Verifie la longueur de la chaine
         */
        $length = strlen($this->fieldChild->getValue());
        if( $length < $this->minLength){
                $this->listErrors['min'] = $this->defaultErrors['min'];
            return false;
        }

        if( $length > $this->maxLength){
            $this->listErrors['max'] = $this->defaultErrors['max'];
            return false;
        }

        return  true;
    }
    private function mergeErrors()
    {
        $this->defaultErrors['min'] = 'Le champ '.$this->fieldChild->getName().' doit faire plus de '.$this->minLength.' caracteres';
        $this->defaultErrors['max'] = 'Le champ '.$this->fieldChild->getName().' doit faire moins de '.$this->maxLength.' caracteres';
        $this->defaultErrors = array_merge($this->customErrors,$this->defaultErrors);
    }


    /**
     * @param mixed $minLength
     */
    public function setMinLength($minLength): void
    {
        $this->minLength = $minLength;
    }

    /**
     * @param mixed $maxLength
     */
    public function setMaxLength($maxLength): void
    {
        $this->maxLength = $maxLength;
    }
}