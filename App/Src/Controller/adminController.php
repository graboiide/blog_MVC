<?php

namespace App\Src\Controller;



class adminController extends backController
{
    public function home()
    {
        $this->render('Back/Views/home.html.twig');
    }

}