<?php

namespace App\Src\Controller;

use App\Src\Service\Entity\BlogPostEntity;
use App\Src\Service\Manager\BlogPostManager;
use DateTime;

class AdminController extends backController
{
    public function home()
    {
        $this->render('Back/Views/home.html.twig');
    }
    public function addBlogPost()
    {
        $date1 = new DateTime('now');
        $date1 = $date1->format("Y-m-d");
        $dataBlogPost = [
            "contain" => 'tevde mdddodification 3' ,
            "chapo" => 'ffg_ gg',
            "image" => 'https://via.placeholder.com/150',
            "title" => 'mon premier blog',
            "slug" => 'mon_premier_blog',
            "date" => $date1,
            "is_published" => 1,
            'user_id'=>1
        ];

        /**
         * @var BlogPostManager $manager
         */
        $manager = $this->manager->getEntityManager(BlogPostEntity::class);
        $blogPost = new BlogPostEntity($dataBlogPost);
        $manager->save($blogPost);

        $this->render('Front/Views/home.html.twig',["blog" => $blogPost]);
    }


}