<?php


namespace App\Src\Service;


class  HttpRequest
{
    public function cookie($key)
    {
        return isset($_COOKIE[$key]) ? $_COOKIE[$key] : null;
    }
    public function get($key)
    {
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