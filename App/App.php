<?php

namespace App;

use AltoRouter;
use App\Src\Controller\backController;
use App\Src\Service\Config;
use App\Src\Service\HTTP\HttpRequest;
use App\Src\Service\Renderer\TwigRenderer;
use Exception;


class App
{


    private $renderer;
    private $router;

    public function __construct()
    {
        $this->router = new AltoRouter();


        $this->renderer = new TwigRenderer(Config::getVar('twig template_path'));


    }

    /**
     * @return AltoRouter
     */
    public function getRouter(): AltoRouter
    {
        return $this->router;
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
        $pathFileRoutes = Config::getVar("router route_path");
        if(is_file($pathFileRoutes))

            $json = file_get_contents($pathFileRoutes);
        else
            return 'No route file found';

        //add map at router
        $routes =json_decode($json,true);
        foreach ($routes as $key => $route){
            $this->router->map(isset($route['method']) ? $route['method'] : 'GET|POST',$key,$route['controller'].'#'.$route['module'],$route['name']);
        }
        if($match = $this->router->match())
        {
            $request = new HttpRequest();
            $request->paramsRoute($match['params']);
            $controllerClass = '\App\Src\Controller\\'.explode('#',$match['target'])[0].'Controller';
            return new $controllerClass($this,explode('#',$match['target'])[1],$request);

        }

        $this->renderer->render('Errors/404.html.twig');
        return null;


    }

}