<?php


namespace App\Src\Form\Field;


class Password extends Field
{
    protected $minLength;
    protected $maxLength;
    public function getWidget()
    {
        return '
                <input 
                name="'.$this->name.'"
                class="form-control" 
                type="password" id="id" 
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