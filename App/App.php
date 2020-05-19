<?php

namespace App;

use AltoRouter;
use App\Src\Controller\backController;
use App\Src\Service\Config;
use App\Src\Service\Renderer\TwigRenderer;
use Exception;


class App
{

    private $config;
    private $renderer;
    private $router;



    public function __construct()
    {
        $this->router = new AltoRouter();
        $this->config = new Config();
        $this->renderer = new TwigRenderer($this->config->getVar('twig template_path'));


    }

    /**
     * @return AltoRouter
     */
    public function getRouter(): AltoRouter
    {
        return $this->router;
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return TwigRenderer
     */
    public function getRenderer()
    {
        return $this->renderer;
    }
    public function run()
    {

        try {
            if ($controller = $this->getController()) {
                /**
                 * @var $controller BackController
                 */
                $controller->execute();

            }
        } catch (Exception $e) {
        }


    }

    /**
     * Retourne le bon controller
     * @throws Exception
     */
    public function getController()
    {
        $pathFileRoutes = $this->config->getVar("router route_path");
        if(is_file($pathFileRoutes))

            $json = file_get_contents($pathFileRoutes);
        else
            return 'No route file found';



        //add map at router
        $routes =json_decode($json,true);
        foreach ($routes as $key => $route){
            $this->router->map(isset($route['method']) ? $route['method'] : 'GET',$key,$route['controller'].'#'.$route['module'],$route['name']);
        }
        if($match = $this->router->match())
        {
            $_GET = array_merge($_GET,$match['params']);
            $controllerClass = '\App\Src\Controller\\'.explode('#',$match['target'])[0].'Controller';
            return new $controllerClass($this,explode('#',$match['target'])[1],$match['params']);

        }
        else{
            $this->renderer->render('Errors/404.html.twig');
        }
        return null;
    }

}