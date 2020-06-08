<?php


namespace App\Src\Form;


use App\Src\Form\Field\Input;
use App\Src\Form\Field\Textarea;
use App\Src\Form\Validator\InDataBase;
use App\Src\Form\Validator\Length;
use App\Src\Service\Entity\UserEntity;

class CommentForm extends FormBuilder
{
    public function buildForm()
    {
        $this
            ->addField(Input::class,[
                "name"=>"name",
                "value"=>"dfsdfsdf",
                "label"=>"Nom",
                "placeholder"=>"votre nom"
            ])
            ->addValidator(Length::class,[
                "min"=>2,
                "max"=>30,
                "customErrors"=>[
                    "min"=>"Qui a un nom avec 1 seul caractere ?",
                    "max"=>"Ca doit etre compliquer à la prefecture avec un nom si long"
                ]
            ])
            ->addValidator(InDataBase::class,[
                "entityClassName"=>UserEntity::class,
                "customErrors"=>"Ce pseudonyme est déja utilisé par un membre de l'équipe veuillez en choisir un autre."
            ])
            ->addField(Textarea::class,[
                "name"=>'message',
                "placeholder"=>"inscrivez un autre commentaire",
                "label"=>"Message",
                "cols"=>30,
                "rows"=>7
            ])
            ->addValidator(Length::class,[
                "min"=>15,
                "max"=>300,
                "customErrors"=>[
                    "min"=>"Il faut 15 caracteres minimum",
                    "max"=>"Il faut 300 caracteres maximum"
                ]
            ]);
    }
}