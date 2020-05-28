<?php


namespace App\Src\Service\Manager;


use PDO;

class UserManager extends BackManager
{
    public function getUser($id)
    {

        try {
            $select = implode(',user.',$this->attributes);
            $sql = 'SELECT '.$select.',role.role FROM user INNER JOIN role ON user.id=role.user_id WHERE user.id = :id';
            $request = $this->db->prepare($sql);
            $request->bindValue(':id',$id,PDO::PARAM_INT);
            $request->execute();
            return $request->fetch();
        }catch (\Exception $e){
            echo $e->getMessage();
        }
        return null;

    }
}