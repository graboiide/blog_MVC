<?php

namespace App\Src\Form\Field;

class Input extends Field
{
    protected $minLength;
    protected $maxLength;
    public function getWidget()
    {

        return '
                <input 
                id="'.$this->name.'"
                name="'.$this->name.'"
                class="form-control" 
                type="text" id="id" 
                '.$this->minLength.'
                '.$this->maxLength.'
                value="'.$this->value.'"
                id="'.$this->id.'"
                '.$this->required.'
                '.$this->placeholder.'>
            ';
    }

    /**
     * @param int $minLength
     */
    public function setMinLength(int $minLength): void
    {
        $this->minLength =  'minLength="'.max(0,(int)$minLength).'"';
    }

    /**
     * @param int $maxLength
     */
    public function setMaxLength(int $maxLength): void
    {
        $this->maxLength = 'maxLength="'.max(0,(int)$maxLength).'"';
    }
}