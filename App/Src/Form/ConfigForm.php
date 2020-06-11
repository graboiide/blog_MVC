<?php


namespace App\Src\Form;

use App\Src\Form\Field\Input;
use App\Src\Form\Field\Upload;

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
           ->addField(Upload::class,[
               "name"=>"imageFile"
           ])
           ->addField(Input::class,[
               "name"=>"presentation",
               "label"=>"Presentation"
           ])
           ->addField(Input::class,[
               "name"=>"email",
               "label"=>"Email de contact"
           ])


       ;

   }
}