<?php

namespace App\Src\Controller;


use App\Src\Service\Entity\BlogPostEntity;
use App\Src\Service\Entity\CommentEntity;
use App\Src\Service\Manager\BlogPostManager;
use Faker;
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

            "contain" => 'dication 3' ,
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

    }


/*
    public function faker()
    {

        $manager = $this->manager->getEntityManager(BlogPostEntity::class);
        $faker = Faker\Factory::create();
        for ($i=0 ; $i<15 ; $i++){
            dump("ajout blog");
            $date = $faker->dateTimeBetween('-1 years','now');
            $dataBlogPost = [
                "contain" => $faker->sentence(mt_rand(30, 100)) ,
                "chapo" => $faker->sentence(mt_rand(5, 20)),
                "image" => 'https://picsum.photos/840/480?random='.$i,
                "title" => $faker->sentence(mt_rand(1, 7)),
                "slug" => $faker->slug,
                "date" => $date->format("Y-m-d"),
                "is_published" => 1,
                'user_id'=>1
            ];
            $blog = new BlogPostEntity($dataBlogPost);
            $idBlog = $manager->save($blog);
            dump($blog);
            // Les commentaires
            $manager = $this->manager->getEntityManager(CommentEntity::class);
            $nbComments = mt_rand(0,8);
            dump("ajout de ".$nbComments." commentaires");
            for ($j=0 ; $j<$nbComments ; $j++){
                $date = $faker->dateTimeBetween('-1 years','now');
                $dataComment = [
                    "date" =>$date->format("Y-m-d H:m"),
                    "message" => $faker->sentence(mt_rand(5, 20)),
                    "is_validate" => 1,
                    "post_blog_id" => $idBlog,
                    "name" => $faker->name
                ];
                $comment = new CommentEntity($dataComment);
                $manager->save($comment);
            }
        }


        $this->render('Front/Views/home.html.twig');
    }

*/
}