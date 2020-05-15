<?php


namespace App\Src\Controller;


use App\App;

use Exception;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;


class BackController
{
    protected $app;
    protected $action;
    protected $params;
    private $twig;

    public function __construct(App $app, $action, $params = null)
    {
        $this->app = $app;
        $this->action = $action;
        $this->params = $params;
        $loader = new FilesystemLoader($this->app->getConfig()->getVar("twig template_path"));
        $this->twig = new Environment($loader);

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
            echo $e->getMessage();
        }

        $this->$method();
    }

    public function render($path,$params = []):void
    {

        $this->app->getRenderer()
            ->addGlobal('router',$this->app->getRouter())
            ->render($path,$params);

    }

}