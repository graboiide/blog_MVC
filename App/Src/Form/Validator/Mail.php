<?php


namespace App\Src\Form\Validator;


class Mail extends Validator
{



    public function isValid()
    {
        $this->defaultErrors["error_message"] = "email invalide";
        $this->mergeErrors();
        if(!filter_var($this->fieldChild->getValue(),FILTER_VALIDATE_EMAIL)){
            $this->listErrors["error_message"] = $this->defaultErrors["error_message"];
            return false;
        }
        return true;
    }
}