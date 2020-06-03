<?php

namespace App\Src\Controller;


use App\App;
use App\Src\Form\BlogPostForm;
use App\Src\Service\Entity\BlogPostEntity;
use App\Src\Service\Entity\CommentEntity;
use App\Src\Service\HTTP\HttpRequest;
use App\Src\Service\HTTP\Session;
use App\Src\Service\Manager\BlogPostManager;
use App\Src\Service\Manager\CommentManager;
use Faker;
use DateTime;


class AdminController extends backController
{
    public function __construct(App $app, $action, HttpRequest $request)
    {
        parent::__construct($app, $action, $request);
        $this->notify();
    }

    public function home()
    {
        /**
         * @var BlogPostManager $managerBlog
         */
        $managerBlog = $this->manager->getEntityManager(BlogPostEntity::class);
        $listBlog = $managerBlog->listBlogs();

        $this->render('Back/Views/home.html.twig',["blogs" => $listBlog]);
    }
    public function addBlogPost()
    {
        /**
        * @var BlogPostManager $manager
        */
        $manager = $this->manager->getEntityManager(BlogPostEntity::class);
        if($this->request->method() == 'POST')
        {
            $blog = new BlogPostEntity($this->request->post());
            $date = new DateTime("now");
            $blog->setUserId(Session::get("user_id"));
            $blog->setId($this->request->get("idBlog"));
            $blog->setDate( $date->format("Y-m-d"));
        }
        else
            $blog = new BlogPostEntity();

        $formBuilder = new BlogPostForm($blog);
        $formBuilder->buildForm();
        $form = $formBuilder->createForm($this->request);
        if($form->isSubmitted() && $form->isValid() ){
            $manager->save($blog);
        }

        $this->render('Back/Views/add_blog_post.html.twig',["form" => $form]);

    }
    public function updateBlogPost()
    {
        /**
         * @var BlogPostManager $manager
         */
        $manager = $this->manager->getEntityManager(BlogPostEntity::class);
        if($this->request->method() == 'POST')
        {
            $date = new DateTime("now");
            $blog = new BlogPostEntity($this->request->post());
            $blog->setId($this->request->get("idBlog"));
            $blog->setDateMaj($date->format("Y-m-d"));
        }
        else
            $blog = $manager->findById($this->request->get("idBlog"));

        $formBuilder = new BlogPostForm($blog);
        $formBuilder->buildForm();
        $form = $formBuilder->createForm($this->request);
        if($form->isSubmitted() && $form->isValid() ){
            $manager->save($blog);
            $this->response->redirect('/admin/postblogs');
        }

        $this->render('Back/Views/add_blog_post.html.twig',["form" => $form,"modified"=>true]);

    }
    public function listBlogPost()
    {

        /**
         * @var BlogPostManager $manager
         */
        $manager = $this->manager->getEntityManager(BlogPostEntity::class);
        $listBlog = $manager->listBlogs(0,20);
        $this->render('Back/Views/list_blog.html.twig',["blogs" => $listBlog]);

    }


    private function notify()
    {
        $nbNotify = 0;
        /**
         * @var CommentManager $commentManager
         */
        $commentManager = $this->manager->getEntityManager(CommentEntity::class);
        //notifie commentaires
        $nbComments = $commentManager->countForValidate();

        /**
         * @var BlogPostManager $blogManager
         */
        $blogManager = $this->manager->getEntityManager(BlogPostEntity::class);
        //notifie commentaires
        $nbBlogBrouillon = $blogManager->countBrouillon();

        if($nbComments > 0){
            $this->app->getRenderer()->addGlobal("notifyComment",$nbComments);
            $nbNotify++;
        }
        if($nbBlogBrouillon > 0){
            $this->app->getRenderer()->addGlobal("notifyBlog",$nbBlogBrouillon);
            $nbNotify++;
        }
        $this->app->getRenderer()->addGlobal("nbNotify",$nbNotify);
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