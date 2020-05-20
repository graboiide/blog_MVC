<?php


namespace App\Src\Service\Manager;
use App\Src\Service\Converter\NamingConverter;
use App\Src\Service\Entity\Entity;
use Exception;

class BackManager extends Manager
{

    public function save(Entity $entity):void
    {
        $exist = $entity->getId() !== null ? true : false;

        if(!$exist)
            //ajouter une entité en bdd
            $this->add($entity);

        else
            //modifier une entité en bdd
            $this->update($entity);

    }

    /**
     * Ajoute une entité en bdd
     * @param Entity $entity
     * @return int|null
     */
    protected function add(Entity $entity)
    {

        //Créer le tableau de colonne
        $select = array_keys(array_filter($entity->getProperties()));
        $sql = 'INSERT INTO '.NamingConverter::toSnakeCase($entity->getClass()).' ('.NamingConverter::toSnakeCase(implode(',',$select)).') 
        VALUES ( :'.NamingConverter::toSnakeCase(implode(', :',$select)).')';

            $request = $this->db->prepare($sql);
            foreach (array_filter($entity->getProperties()) as $key => $value)
            {
                    $key = NamingConverter::toSnakeCase($key);
                    $request->bindValue(':'.$key,$value);
            }
            $request->execute();
            return $this->db->lastInsertId();
    }

    /**
     * Modifie un entité en bdd
     * @param Entity $entity
     */
    protected function update(Entity $entity):void
    {

        $sql = array_map(function ($data){
            return ''.$data.' = :'.$data;
        },array_keys(array_filter($entity->getProperties())));

        $q = 'UPDATE '.NamingConverter::toSnakeCase($entity->getClass()).' SET '.NamingConverter::toSnakeCase(implode(', ',$sql)).' WHERE id=:id';
        try {
            $request = $this->db->prepare($q);
            foreach (array_filter($entity->getProperties()) as $key => $value)
            {
                    $key = NamingConverter::toSnakeCase($key);
                    $request->bindValue(':' . $key, $value);

            }
            $request->execute();
        }catch (Exception $e){
            echo $e->getMessage();
        }

    }

}