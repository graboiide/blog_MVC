<?php

namespace App\Src\Form\Field;

class Upload extends Field
{
    private $progressbar;



    public function getWidget()
    {
        $field = ' <input value="'.$this->value.'" type="file" name="'.$this->name.'" class=" upload-system custom-file-input" id="uploadFile"> ';
        return $field;
    }
    /**
     * @param mixed $progressbar
     */
    public function setProgressbar($progressbar): void
    {
        $this->progressbar = $progressbar;
    }

}