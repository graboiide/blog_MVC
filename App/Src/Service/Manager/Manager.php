<?php


namespace App\Src\Service\Manager;
use App\Src\Service\Converter\NamingConventionConverter;
use App\Src\Service\Entity\Entity;
use Exception;
use PDO;


class Manager
{
    private $db;
    private $convert;

    public function __construct($db)
    {
        /**
         * @var PDO $db
         */
        $this->db = $db;
        $this->convert = new NamingConventionConverter();
    }


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
    protected function add(Entity $entity)
    {
        $tokens = [];
        //Créer le tableau de colonne en snake_case
        foreach (array_keys($entity->getProperties()) as $key)
            if($key !== "id")
                $tokens[] = $this->convert->camelCaseToSnakeCase($key);

        $sql = 'INSERT INTO '.$entity->getClass().' ('.implode(',',$tokens).') VALUES ( :'.implode(', :',$tokens).')';

        try {
            $request = $this->db->prepare($sql);
            foreach ($entity->getProperties() as $key => $value)
            {
                if($key !== "id")
                {
                    $key = $this->convert->camelCaseToSnakeCase($key);
                    $request->bindValue(':'.$key,$value);
                }
            }
            $request->execute();
            return $this->db->lastInsertId();
        }catch (Exception $e){
            echo $e->getMessage();
        }
        return null;
    }
    protected function update(Entity $entity):void
    {
        $sql='';
        foreach ($entity->getProperties() as $key => $value)
        {
            if($key !== "id")
            {
                if($value !== null)
                {
                    $key = $this->convert->camelCaseToSnakeCase($key);
                    $sql .= $key.'=:'.$key.', ';
                }
            }
        }
        $sql = substr($sql, 0, -2);
        $q = 'UPDATE '.$entity->getClass().' SET '.$sql.' WHERE id=:id';
        try {
            $request = $this->db->prepare($q);
            foreach ($entity->getProperties() as $key => $value)
            {
                if($value !== null) {
                    $key = $this->convert->camelCaseToSnakeCase($key);
                    $request->bindValue(':' . $key, $value);
                }
            }
            $request->execute();
        }catch (Exception $e){
            echo $e->getMessage();
        }

    }



}