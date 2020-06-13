<?php


namespace App\Src\Form;


use App\Src\Form\Field\Input;
use App\Src\Form\Field\Textarea;
use App\Src\Form\Validator\Length;
use App\Src\Form\Validator\Mail;


class ContactForm extends FormBuilder
{
    public function buildForm()
    {
        $this
            ->addField(Input::class,[
                "name"=>"name",
                "label"=>"Nom"
            ])
            ->addValidator(Length::class,["min"=>2, "max"=>45,"customErrors"=>["min"=>"vous devez trouver un nom avec plus de deux caracteres"]])
            ->addField(Input::class,[
                "name"=>"prenom",
                "label"=>"Prénom"
            ])
            ->addValidator(Length::class,["min"=>2,"max"=>25])
            ->addField(Input::class,[
                "name"=>"tel",
                "label"=>"Numéro de téléphone"
            ])
            ->addField(Input::class,[
                "name"=>"email",
                "label"=>"Email"
            ])
            ->addValidator(Mail::class)
            ->addField(Textarea::class,[
                "name"=>"message",
                "label"=>"Votre message",
                "rows"=>6
            ])

        ;
    }
}