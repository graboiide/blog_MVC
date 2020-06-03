<?php

namespace App\Src\Service\Connect;

use App\Src\Service\Entity\UserEntity;
use App\Src\Service\HTTP\HttpRequest;
use App\Src\Service\HTTP\Session;
use App\Src\Service\Manager\Manager;
use App\Src\Service\Manager\UserManager;

class HandlerUser
{
    private $request;
    private $manager;

    public function __construct(HttpRequest $request,Manager $manager)
    {
        $this->request = $request;
        $this->manager = $manager;
    }

    /**
     * Permet de connecter un utilisateur par cookie modifie le token
     * @param Manager $manager
     * @return bool
     */
    public function isConnected()
    {

        if(is_null(Session::get('connect'))){
            if(!is_null($this->request->cookie('token'))){
                /**
                 * @var UserManager $userManager
                 */

                $userManager = $this->manager->getEntityManager(UserEntity::class);
                $user = $userManager->getUserByToken($this->request->cookie('token'));
                Session::set('connect',$user->getRole());
                Session::set('user_id',$user->getId());
                $this->updateToken($user);
                return true;
            }
            return false;
        }
        return true;

    }
    private function updateToken(UserEntity $user)
    {
        //dd($user->getId());
        $id = uniqid();
        $userManager = $this->manager->getEntityManager(UserEntity::class);
        $userManager->updateToken($id,$user->getId());
        $this->request->setCookie(["token",$id,5]);


    }

    /**
     * @param UserEntity $user
     */
    public function connect(UserEntity $user)
    {
        Session::set('connect',$user->getRole());
        Session::set('user_id',$user->getId());
        $this->updateToken($user);

    }
}