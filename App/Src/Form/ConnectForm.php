<?php


namespace App\Src\Form;


use App\Src\Form\Field\Input;
use App\Src\Form\Field\Password;
use App\Src\Form\Validator\ExistInDataBase;
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
            ->addValidator(ExistInDataBase::class,[
                "entityClassName"=>UserEntity::class,
                "customErrors"=>"Ce compte existe pas en base de donnée"
            ])
            ->addField(Password::class,[
                "name"=>"password",
                "placeholder"=>"Votre mot de passe"
            ])

        ;
    }
}