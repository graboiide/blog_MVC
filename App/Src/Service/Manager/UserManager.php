<?php


namespace App\Src\Service\Manager;


use App\Src\Service\Entity\UserEntity;
use Exception;
use PDO;

class UserManager extends BackManager
{
    public function getUser($id)
    {

        try {
            $select = implode(',user.',$this->attributes);
            $sql = 'SELECT '.$select.',role.role FROM user LEFT JOIN role ON user.id=role.user_id WHERE user.id = :id';
            $request = $this->db->prepare($sql);
            $request->bindValue(':id',$id,PDO::PARAM_INT);
            $request->execute();
            return new UserEntity($request->fetch());
        }catch (Exception $e){
            print_r($e->getMessage() );
        }
        return null;

    }
    public function getUserByName($name)
    {

        try {
            $select = implode(',user.',$this->attributes);

            $sql = 'SELECT '.$select.',role.role FROM user 
            LEFT JOIN role ON user.id = role.user_id 
            LEFT JOIN token ON token.user_id = user.id
            WHERE user.name = :name';

            $request = $this->db->prepare($sql);
            $request->bindValue(':name',$name,PDO::PARAM_STR);
            $request->execute();

            return  new UserEntity($request->fetch());
        }catch (Exception $e){
            print_r($e->getMessage() );
        }
        return null;

    }
    public function getUserByToken($token)
    {

        try {
            $select = implode(',user.',$this->attributes);

            $sql = 'SELECT '.$select.',role.role FROM user 
            INNER JOIN role ON user.id=role.user_id 
            INNER JOIN token ON user.id = token.user_id
            WHERE token.token = :token';
            $request = $this->db->prepare($sql);
            $request->bindValue(':token',$token);
            $request->execute();
            $data = $request->fetch();
            if(!$data){
                return new UserEntity(["role"=>"user"]);
            }
            else{
                return  new UserEntity($data);
            }

        }catch (Exception $e){
            print_r($e->getMessage() );
        }
        return null;

    }
    public function updateToken($token,$userId)
    {

        $sql = 'UPDATE token SET token = :token WHERE user_id = :user_id';
        $request = $this->db->prepare($sql);
        $request->bindValue(':token',$token);
        $request->bindValue(':user_id',$userId);
        $request->execute();
    }

    /**
     * @param $token
     * @param $userId
     */
    public function createToken($token,$userId)
    {
        try {
            $sql = 'INSERT INTO token (token,user_id) VALUES (:token,:user_id)';
            $request = $this->db->prepare($sql);
            $request->bindValue(':token',$token);
            $request->bindValue(':user_id',$userId);
            $request->execute();
        }catch (Exception $e){
            print_r($e->getMessage());
        }
    }
    public function createRole($role,$userId)
    {
        try {
            $sql = 'INSERT INTO role (role,user_id) VALUES (:role,:user_id)';
            $request = $this->db->prepare($sql);
            $request->bindValue(':role',$role);
            $request->bindValue(':user_id',$userId);
            $request->execute();
        }catch (Exception $e){
            print_r($e->getMessage());
        }
    }
}