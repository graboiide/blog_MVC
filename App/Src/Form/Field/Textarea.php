<?php

namespace App\Src\Form\Field;

class Textarea extends Field
{
    protected $minLength ;
    protected $maxLength;
    protected $rows;
    protected $cols;
    protected $type = 'text';
    public function getWidget()
    {

        return '<textarea 
                name="'.$this->name.'"
                class="form-control " 
                type="text" 
                '.$this->minLength.'
                '.$this->maxLength.'
                '.$this->cols.'
                '.$this->rows.'
                id="'.$this->name.'"
                '.$this->required.'
                '.$this->placeholder.'>'.$this->value.'</textarea>';
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

    /**
     * @param mixed $rows
     */
    public function setRows($rows): void
    {
        $this->rows = 'rows="'.max(0,$rows).'"';
    }

    /**
     * @param mixed $cols
     */
    public function setCols($cols): void
    {
        $this->cols = 'cols="'.max(0,$cols).'"';
    }

}