<?php


namespace App\Src\Controller;


use App\Src\Form\BlogPostForm;
use App\Src\Security\CsrfSecurity;
use App\Src\Service\Entity\BlogPostEntity;
use App\Src\Service\HTTP\Session;
use App\Src\Service\Manager\BlogPostManager;
use DateTime;

class BlogController extends BackController
{
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
            $blog->setIsPublished($this->request->post('action') == "Brouillon" ? 0 : 1);
            $blog->setDate( $date->format("Y-m-d"));

        }
        else
            $blog = new BlogPostEntity();

        $formBuilder = new BlogPostForm($blog);
        $formBuilder->buildForm();
        $form = $formBuilder->createForm($this->request);
        if($form->isSubmitted() && $form->isValid() ){
            $blog->setImage('/'.$this->uploadFile('imageFile'));
            $manager->save($blog);
            $this->response->redirect("/admin/postblogs");
        }
        $this->render('Back/Views/add_blog_post.html.twig',["form" => $form,"published"=>(int)$blog->getIsPublished()]);

    }
    private function uploadFile($name)
    {
        $target_dir = "uploads/";
        $fileName = uniqid().basename($_FILES[$name]["name"]);
        $target_file = $target_dir . $fileName;

        if(isset($_FILES)){
            move_uploaded_file($_FILES[$name]["tmp_name"], $target_file);
            return $target_file;
        }
        return null;

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
            $blog->setIsPublished($this->request->post('action') == "Brouillon" ? 0 : 1);
            $blog->setDateMaj($date->format("Y-m-d"));

        }
        else
            $blog = $manager->findById($this->request->get("idBlog"));

        $formBuilder = new BlogPostForm($blog);
        $formBuilder->buildForm();
        $form = $formBuilder->createForm($this->request);

        if($form->isSubmitted() && $form->isValid() ){
            $blog->setImage('/'.$this->uploadFile('imageFile'));
            $manager->save($blog);
            $this->response->redirect('/admin/postblogs');
        }

        $this->render('Back/Views/add_blog_post.html.twig',["form" => $form,"modified"=>true,"published"=>(int)$blog->getIsPublished()]);

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
}