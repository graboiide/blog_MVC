<?php


namespace App\Src\Service\Manager;


use App\Src\Service\Entity\BlogPostEntity;
use Exception;
use PDO;

class BlogPostManager extends BackManager
{
    /**
     * Ajouter requetes spécifique ici
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function listPublished($limit = 0,$offset = 10)
    {
        try {
            $select = implode(', blog_post.',$this->attributes);
            $sql = 'SELECT '.$select.',user.name as author FROM blog_post
            INNER JOIN user ON user.id = blog_post.user_id
        
            WHERE is_published = 1 ORDER BY id DESC LIMIT :limit , :offset';

            $request = $this->db->prepare($sql);
            $request->bindValue(':limit', $limit,PDO::PARAM_INT);
            $request->bindValue(':offset', $offset,PDO::PARAM_INT);
            $request->execute();
            $listBlogs = [];
            foreach ($request->fetchAll() as $data){
                $listBlogs[] = new BlogPostEntity($data);
            }
            return $listBlogs;
        }catch (Exception $e){
            var_dump($e->getMessage());
        }

        return null;
    }

    public function listBlogs($limit = 0,$offset = 10)
    {

        try {
            $select = implode(', blog_post.',$this->attributes);
            $sql = ' SELECT '.$select.', user.name as author, COUNT(comment.message) AS nbComment FROM blog_post 
            INNER JOIN user ON blog_post.user_id = user.id
            LEFT JOIN comment ON comment.post_blog_id = blog_post.id 
            GROUP BY blog_post.id
            ORDER BY blog_post.id DESC LIMIT :limit , :offset';




            $request = $this->db->prepare($sql);
            $request->bindValue(':limit', $limit,PDO::PARAM_INT);
            $request->bindValue(':offset', $offset,PDO::PARAM_INT);
            $request->execute();
            $listBlogs = [];

            foreach ($request->fetchAll() as $data){
                $blogPost = new BlogPostEntity($data);
                $blogPost->setAuthor($data['author']);
                $listBlogs[] = $blogPost;
            }
            return $listBlogs;
        }catch (Exception $e){
            echo $e->getMessage();
        }

        return null;
    }

    /**
     * @param $id
     * @return BlogPostEntity
     */
    public function findById($id)
    {
        try {
            $sql = 'SELECT '.implode(', ',$this->attributes).' FROM blog_post WHERE id = :id';

            $request = $this->db->prepare($sql);
            $request->bindValue(':id', $id,PDO::PARAM_INT);
            $request->execute();
            $data = $request->fetch();
            return new BlogPostEntity((array)$data);

        }catch (Exception $e){
           print_r($e->getMessage()) ;
        }
        return null;

    }

    /**
     * Retourne le blog avant pendant et aprés
     * @param $id
     * @return array
     */
    public function findBlogsForNav($id):array
    {
        $sql = 'SELECT * FROM ( SELECT * FROM blog_post A WHERE A.id < :id AND A.is_published = 1 ORDER BY A.id DESC LIMIT 1) C 
        UNION SELECT * FROM ( SELECT * FROM blog_post A WHERE A.id = :id  AND A.is_published = 1 ORDER BY A.id LIMIT 1) C
        UNION SELECT * FROM ( SELECT * FROM blog_post A WHERE A.id > :id  AND A.is_published = 1 ORDER BY A.id LIMIT 1) C
        ';
        $request = $this->db->prepare($sql);
        $request->bindValue(':id',$id,PDO::PARAM_INT);
        $request->execute();
        $listBlogs = [];
        $key = 'prev';
        foreach ($request->fetchAll() as $data){
            if($data['id'] == $id)
                $key = 'target';
            if($data['id'] > $id)
                $key = 'next';
            $listBlogs[$key] = new BlogPostEntity($data);
        }

        return $listBlogs;

    }

    /**
     * @return |null
     */
    public function count($isPublished = true)
    {
        try {
            $sql = 'SELECT COUNT(*) as nb FROM blog_post '.($isPublished == true ? 'WHERE is_published = 1' : '');
            $request = $this->db->query($sql);
            $request->execute();
            return $request->fetch()['nb'];

        }catch (Exception $e){
            var_dump($e->getMessage().'<br>');
        }

        return null;
    }

    /**
     * @return |null
     */
    public function countBrouillon()
    {
        try {
            $sql = 'SELECT COUNT(*) as nb FROM blog_post WHERE is_published != 1';
            $request = $this->db->query($sql);
            $request->execute();
            return $request->fetch()['nb'];

        }catch (Exception $e){
            var_dump($e->getMessage().'<br>');
        }

        return null;
    }

}
