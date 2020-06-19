<?php

namespace App\Src\Controller;

use App\App;
use App\Src\Form\CommentForm;
use App\Src\Form\ConnectForm;
use App\Src\Form\ContactForm;
use App\Src\Form\Field\Hidden;
use App\Src\Form\Form;
use App\Src\Form\InscriptionForm;
use App\Src\Service\Entity\BlogPostEntity;
use App\Src\Service\Entity\CommentEntity;
use App\Src\Service\Entity\ConfigEntity;
use App\Src\Service\Entity\ContactEntity;
use App\Src\Service\Entity\UserEntity;
use App\Src\Service\FlashBag\FlashBag;
use App\Src\Service\HTTP\HttpRequest;
use App\Src\Service\HTTP\Session;
use App\Src\Service\Manager\BlogPostManager;
use App\Src\Service\Manager\CommentManager;
use App\Src\Service\Manager\ConfigManager;
use App\Src\Service\Manager\UserManager;
use App\Src\Service\Upload\Upload;
use DateTime;

class HomeController extends BackController
{
    /**
    * @var BlogPostManager $blogManager
    */
    private $blogManager;

    /**
     * @var BlogPostManager $blogManager
     */
    private $commentManager;
    /**
     * @var UserManager $userManager
     */
    private $userManager;

    public function __construct(App $app, $action, HttpRequest $request)
    {
        parent::__construct($app, $action, $request);
        //exemple de creation des managers dans le construct afin de ne pas surcharger le code des controllers
        $this->blogManager = $blogManager = $this->manager->getEntityManager(BlogPostEntity::class);
        $this->commentManager = $this->manager->getEntityManager(CommentEntity::class);
        $this->userManager = $this->manager->getEntityManager(UserEntity::class);
    }


    public function blog()
    {
        //on évite un if pour rien
        $page = max(0,(int)$this->request->get('page')-1);
        $listBlogs = $this->blogManager->listPublished($page * 10,10);
        $nbPage = ceil($this->blogManager->count()/10);
        /**
         * @var BlogPostEntity $blog
         */
        foreach ($listBlogs as $blog)
            $blog->setNbComment($this->commentManager->countByBlog($blog->getId()));
        $this->render('Front/Views/grid_blog.html.twig',['blogs'=>$listBlogs,'nbPage'=>$nbPage,'page'=>$page + 1]);

    }
    public function show()
    {
        $blogs = $this->blogManager->findBlogsForNav($this->request->get('id'));
        $dateNow= new DateTime('now');
        if($this->request->method() === 'POST')
            $comment = new CommentEntity(array_merge($this->request->post(),["postBlogId"=>(int)$this->request->get('id'),"date"=> $dateNow->format('Y-m-d H:m')]));
        else
            $comment = new CommentEntity();

        $messageFlash = 'Votre commentaire à été ajouté et soumis à la validation';
        $formBuilder = new CommentForm($comment);
        // pour les admin on retire le champ name et modifie l'entity comment
        $formBuilder->buildForm();
        if($this->userHandler->isConnected() && Session::get('connect') === 'admin')  {
            $comment->setIsValidate(1);
            $comment->setName($this->userManager->getUser(Session::get('user_id'))->getName());
            $formBuilder->removeField('name');
            $messageFlash = 'Votre commentaire à été ajouté ';
        }
        /**
         * @var Form $form
         */
        $form = $formBuilder->createForm($this->request);
        if($form->isSubmitted() && $form->isValid()){
            $this->commentManager->save($comment);
            FlashBag::set($messageFlash);
            $this->response->redirect($this->request->uri());
        }
        $this->render('Front/Views/show.html.twig',["blogs"=>$blogs,
            "comments"=>$this->commentManager->listPublished($blogs['target']->getId()),
            "form"=>$form,'author'=>$this->userManager->getUser($blogs["target"]->getUserId())]);
    }

    public function connectUser(){
        $user = new UserEntity();
        $builderForm = new ConnectForm($user);
        $builderForm->buildForm();
        $form = $builderForm->createForm($this->request);
        if($form->isSubmitted() && $form->isValid()){
            $user = $this->userManager->getUserByName($this->request->post('name'));
            if($user->getPassword() === sha1($this->request->post('password'))){
                FlashBag::set('Vous êtes connecté','success');
                $this->userHandler->connect($user);
                $this->response->redirect("/blog");

            }
        }else{
            FlashBag::set('Erreur d\'identification, veuillez réessayer','error');
        }

        $this->render('Back/Views/connect.html.twig',["form"=>$form]);
    }
    public function disconnectUser()
    {
        $this->request->setCookie(["token",'',0]);
        Session::unset('connect');
        FlashBag::set('Vous avez été déconnecté','notify');
        $this->response->redirect("/blog");
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
            $idUser = $this->userManager->save($user);
            $this->userManager->createToken(uniqid(),$idUser);

            FlashBag::set('Votre inscription à réussi, un administrateur va la valider sous 24h','notify');

            $this->response->redirect("/blog");

        }


        $this->render('Back/Views/inscription.html.twig',["form"=>$form]);
    }

    public function home()
    {
        /**
         * @var ConfigManager $manager
         */
        $manager = $this->manager->getEntityManager(ConfigEntity::class);
        $builderForm = new ContactForm();
        $builderForm->buildForm();
        $config = $manager->getConfig();

        $form = $builderForm->createForm($this->request);

        if($form->isSubmitted() && $form->isValid()){
            $headers = 'From: '.$this->request->post('email') . "\r\n" .
                'Reply-To:'.$this->request->post('email') . "\r\n" ;
                $message = 'Message de : '.$this->request->post('name').' '.$this->request->post('prenom'). "\r\n".$this->request->post('message');

            //empecher l'envoie de plusieurs mail a la suite
            
                FlashBag::set("Votre message à bien été envoyé");
                $this->response->redirect("/#contact");
                mail($config->getEmail(), $this->request->post('subject'), $message,$headers);

            Session::set('mailSending','sending');


        }
        $this->render('Front/Views/home.html.twig',["config"=>$config,"form"=>$form]);
    }






}