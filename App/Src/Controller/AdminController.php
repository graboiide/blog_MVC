<?php

namespace App\Src\Controller;


use App\App;

use App\Src\Form\BlogPostForm;
use App\Src\Form\ConfigForm;
use App\Src\Form\UserForm;
use App\Src\Security\CsrfSecurity;
use App\Src\Service\Entity\BlogPostEntity;
use App\Src\Service\Entity\CommentEntity;
use App\Src\Service\Entity\ConfigEntity;
use App\Src\Service\Entity\UserEntity;
use App\Src\Service\HTTP\HttpRequest;
use App\Src\Service\HTTP\Session;
use App\Src\Service\Manager\BlogPostManager;
use App\Src\Service\Manager\CommentManager;
use App\Src\Service\Manager\ConfigManager;
use App\Src\Service\Manager\UserManager;
use App\Src\Service\Upload\UploadFile;
use DateTime;
use Exception;
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
            $blog->setIsPublished($this->request->post('action') == "Brouillon" ? 2 : 1);
            $blog->setDate( $date->format("Y-m-d"));

        }
        else
            $blog = new BlogPostEntity();

        $formBuilder = new BlogPostForm($blog);
        $formBuilder->buildForm();
        $form = $formBuilder->createForm($this->request);
        if($form->isSubmitted() && $form->isValid() ){
            $upload = new UploadFile();
            $blog->setImage($upload->setFile('imageFile')->saveFile());
            $manager->save($blog);
            $this->response->redirect("/admin/postblogs");
        }
        $this->render('Back/Views/add_blog_post.html.twig',["form" => $form,"published"=>(int)$blog->getIsPublished()]);

    }

    /**
     * Mergé avec addBlogPost des que possible
     * @throws Exception
     */
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

            $blog->setIsPublished(!is_null($this->request->post('brouillon')) ? 2 : 1);
            $blog->setDateMaj($date->format("Y-m-d"));
        }
        else
            $blog = $manager->findById($this->request->get("idBlog"));

        $formBuilder = new BlogPostForm($blog);
        $formBuilder->buildForm();
        $form = $formBuilder->createForm($this->request);

        if($form->isSubmitted() && $form->isValid() ){

            //upload fichier
            $upload = new UploadFile();
            $blog->setImage($upload->setFile('imageFile')->saveFile());

            $manager->save($blog);
            $this->response->redirect('/admin/postblogs');
        }

        $this->render('Back/Views/add_blog_post.html.twig',["form" => $form,"modified"=>true,"blog"=>$blog,"published"=>(int)$blog->getIsPublished()]);

    }
    public function deleteBlogPost()
    {
        /**
         * @var BlogPostManager $manager
         */
        $manager = $this->manager->getEntityManager(BlogPostEntity::class);

        if (CsrfSecurity::isValid($this->request->get('once'))){
            // protection csrf
            $blog = new BlogPostEntity(["id"=>$this->request->get('idBlog')]);
            $manager->delete($blog);
            $this->response->redirect("/admin/postblogs");
        }

    }
    public function listBlogPost()
    {
        $page = max(0,(int)$this->request->get('page')-1);
        $nbBlogPage = 25;
        /**
         * @var BlogPostManager $manager
         */
        $manager = $this->manager->getEntityManager(BlogPostEntity::class);
        $nbBlog =$manager->count(false);
        $nbPage = ceil($nbBlog/$nbBlogPage);

        $listBlog = $manager->listBlogs($page*$nbBlogPage,$nbBlogPage);
        $once = CsrfSecurity::generateToken();
        $this->render('Back/Views/list_blog.html.twig',[
            "blogs" => $listBlog,
            "once"=>$once,
            "nbBlog"=>$nbBlog,
            "nbPage"=>$nbPage,
            "page"=>$page+1,
            "nbPerPage"=>$nbBlogPage]);

    }
    public function comments()
    {
        $page = max(0,(int)$this->request->get('page')-1);
        $nbComPage = 10;
        /**
         * @var CommentManager $commentManager
         */
        $commentManager = $this->manager->getEntityManager(CommentEntity::class);

        if($this->request->get("action") == 'validate'){
            $nbCom =$commentManager->countForValidate();
            $comments = $commentManager->listNotValidate($page*$nbComPage,$nbComPage);
        }
        elseif ($this->request->get("action") == 'blog'){
            $nbCom =$commentManager->countByBlog($this->request->get("idBlog"));
            $comments = $commentManager->listPublished($this->request->get("idBlog"));
        }
        else{
            $nbCom =$commentManager->countAll();
            $comments = $commentManager->all($page*$nbComPage,$nbComPage);
        }

        $nbPage = ceil($nbCom/$nbComPage);

        $once = CsrfSecurity::generateToken();

        $this->render('Back/Views/comments.html.twig',[
            "comments" => $comments,
            "once"=>$once,"action"=>$this->request->get("action"),
            "nbPage"=>$nbPage,
            "page"=>$page+1,
            "nbPerPage"=>$nbComPage]);

    }
    public function deleteComment()
    {
        /**
         * @var CommentManager $commentManager
         */
        $commentManager = $this->manager->getEntityManager(CommentEntity::class);

        if (CsrfSecurity::isValid($this->request->get('once'))) {
            $comment = new CommentEntity(["id"=>$this->request->get('idCom')]);
            $commentManager->delete($comment);
            $this->response->redirect($this->request->referer());
        }


    }
    public function validateComment()
    {
        /**
         * @var CommentManager $commentManager
         */
        $commentManager = $this->manager->getEntityManager(CommentEntity::class);

        if (CsrfSecurity::isValid($this->request->get('once'))) {
            $commentManager->Validate($this->request->get('idCom'));
            $this->response->redirect($this->request->referer());
        }

    }
    public function profileUser(){

        // on recupere l'identifiant user
        if(is_null($this->request->get('idUser')))
            $idUser = Session::get('user_id');
        else
            $idUser = $this->request->get('idUser');
        /**
         * @var UserManager $manager
         */
        $manager = $this->manager->getEntityManager(UserEntity::class);
        if($this->request->method() == 'POST' )
            $user = new UserEntity(array_merge($this->request->post(),["id"=>$idUser]));
        else
            $user = $manager->getUser($idUser);

        //detection mon profil
        $myProfile = Session::get('user_id') == $this->request->get('idUser') || $this->request->get('idUser') == null;
        //construction formulaire
        $builderForm = new UserForm($user);
        $builderForm->buildForm();
        $form = $builderForm->createForm($this->request);
        if($form->isSubmitted() && $form->isSubmitted())
        {
            //si le fichier existe pas null sera renvoyé
            $upload = new UploadFile();

            // on enregistre pas l'avatar si aucuns fichier selectionné ( à automatisé des que possible)
            $linkAvatar = $upload->setFile('avatarFile')->saveFile();
            $user->setAvatar($linkAvatar);
            $this->app->getRenderer()->addGlobal('adminAvatar','/'.$linkAvatar);

            $manager->save($user);
            $this->response->redirect('/profile');
        }
        $this->render('Back/Views/profile.html.twig',["form"=>$form,"user"=>$user,"myProfile"=>$myProfile]);
    }
    public function config(){
        /**
         * @var ConfigManager $configManager
         */
        $configManager = $this->manager->getEntityManager(ConfigEntity::class);
        //config modifier
        if($this->request->method()=='POST'){
            $upload= new UploadFile();
            $config = new ConfigEntity(array_merge($this->request->post(),["id"=>1]));
            $config->setPicture($upload->setFile('imageFile')->saveFile());
            $config->setCv($upload->setFile('cvFile')->saveFile());
        }//config charger
        else
            $config = $configManager->getConfig();

        $builderForm = new ConfigForm($config);
        $builderForm->buildForm();
        $form = $builderForm->createForm($this->request);

        if($form->isSubmitted() && $form->isValid())
        {
            $configManager->save($config);
            $this->response->redirect('/admin/config');
        }
        $this->render('Back/Views/config.html.twig',["form"=>$form,"config"=>$config]);
    }

    private function notify()
    {

        if(!is_null(Session::get('user_id'))){
            /**
             * @var UserManager $userManager
             */
            $userManager = $this->manager->getEntityManager(UserEntity::class);
            $user = $userManager->getUser(Session::get('user_id'));
            $this->app->getRenderer()->addGlobal('adminAvatar',$user->getAvatar());
        }

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