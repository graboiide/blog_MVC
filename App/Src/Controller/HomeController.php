<?php

namespace App\Src\Controller;

use App\Src\Form\CommentForm;
use App\Src\Form\ConnectForm;
use App\Src\Form\ContactForm;
use App\Src\Form\Form;
use App\Src\Form\InscriptionForm;
use App\Src\Service\Entity\BlogPostEntity;
use App\Src\Service\Entity\CommentEntity;
use App\Src\Service\Entity\ConfigEntity;
use App\Src\Service\Entity\ContactEntity;
use App\Src\Service\Entity\UserEntity;
use App\Src\Service\Manager\BlogPostManager;
use App\Src\Service\Manager\CommentManager;
use App\Src\Service\Manager\ConfigManager;
use App\Src\Service\Manager\UserManager;
use App\Src\Service\Upload\Upload;
use DateTime;

class HomeController extends backController
{


    public function blog()
    {
        //on évite un if pour rien
        $page = max(0,(int)$this->request->get('page')-1);

        /**
         * @var BlogPostManager $blogManager
         */
        $blogManager = $this->manager->getEntityManager(BlogPostEntity::class);
        /**
         * @var CommentManager $commentManager
         */
        $listBlogs = $blogManager->listPublished($page * 10,10);
        $commentManager = $this->manager->getEntityManager(CommentEntity::class);
        $nbPage = ceil($blogManager->count()/10);
        /**
         * @var BlogPostEntity $blog
         */
        foreach ($listBlogs as $blog)
            $blog->setNbComment($commentManager->countByBlog($blog->getId()));

        $this->render('Front/Views/grid_blog.html.twig',['blogs'=>$listBlogs,'nbPage'=>$nbPage,'page'=>$page + 1]);

    }
    public function show()
    {
        /**
         * @var BlogPostManager $blogManager
         */
        $blogManager = $this->manager->getEntityManager(BlogPostEntity::class);
        $blogs = $blogManager->findBlogsForNav($this->request->get('id'));

        /**
         * @var CommentManager $commentManager
         */
        $commentManager = $this->manager->getEntityManager(CommentEntity::class);
        $comments = $commentManager->listPublished($blogs['target']->getId());
        $dateNow= new DateTime('now');
        if($this->request->method() === 'POST')
            $comment = new CommentEntity(array_merge($this->request->post(),[
                "postBlogId"=>(int)$this->request->get('id'),
                "date"=> $dateNow->format('Y-m-d H:m')
            ]));
        else
            $comment = new CommentEntity();

        $formBuilder = new CommentForm($comment);
        $formBuilder->buildForm();

        /**
         * @var Form $form
         */
        $form = $formBuilder->createForm($this->request);

        if($form->isSubmitted() && $form->isValid()){
            $commentManager->save($comment);
            $this->response->redirect($this->request->uri());
        }

        $this->render('Front/views/show.html.twig',["blogs"=>$blogs,"comments"=>$comments,"form"=>$form]);
    }
    public function connectUser(){
        $user = new UserEntity();
        $builderForm = new ConnectForm($user);
        $builderForm->buildForm();
        $form = $builderForm->createForm($this->request);
        if($form->isSubmitted() && $form->isValid()){
            /**
             * @var UserManager $manager
             */
            $manager = $this->manager->getEntityManager(UserEntity::class);
            $user = $manager->getUserByName($this->request->post('name'));

            if($user->getPassword() === sha1($this->request->post('password'))){
                $this->userHandler->connect($user);
                $this->response->redirect("/blog");
            }

        }

        $this->render('Back/Views/connect.html.twig',["form"=>$form]);
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
    public function contact()
    {

        $builderForm = new ContactForm();
        $builderForm->buildForm();

        $form = $builderForm->createForm($this->request);

        if($form->isSubmitted() && $form->isValid()){
            //mail('graboiide@gmail.com', 'mail test', 'test de message');

        }


        $this->render('Front/Views/contact.html.twig',["form"=>$form]);
    }
    public function home()
    {
        /**
         * @var ConfigManager $manager
         */
        $manager = $this->manager->getEntityManager(ConfigEntity::class);
        $builderForm = new ContactForm();
        $builderForm->buildForm();

        $form = $builderForm->createForm($this->request);

        if($form->isSubmitted() && $form->isValid()){
            //mail('graboiide@gmail.com', 'mail test', 'test de message');

        }
        $this->render('Front/Views/home.html.twig',["config"=>$manager->getConfig(),"form"=>$form]);
    }






}