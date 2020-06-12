<?php


namespace App\Src\Form;

use App\Src\Form\Field\Input;
use App\Src\Form\Field\Textarea;
use App\Src\Form\Field\Upload;
use App\Src\Form\Validator\UploadValidate;

class ConfigForm extends FormBuilder
{
   public function buildForm()
   {
       $this
           ->addField(Input::class,[
               "name"=>"name",
               "label"=>"Nom"
           ])
           ->addField(Upload::class,[
               "name"=>"cvFile"
           ])
           ->addValidator(UploadValidate::class,[
               "typeAccepted"=>['pdf']
           ])
           ->addField(Upload::class,[
               "name"=>"imageFile"
           ])
           ->addField(Textarea::class,[
               "name"=>"presentation",
               "label"=>"Presentation",
               "rows"=> 5
           ])
           ->addField(Input::class,[
               "name"=>"email",
               "label"=>"Email de contact"
           ])


       ;

   }
}