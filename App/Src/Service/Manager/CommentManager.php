<?php


namespace App\Src\Service\Manager;

use App\Src\Service\Entity\CommentEntity;
use Exception;
use PDO;

class CommentManager extends BackManager
{

    public function listPublished($blogId)
    {

        try {
            $select = implode(', ',$this->attributes);

            $sql = 'SELECT '.$select.' FROM comment WHERE is_validate = 1 AND post_blog_id = :post_blog_id ORDER BY id DESC';
            $request = $this->db->prepare($sql);

            $request->bindValue(':post_blog_id', (int)$blogId);
            $request->execute();
            $listComments = [];
            foreach ($request->fetchAll() as $data){
                $listComments[] = new CommentEntity($data);
            }
            return $listComments;
        }catch (Exception $e){
            var_dump($e);
        }
        return null;

    }
    public function all($limit,$offset)
    {

        try {
            $select = implode(', ',$this->attributes);

            $sql = 'SELECT '.$select.' FROM comment ORDER BY id DESC LIMIT :limit,:offset';

            $request = $this->db->prepare($sql);
            $request->bindValue(':limit',$limit,PDO::PARAM_INT);
            $request->bindValue(':offset',$offset,PDO::PARAM_INT);

            $request->execute();
            $listComments = [];
            foreach ($request->fetchAll() as $data){
                $listComments[] = new CommentEntity($data);
            }
            return $listComments;
        }catch (Exception $e){
            var_dump($e);
        }
        return null;

    }
    public function listNotValidate($limit,$offset)
    {

        try {
            $select = implode(', ',$this->attributes);

            $sql = 'SELECT '.$select.' FROM comment WHERE is_validate = 0 ORDER BY id DESC LIMIT :limit,:offset';
            $request = $this->db->prepare($sql);
            $request->bindValue(':limit',$limit,PDO::PARAM_INT);
            $request->bindValue(':offset',$offset,PDO::PARAM_INT);

            $request->execute();
            $listComments = [];
            foreach ($request->fetchAll() as $data){
                $listComments[] = new CommentEntity($data);
            }
            return $listComments;
        }catch (Exception $e){
            var_dump($e);
        }
        return null;

    }
    public function Validate($idCom)
    {

        try {
            $sql = 'UPDATE comment SET is_validate = 1 WHERE id = :id';
            $request = $this->db->prepare($sql);
            $request->bindValue(':id',$idCom);
            $request->execute();
            return true;
        }catch (Exception $e){
            var_dump($e);
        }
        return false;

    }

    public function countByBlog($idBlog)
    {
        try {
            $sql = 'SELECT COUNT(*) as nb FROM comment WHERE is_validate = 1 && post_blog_id = :post_blog_id ';
            $request = $this->db->prepare($sql);

            $request->bindValue(':post_blog_id', (int)$idBlog,PDO::PARAM_INT);
            $request->execute();
            return $request->fetch()['nb'];

        }catch (Exception $e){
            var_dump($e->getMessage().'<br>');
        }

        return null;
    }
    public function countForValidate()
    {
        try {
            $sql = 'SELECT COUNT(*) as nb FROM comment WHERE is_validate = 0';
            $request = $this->db->prepare($sql);
            $request->execute();
            return $request->fetch()['nb'];

        }catch (Exception $e){
            var_dump($e->getMessage().'<br>');
        }

        return null;
    }
    public function countAll()
    {
        try {
            $sql = 'SELECT COUNT(*) as nb FROM comment';
            $request = $this->db->query($sql);
            $request->execute();
            return $request->fetch()['nb'];

        }catch (Exception $e){
            var_dump($e->getMessage().'<br>');
        }

        return null;
    }
}
