<?php

namespace App;

use AltoRouter;
use App\Src\Service\Config;
use http\Params;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class App
{
    private $twig;
    private $config;

    public function __construct()
    {
        $this->config = new Config();
        $loader = new FilesystemLoader($this->config->getVar("twig template_path"));
        $twig = new Environment($loader);
        $this->twig = $twig;
    }
    public function run()
    {
        if($controller = $this->getController())
            $controller->execute();

    }

    /**
     * Retourne la bon controller
     */
    public function getController()
    {
        $pathFileRoutes = $this->config->getVar("router route_path");
        if(file_exists($pathFileRoutes))
            $json = file_get_contents($pathFileRoutes);
        else
            die('No route file found');

        $router = new AltoRouter();

        //add map at router
        $routes =[];
        foreach (json_decode($json,true) as $key => $route)
            $routes[$key] = $route;
        foreach ($routes as $key => $route){
            $router->map(isset($route['method']) ? $route['method'] : 'GET',$key,$route['controller'].'#'.$route['module'],$route['name']);
        }
        if($match = $router->match())
        {

            if($match['params']){
                $_GET = array_merge($_GET,$match['params']);
            }
            $controllerClass = '\App\Src\Controller\\'.explode('#',$match['target'])[0].'Controller';
            return new $controllerClass($this,explode('#',$match['target'])[1],$match['params']);

        }
        else{
            $this->render('Errors/404.html.twig');
        }
        return null;
    }

    public function render($path,$params = null)
    {
        if(file_exists($this->config->getVar('twig template_path').$path))
        {
            if($params !== null)
                echo $this->twig->render($path,$params);
            else
                echo $this->twig->render($path);
        }

    }
}