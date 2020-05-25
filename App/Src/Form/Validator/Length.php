<?php


namespace App\Src\Form\Validator;


class Length extends Validator
{
    private $minLength;
    private $maxLength;

    public function isValid()
    {
        /*
         * Verifie la longueur de la chaine
         */

        $length = strlen($this->fieldChild->getValue());
        if( $length < $this->minLength){
            if(is_null($this->customErrors))
                $this->errorMessage['min'] = 'Le champ '.$this->fieldChild->getName().' doit faire plus de '.$this->minLength.' caracteres';
            else
                $this->errorMessage['min'] = $this->customErrors['min'];

            return false;
        }

        if( $length > $this->maxLength){
            if(is_null($this->customErrors))
                $this->errorMessage['max'] = 'Le champ '.$this->fieldChild->getName().' doit faire moins de '.$this->maxLength.' caracteres';
            else
                $this->errorMessage['max'] = $this->customErrors['max'];
            return false;
        }

        return  true;
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