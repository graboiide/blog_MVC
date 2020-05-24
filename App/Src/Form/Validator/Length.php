<?php


namespace App\Src\Form\Validator;


class Length extends Validator
{
    private $minLength;
    private $maxLength;

    public function isValid()
    {
        $length = strlen($this->fieldChild->getValue());
        if( $length < $this->minLength){
            $this->errorMessage['min'] = 'Le champ '.$this->fieldChild->getName().' doit faire plus de '.$this->minLength.' caracteres';
            return false;
        }

        if( $length > $this->maxLength){
            $this->errorMessage['min'] = 'Le champ '.$this->fieldChild->getName().' doit faire moins de '.$this->maxLength.' caracteres';
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