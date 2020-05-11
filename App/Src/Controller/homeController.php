<?php

namespace App\Src\Controller;


class homeController extends backController
{
    public function home()
    {
        $this->app->render('Front/views/home.html.twig');
    }
    public function show()
    {
        $this->app->render('Front/views/show.html.twig',["slug"=>$_GET['slug']]);
    }

}