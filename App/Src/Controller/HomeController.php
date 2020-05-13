<?php

namespace App\Src\Controller;


class HomeController extends backController
{
    public function home()
    {
       $this->render('Front/Views/home.html.twig');
    }
    public function show()
    {
        $this->render('Front/views/show.html.twig',["slug"=>$_GET['slug']]);
    }

}