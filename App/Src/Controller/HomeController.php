<?php

namespace App\Src\Controller;

use App\Src\Service\DataBase\DBFactory;
use App\Src\Service\Entity\BlogPostEntity;
use App\Src\Service\Manager\Manager;

class HomeController extends backController
{
    public function home()
    {
        $manager = new Manager(DBFactory::PDOMysqlDB($this->app->getConfig()->getVar("database")));
        $blog = $manager->findAll(BlogPostEntity::class,[
            'criteria'=>[
                'is_published'=>1
            ],
            'order'=>'id',
            'limit'=>0,
            'offset'=>8
        ]);
        dump($blog);

        $this->render('Front/Views/home.html.twig');
    }
    public function show()
    {
        $this->render('Front/views/show.html.twig',["slug"=>$_GET['slug']]);
    }



}