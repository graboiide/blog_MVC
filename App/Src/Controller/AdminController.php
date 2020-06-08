<?php

namespace App\Src\Controller;


use App\App;

use App\Src\Form\InscriptionForm;

use App\Src\Service\Entity\BlogPostEntity;
use App\Src\Service\Entity\CommentEntity;
use App\Src\Service\Entity\UserEntity;
use App\Src\Service\HTTP\HttpRequest;

use App\Src\Service\Manager\BlogPostManager;
use App\Src\Service\Manager\CommentManager;
use App\Src\Service\Manager\UserManager;
use Faker;


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

    public function inscription()
    {
        /**
         * @var UserManager $userManager
         */
        if($this->request->method() === 'POST'){
            $user = new UserEntity($this->request->post());
            $user->setPassword(sha1($this->request->post('password')));
        }
        else
            $user = new UserEntity();
        $builderForm = new InscriptionForm($user);
        $builderForm->buildForm();

        $form = $builderForm->createForm($this->request);

        if($form->isSubmitted() && $form->isValid()){
            /**
             * @var UserManager $managerUser
             */
            $managerUser = $this->manager->getEntityManager(UserEntity::class);
            $idUser = $managerUser->save($user);
            $managerUser->createToken(uniqid(),$idUser);
            $this->response->redirect("/blog");

        }


        $this->render('Back/Views/inscription.html.twig',["form"=>$form]);
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