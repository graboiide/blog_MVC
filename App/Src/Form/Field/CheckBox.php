<?php

namespace App\Src\Form\Field;

class CheckBox extends Field
{
    private $checked = false;

    public function getWidget()
    {

        return '
                <input 
                id="'.$this->name.'"
                name="'.$this->name.'"
                class="custom-control-input" 
                type="checkbox" id="id" 
            
                value="'.$this->value.'"
                id="'.$this->id.'"
                '.$this->required.' '
                .($this->checked === false ? '' : 'checked').'>';
    }

    /**
     * @param bool $checked
     */
    public function setChecked(bool $checked): void
    {
        $this->checked = $checked;
    }



}