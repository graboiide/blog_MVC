<?php

namespace App\Src\Controller;

use App\App;
use App\Src\Form\CommentForm;
use App\Src\Form\ConnectForm;
use App\Src\Form\Form;
use App\Src\Service\Entity\BlogPostEntity;
use App\Src\Service\Entity\CommentEntity;
use App\Src\Service\Entity\UserEntity;
use App\Src\Service\HTTP\HttpRequest;
use App\Src\Service\Manager\BlogPostManager;
use App\Src\Service\Manager\CommentManager;
use App\Src\Service\Manager\UserManager;
use DateTime;

class HomeController extends backController
{

    public function __construct(App $app, $action, HttpRequest $request)
    {
        parent::__construct($app, $action, $request);
        $this->cate();
    }

    public function home()
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
    private function cate()
    {

        $this->app->getRenderer()->addGlobal('_categories',[
            ['title'=>'Jeux vidéo','nb'=>15],
            ['title'=>'Loisir','nb'=>5],
            ['title'=>'Sport','nb'=>6],
            ['title'=>'Autres','nb'=>2]
        ]);

    }





}