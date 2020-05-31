<?php


namespace App\Src\Service\HTTP;




class Session
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
    static public function unset($name)
    {
        if(isset($_SESSION[$name]))
            unset($_SESSION[$name]);
    }

}