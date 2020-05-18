<?php


namespace App\Src\Service\HTTP;


class  HttpRequest
{
    public function cookie($key)
    {
        return isset($_COOKIE[$key]) ? $_COOKIE[$key] : null;
    }
    public function get($key,$protect = true)
    {
        if($protect)
            return isset($_GET[$key]) ? htmlentities($_GET[$key]) : null;
        else
            return isset($_GET[$key]) ? $_GET[$key] : null;
    }
    public function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
    public function post($key)
    {
        return isset($_POST[$key]) ? $_POST[$key] : null;
    }

}