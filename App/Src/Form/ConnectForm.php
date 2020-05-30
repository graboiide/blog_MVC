<?php


namespace App\Src\Form;


use App\Src\Form\Field\Input;
use App\Src\Form\Field\Password;


class ConnectForm extends FormBuilder
{
    public function buildForm()
    {
        $this
            ->addField(Input::class,[
                "name"=>"name",
                "placeholder"=>"Votre nom"
            ])
            ->addField(Password::class,[
                "name"=>"password",
                "placeholder"=>"Votre mot de passe"
            ])

        ;
    }
}