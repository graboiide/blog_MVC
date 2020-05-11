<?php


namespace App\Src\Controller;


use App\App;


class backController
{
    protected $app;
    protected $action;
    protected $params;

    public function __construct(App $app, $action, $params = null)
    {
        $this->app = $app;
        $this->action = $action;
        $this->params = $params;

    }
    public function execute()
    {
        $method = $this->action;

        if(!is_callable([$this,$method]))
        {
            throw new \Exception("L'action $this->action n'existe pas dans le controller");
        }

        $this->$method();
    }

}