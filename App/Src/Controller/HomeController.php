<?php

namespace App\Src\Controller;


use App\Src\Service\Entity\BlogPostEntity;
use App\Src\Service\Entity\CommentEntity;
use DateTime;

class HomeController extends backController
{
    public function home()
    {
        /*
         * search all comments for the postblog here
         */
        $comment1 = new CommentEntity([
            "date" => new DateTime('2020-05-14 15:53:17'),
            "id"=>1,
            "message"=>'Commentaire test',
            "is_validate" => 1,
            "post_blog_id"=> 5
        ]);

        $comment2 = new CommentEntity([
            "date" => new DateTime('2020-05-14 16:12:24'),
            "id"=>2,
            "message"=>'Commentaire test 2',
            "is_validate" => 1,
            "post_blog_id"=> 5
        ]);
        $comments = [$comment1,$comment2];
        /*
         * load blogpost here and add comments to blogpost data
         */
        $dataBlogPost = [
            "id" => 5,
            "contain" => 'Premier blog de test en local sans database' ,
            "chapo" => 'Un court résumé',
            "image" => 'https://via.placeholder.com/150',
            "title" => 'mon premier blog',
            "slug" => 'mon_premier_blog',
            "date" => new DateTime('now'),
            "is_published" => 1,
            'user_id'=>1,
            'comments'=>$comments
        ];
        $blogPost = new BlogPostEntity($dataBlogPost);
        dump($blogPost);
        $this->render('Front/Views/home.html.twig',["blog" => $blogPost]);
    }
    public function show()
    {
        $this->render('Front/views/show.html.twig',["slug"=>$_GET['slug']]);
    }

}