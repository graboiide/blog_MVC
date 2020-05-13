<?php

namespace App\Src\Controller;



class AdminController extends backController
{
    public function home()
    {
        $this->render('Back/Views/home.html.twig');
    }

}