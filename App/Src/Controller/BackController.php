<?php


namespace App\Src\Controller;


use App\App;

use App\Src\Service\Config;
use App\Src\Service\DataBase\DBFactory;
use App\Src\Service\HTTP\HttpRequest;
use App\Src\Service\Manager\Manager;
use Exception;

class BackController
{
    protected $app;
    protected $action;

    protected $manager;
    protected $request;

    public function __construct(App $app, $action,HttpRequest $request)
    {
        $this->app = $app;
        $this->action = $action;
        $this->request = $request;

        $this->manager = new Manager(DBFactory::PDOMysqlDB(Config::getVar('database')));

    }


    public function execute()
    {
        $method = $this->action;
        try {
            if(!is_callable([$this,$method]))
            {
                /**
                 * @throws Exception
                 */
                throw new Exception("L'action $this->action n'existe pas dans le controller");
            }
        }catch (Exception $e)
        {
            print_r($e->getMessage()) ;
        }

        $this->$method();
    }

    public function render($path,$params = []):void
    {

        $this->app->getRenderer()
            ->addGlobal('router',$this->app->getRouter())
            ->addGlobal('assets','/')
            ->render($path,$params);

    }

}