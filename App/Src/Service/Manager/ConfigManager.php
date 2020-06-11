<?php


namespace App\Src\Service\Manager;


use App\Src\Service\Entity\UserEntity;
use Exception;

class ConfigManager extends BackManager
{
    public function getConfig()
    {

        try {
            $select = implode(',user.',$this->attributes);
            $sql = 'SELECT '.$select.' FROM config WHERE id = 1';
            $request = $this->db->query($sql);

            $request->execute();
            return new UserEntity($request->fetch());
        }catch (Exception $e){
            print_r($e->getMessage() );
        }
        return null;

    }

}