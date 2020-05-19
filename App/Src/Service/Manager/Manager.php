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

    /**
     * Ajoute une entité en bdd
     * @param Entity $entity
     * @return int|null
     */
    protected function add(Entity $entity)
    {
        //Créer le tableau de colonne
        $select = array_keys(array_filter($entity->getProperties()));
        $sql = 'INSERT INTO '.$entity->getClass().' ('.$this->convert->camelCaseToSnakeCase(implode(',',$select)).') 
        VALUES ( :'.$this->convert->camelCaseToSnakeCase(implode(', :',$select)).')';

            $request = $this->db->prepare($sql);
            foreach (array_filter($entity->getProperties()) as $key => $value)
            {
                    $key = $this->convert->camelCaseToSnakeCase($key);
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

        $q = 'UPDATE '.$entity->getClass().' SET '.$this->convert->camelCaseToSnakeCase(implode(', ',$sql)).' WHERE id=:id';
        try {
            $request = $this->db->prepare($q);
            foreach (array_filter($entity->getProperties()) as $key => $value)
            {
                    $key = $this->convert->camelCaseToSnakeCase($key);
                    $request->bindValue(':' . $key, $value);

            }
            $request->execute();
        }catch (Exception $e){
            echo $e->getMessage();
        }

    }

    /**
     * Recupere la liste des entités dont la class et passé parametre
     * @param $className
     * @param null $params
     * @return array
     */
    public function findAll($className,$params = null)
    {
        /**
         * @var Entity $entity
         */
        $entity = new $className;
        $fields = [];
        foreach (array_keys($entity->getProperties()) as $key)
            $fields[] = $this->convert->camelCaseToSnakeCase($key);
        $sql = 'SELECT '.implode(", ",$fields).' FROM '.$entity->getClass();

        if($params !== null) {
            $prepare = [];
            // WHERE
            if(isset($params['criteria'])){
                $where = [];

                foreach ($params['criteria'] as $key => $value){
                    $where[$key] = $key.' = :'.$key.' ';
                    $prepare[':'.$key] = $value;
                }

                $sql .= ' WHERE '.implode(' AND ',$where);
            }
            // ORDER BY
            $sql .= isset($params['order']) ? ' ORDER BY '.$params['order'].' DESC ' : '';
            if(isset($params['limit'])){
                $sql .= ' LIMIT :limit';
                $prepare[':limit']=$params['limit'];
                if (isset($params['offset'])){
                    $sql .= ', :offset';
                    $prepare[':offset']=$params['offset'];
                }

            }

            $request = $this->db->prepare($sql);
            if($prepare !== null)
                foreach ($prepare as $key => $value){
                    //filtre pour la limit
                    if($key === ':limit' || $key === ':offset')
                        $request->bindValue($key, $value,PDO::PARAM_INT);
                    else
                        $request->bindValue($key, $value);
                }

            $request->execute();
        } else {
            $request = $this->db->query( $sql);
        }

        $request->setFetchMode(PDO::FETCH_ASSOC | PDO::FETCH_PROPS_LATE);
        $entities = [];
        foreach ($request->fetchAll() as $data)
            $entities[] = new $className($data);
        return $entities;

    }

    /**
     * @param $className
     * @return null
     */
    public function getEntityManager($className)
    {
        if(class_exists($className))
            return new $className();
        else
            return null;
    }

}