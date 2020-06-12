<?php


namespace App\Src\Form;

use App\Src\Form\Field\Textarea;
use App\Src\Form\Field\Upload;
use App\Src\Form\Validator\UploadValidate;

class UserForm extends FormBuilder
{
    public function buildForm()
    {
        $this
            ->addField(Upload::class,[
                "name"=>"avatarFile"
            ])
            ->addValidator(UploadValidate::class)
            ->addField(Textarea::class,[
                "name"=>"description",
                "cols"=>30,
                "rows"=>5,
            ])
        ;
    }
}