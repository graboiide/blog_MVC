<?php

namespace App\Src\Controller;

use App\Src\Service\Entity\BlogPostEntity;
use App\Src\Service\HTTP\HttpRequest;
use App\Src\Service\Manager\BlogPostManager;

class HomeController extends backController
{
    public function home()
    {

        /**
         * @var BlogPostManager $blogManager
         */
        $blogManager = $this->manager->getEntityManager(BlogPostEntity::class);
        dump($blogManager->listPublished(0,1));


        $this->render('Front/Views/home.html.twig');
    }
    public function show()
    {
        $request = new HttpRequest();
        $this->render('Front/views/show.html.twig',["slug"=>$request->get('slug')]);
    }



}