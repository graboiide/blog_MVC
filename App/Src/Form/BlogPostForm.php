<?php


namespace App\Src\Form;

use App\Src\Form\Field\Input;
use App\Src\Form\Field\Textarea;
use App\Src\Form\Validator\Length;

class BlogPostForm extends FormBuilder
{
   public function buildForm()
   {
       $this
           ->addField(Input::class,[
               "name"=>"title",
               "placeholder"=>"Titre du blog"
           ])
           ->addValidator(Length::class,[
               "min"=>2,
               "max"=>30])

           ->addField(Input::class,[
               "name"=>"image",
               "placeholder"=>"url de l'image"
           ])
           ->addField(Textarea::class,[
               "name"=>'contain',
               "placeholder"=>"Contenu du blog",
               "cols"=>30,
               "rows"=>20
           ])
           ->addValidator(Length::class,[
               "min"=>30,
               "max"=>2000])
           ->addField(Textarea::class,[
               "name"=>'chapo',
               "placeholder"=>"chapo du blog",
               "cols"=>30,
               "rows"=>4
           ])
           ->addValidator(Length::class,[
               "min"=>10,
               "max"=>200])
           ->addField(Input::class,["name"=>"slug","placeholder"=>"Slug du blog post"])

       ;

   }
}