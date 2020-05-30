<?php


namespace App\Src\Service\HTTP;


use SessionHandler;

class Session extends SessionHandler
{

    static public function set($name, $data=[])
    {
        if($name != null)
            $_SESSION[$name]=$data;

    }
    static public function get($name)
    {
        return isset($_SESSION[$name]) ? $_SESSION[$name] : null;
    }

}