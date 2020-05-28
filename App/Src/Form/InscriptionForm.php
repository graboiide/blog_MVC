<?php


namespace App\Src\Form;


use App\Src\Form\Field\Input;
use App\Src\Form\Field\Password;
use App\Src\Form\Validator\EqualTo;
use App\Src\Form\Validator\InDataBase;
use App\Src\Form\Validator\Length;
use App\Src\Form\Validator\Mail;
use App\Src\Service\Entity\UserEntity;

class InscriptionForm extends FormBuilder
{
    public function buildForm()
    {
        $this
            ->addField(Input::class,[
                "name"=>"name",
                "placeholder"=>"Votre nom"
            ])
            ->addValidator(InDataBase::class,[
                "entityClassName"=>UserEntity::class,
                "customErrors"=>"Ce nom existe déja"
            ])
            ->addValidator(Length::class,["min"=>2, "max"=>45,"customErrors"=>["min"=>"vous devez trouver un nom avec plus de deux caracteres"]])
            ->addField(Password::class,[
                "name"=>"password",
                "placeholder"=>"Votre mot de passe"
            ])
            ->addValidator(Length::class,["min"=>5,"max"=>20])
            ->addField(Password::class,[
                "name"=>"rePassword",
                "placeholder"=>"Répetez le mot de passe"
            ])
            ->addValidator(EqualTo::class,[
                "targetField"=>"password",
                "customErrors"=>["error_message"=>"Les deux passwords doivent être identique"]
            ])
            ->addField(Input::class,[
                "name"=>"email",
                "placeholder"=>"entrez votre email"
            ])
            ->addValidator(InDataBase::class,["entityClassName"=>UserEntity::class])
            ->addValidator(Mail::class)

        ;
    }
}