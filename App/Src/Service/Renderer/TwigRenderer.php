<?php


namespace App\Src\Service\Renderer;


use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class TwigRenderer implements RendererInterface
{
    private $twig;
    public function __construct($defaultPath)
    {
        $loader = new FilesystemLoader($defaultPath);
        $this->twig = new Environment($loader);
    }

    public function render($path,$params =[]):void
    {
        try {
            print_r($this->twig->render($path, $params)) ;
        } catch (LoaderError $e) {
        } catch (RuntimeError $e) {
        } catch (SyntaxError $e) {
        }
    }

    public function addGlobal($key,$value)
    {
        $this->twig->addGlobal($key,$value);
        return $this;
    }

}