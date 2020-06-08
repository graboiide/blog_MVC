<?php


namespace App\Src\Controller;


use App\Src\Security\CsrfSecurity;
use App\Src\Service\Entity\CommentEntity;
use App\Src\Service\Manager\CommentManager;

class CommentController extends BackController
{
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
}