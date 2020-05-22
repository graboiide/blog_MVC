<?php

namespace App\Src\Controller;

use App\Src\Service\Entity\BlogPostEntity;
use App\Src\Service\Entity\CommentEntity;
use App\Src\Service\Manager\BlogPostManager;
use App\Src\Service\Manager\CommentManager;

class HomeController extends backController
{
    public function home()
    {
        /**
         * @var BlogPostManager $blogManager
         */
        $blogManager = $this->manager->getEntityManager(BlogPostEntity::class);
        /**
         * @var CommentManager $commentManager
         */
        $listBlogs = $blogManager->listPublished(0,10);
        $commentManager = $this->manager->getEntityManager(CommentEntity::class);

        /**
         * @var BlogPostEntity $blog
         */
        foreach ($listBlogs as $blog){
            $blog->setNbComment($commentManager->countByBlog($blog->getId()));
        }
        $this->render('Front/Views/grid_blog.html.twig',['blogs'=>$listBlogs]);
    }
    public function show()
    {
        /**
         * @var BlogPostManager $blogManager
         */
        $blogManager = $this->manager->getEntityManager(BlogPostEntity::class);
        $blog = $blogManager->findById($this->request->get('id'));
        /**
         * @var CommentManager $commentManager
         */
        $commentManager = $this->manager->getEntityManager(CommentEntity::class);

        $comments = $commentManager->listPublished($blog->getId());
        

        $this->render('Front/views/show.html.twig',["blog"=>$blog,"comments"=>$comments]);
    }



}