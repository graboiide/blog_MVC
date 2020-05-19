<?php


namespace App\Src\Service\HTTP;


class  HttpRequest
{

    
    public function cookie($key)
    {
        $cookie = filter_input(INPUT_COOKIE,$key);
        return isset($cookie) ? $cookie : null;
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