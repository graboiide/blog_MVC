<?php


namespace App\Src\Service\Manager;

use App\Src\Service\Entity\ConfigEntity;
use Exception;

class ConfigManager extends BackManager
{
    public function getConfig()
    {
        //une seul config autorisé on va chercher l'id 1
        try {
            $select = implode(',',$this->attributes);
            $sql = 'SELECT '.$select.' FROM config WHERE id = 1';
            $request = $this->db->query($sql);

            $request->execute();
            return new ConfigEntity($request->fetch());
        }catch (Exception $e){
            print_r($e->getMessage() );
        }
        return null;

    }

}