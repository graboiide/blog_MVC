<?php


namespace App\Src\Service\HTTP;


class  HttpRequest
{
    private $get;
    private $post;
    private $cookie;
    private $server;

    public function __construct()
    {
        $this->setGlobal();
    }


    private function setGlobal()
    {

        $this->get = filter_input_array(INPUT_GET);
        $this->post = filter_input_array(INPUT_POST);
        $this->cookie = filter_input_array(INPUT_COOKIE);
        $this->server = filter_input_array(INPUT_SERVER);

    }
    public function paramsRoute($data)
    {
        $this->get = array_merge($data,(array)$this->get);
    }
    public function setCookie($data)
    {
        setcookie($data[0],$data[1],time() + $data[2] * 24 * 3600,'/');

    }
    public function cookie($key)
    {
        return isset($this->cookie[$key]) ? $this->cookie[$key] : null;
    }
    public function get($key)
    {
        return isset($this->get[$key]) ? $this->get[$key] : null;
    }
    public function method()
    {
        return $this->server['REQUEST_METHOD'];
    }
    public function uri()
    {

        return $this->server['REQUEST_URI'];
    }
    public function referer()
    {
        return $this->server['HTTP_REFERER'];
    }
    public function post($key=null)
    {
        if($key === null)
            return $this->post;
        return isset($this->post[$key]) ? $this->post[$key] : null;
    }
    public function file($key)
    {
        if($key === null)
            return $_FILES;
        else
            return $_FILES[$key];
    }




}