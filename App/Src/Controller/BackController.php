<?php


namespace App\Src\Controller;


use App\App;

use App\Src\Service\Config;
use App\Src\Service\Connect\HandlerUser;
use App\Src\Service\DataBase\DBFactory;

use App\Src\Service\FlashBag\FlashBag;
use App\Src\Service\HTTP\HttpRequest;
use App\Src\Service\HTTP\HttpResponse;
use App\Src\Service\HTTP\Session;
use App\Src\Service\Manager\Manager;



class BackController
{
    protected $app;
    protected $action;
    protected $manager;
    protected $request;
    protected $response;
    protected $userHandler;
    protected $flash;

    public function __construct(App $app, $action,HttpRequest $request)
    {
        $this->app = $app;
        $this->action = $action;
        $this->request = $request;
        $this->response = new HttpResponse();
        $this->manager = new Manager(DBFactory::PDOMysqlDB(Config::getDBconnexion()));
        $this->userHandler = new HandlerUser($this->request,$this->manager);
        $this->flash();
    }

    /**
     * Enregistre le message alert flash
     */
    private function flash()
    {

         if(Session::get('flash') != null){
             //$this->flash = $_SESSION['flash'];
             $this->app->getRenderer()->addGlobal('flash',FlashBag::get());
         }

         Session::unset('flash');
    }


    public function execute()
    {
        //on récupere le nom de la class controller
        $class =explode('\\',get_class($this)) ;
        $class= end($class);
        //on recupere la liste des controllers protégé
        $protected = Config::getVar('connect protected_controllers');
        //partie protégé
            //on verifie qu'il est connecté ou que la variable session a le bon role
        if(array_key_exists($class,$protected) && (!$this->userHandler->isConnected() || Session::get('connect') != $protected[$class])){
            $response = new HttpResponse();
            $response->redirect($this->app->getRouter()->generate('connect'));
        }

        $method = $this->action;
        if(is_callable([$this,$method]))
        {
            $this->$method();
        }


    }

    public function render($path,$params = []):void
    {

        $this->app->getRenderer()

            ->render($path,$params);

    }

}