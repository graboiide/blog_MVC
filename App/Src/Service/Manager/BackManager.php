<?php


namespace App\Src\Service\Manager;

use App\Src\Service\Entity\Entity;
use Exception;

class BackManager extends Manager
{

    public function save(Entity $entity)
    {
        $exist = $entity->getId() !== null ? true : false;

        if(!$exist)
            //ajouter une entité en bdd
           return $this->add($entity);

        else
            //modifier une entité en bdd
           return $this->update($entity);

    }

    /**
     * Ajoute une entité en bdd
     * @param Entity $entity
     * @return int|null
     */
    protected function add(Entity $entity)
    {
        //Créer le tableau de colonne
        $select = implode(', ',$entity->extractAttributes());
        $tokens = implode(', :',$entity->extractAttributes());

        $sql = 'INSERT INTO'.' '.$entity->extractTable().' ('.$select.') VALUES ( :'.$tokens.')';


        $request = $this->db->prepare($sql);

        foreach ($entity->extractAttributes(false) as $key => $value)
            $request->bindValue(':'.$key,$value);

        $request->execute();
        return $this->db->lastInsertId();
    }

    /**
     * Modifie un entité en bdd
     * @param Entity $entity
     */
    protected function update(Entity $entity)
    {

        $sql = array_map(function ($data){
            return ''.$data.' = :'.$data;
        },$entity->extractAttributes());

        $set = implode(', ',$sql);
        $q = 'UPDATE '.$entity->extractTable().' SET '.$set.' WHERE id=:id';

        try {
            $request = $this->db->prepare($q);

            foreach ($entity->extractAttributes(false) as $key => $value)
                $request->bindValue(':' . $key, $value);

            $request->execute();
        }catch (Exception $e){
            print_r($e->getMessage()) ;
        }
        return $this->db->lastInsertId();

    }

}