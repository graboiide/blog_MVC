<?php


namespace App\Src\Service\HTTP;


class  HttpRequest
{
    private $get;
    private $post;
    private $cookie;

    public function __construct()
    {
        $this->setGlobal();
    }


    private function setGlobal()
    {

        $this->get = filter_input_array(INPUT_GET);
        $this->post = filter_input_array(INPUT_POST);
        $this->cookie = filter_input_array(INPUT_COOKIE);

    }
    public function paramsRoute($data)
    {
        $this->get = array_merge($data,(array)$this->get);
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

        $server = filter_input_array(INPUT_SERVER);

        return $server['REQUEST_METHOD'];
    }
    public function uri()
    {
        $server = filter_input_array(INPUT_SERVER);
        return $server['REQUEST_URI'];
    }
    public function post($key=null)
    {
        if($key === null)
            return $this->post;
        return isset($this->post[$key]) ? $this->post[$key] : null;
    }




}