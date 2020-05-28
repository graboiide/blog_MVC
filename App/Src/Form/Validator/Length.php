<?php


namespace App\Src\Form\Validator;


class Length extends Validator
{
    private $minLength;
    private $maxLength;


    public function isValid()
    {
        $this->defaultErrors['min'] = 'Le champ '.$this->fieldChild->getName().' doit faire plus de '.$this->minLength.' caracteres';
        $this->defaultErrors['max'] = 'Le champ '.$this->fieldChild->getName().' doit faire moins de '.$this->maxLength.' caracteres';
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



    /**
     * @param mixed $minLength
     */
    public function setMin($minLength): void
    {
        $this->minLength = $minLength;
    }

    /**
     * @param mixed $maxLength
     */
    public function setMax($maxLength): void
    {
        $this->maxLength = $maxLength;
    }
}