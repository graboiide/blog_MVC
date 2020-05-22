<?php


namespace App\Src\Service\Manager;


use App\Src\Service\Converter\NamingConverter;
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
            $sql = 'SELECT '.$select.' FROM blog_post
        
            WHERE is_published = 1 LIMIT :limit , :offset';
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
            print_r($e->getMessage());
        }

        return null;
    }
    public function findById($id)
    {
        $sql = 'SELECT '.NamingConverter::toSnakeCase(implode(', ',$this->propertiesEntity)).' FROM blog_post WHERE id = :id';
        $request = $this->db->prepare($sql);
        $request->bindValue(':id', $id,PDO::PARAM_INT);
        $request->execute();
        $data = $request->fetch();
        $blog = new BlogPostEntity($data);
        return $blog;
    }
}
