<?php


namespace App\Src\Form;

use App\Src\Form\Field\CheckBox;
use App\Src\Form\Field\Input;
use App\Src\Form\Field\Textarea;

use App\Src\Form\Field\Upload;
use App\Src\Form\Validator\Length;
use App\Src\Form\Validator\UploadValidate;


class BlogPostForm extends FormBuilder
{
   public function buildForm()
   {
       $this
           ->addField(Input::class,[
               "name"=>"title",
               "label"=>"Titre du blog post"
           ])
           ->addValidator(Length::class,[
               "min"=>2,
               "max"=>30])

           ->addField(Upload::class,[
               "name"=>"imageFile",
               "label"=>"choisisse dune image"
           ])
           ->addValidator(UploadValidate::class)
           ->addField(Textarea::class,[
               "name"=>'contain',
               "label"=>"Contenu",
               "cols"=>30,
               "rows"=>20,
               "id"=>'tinyMce'
           ])
           ->addValidator(Length::class,[
               "min"=>30,
               "max"=>2000])
           ->addField(Textarea::class,[
               "name"=>'chapo',
               "cols"=>30,
               "rows"=>4,
               "label"=>"Chapo"
           ])
           ->addValidator(Length::class,[
               "min"=>10,
               "max"=>200])

           ->addField(CheckBox::class,["name"=>"is_published","label"=>"Enregistrer en brouillon"])

       ;

   }
}