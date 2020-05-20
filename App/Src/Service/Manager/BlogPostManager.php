<?php


namespace App\Src\Service\Manager;


use App\Src\Service\Converter\NamingConverter;
use App\Src\Service\Entity\BlogPostEntity;
use PDO;

class BlogPostManager extends BackManager
{
/**
 * Ajouter requetes spécifique ici
 */
    public function listPublished($limit = 0,$offset = 10)
    {
        $sql = 'SELECT '.NamingConverter::toSnakeCase(implode(', ',$this->propertiesEntity)).' FROM blog_post WHERE is_published = 1 LIMIT :limit , :offset';
        $request = $this->db->prepare($sql);
        $request->bindValue(':limit', $limit,PDO::PARAM_INT);
        $request->bindValue(':offset', $offset,PDO::PARAM_INT);
        $request->execute();
        $listBlogs = [];
        foreach ($request->fetchAll() as $data){
            $listBlogs[] = new BlogPostEntity($data);
        }
        return $listBlogs;

    }
}