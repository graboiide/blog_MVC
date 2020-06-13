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
        if($name != null)
            return isset($_SESSION[$name]) ? $_SESSION[$name] : null;
        else
            return $_SESSION;
    }
    static public function unset($name)
    {
        if(isset($_SESSION[$name]))
            unset($_SESSION[$name]);
    }

}