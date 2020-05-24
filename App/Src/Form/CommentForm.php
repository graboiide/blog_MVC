<?php


namespace App\Src\Form;


use App\Src\Form\Field\Input;
use App\Src\Form\Field\Textarea;
use App\Src\Form\Validator\Length;

class CommentForm extends FormBuilder
{
    public function buildForm()
    {
        $this
            ->addField(Input::class,[
                "name"=>"name",
                "value"=>"dfsdfsdf",
                "placeholder"=>"votre nom"
            ])
            ->addValidator(Length::class,[
                "minLength"=>2,
                "maxLength"=>30
            ])
            ->addField(Textarea::class,[
                "name"=>'message',
                "placeholder"=>"inscrivez un autre commentaire",
                "cols"=>30,
                "rows"=>7
            ])
            ->addValidator(Length::class,[
                "minLength"=>15,
                "maxLength"=>300
            ]);
    }
}