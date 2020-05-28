<?php


namespace App\Src\Form;


use App\Src\Form\Field\Input;
use App\Src\Form\Field\Password;
use App\Src\Form\Validator\InDataBase;
use App\Src\Form\Validator\Length;
use App\Src\Service\Entity\UserEntity;

class ConnectForm extends FormBuilder
{
    public function buildForm()
    {
        $this
            ->addField(Input::class,[
                "name"=>"name",
                "placeholder"=>"Votre nom"
            ])
            ->addValidator(InDataBase::class,[
                "entityClassName"=>UserEntity::class
            ])
            ->addValidator(Length::class,[
                "min"=>2,
                "max"=>45
            ])
            ->addField(Password::class,[
                "name"=>"password",
                "placeholder"=>"Votre mot de passe"
            ])

        ;
    }
}