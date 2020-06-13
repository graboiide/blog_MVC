<?php

namespace App\Src\Service\FlashBag;
use App\Src\Service\Entity\FlashEntity;
use App\Src\Service\HTTP\Session;

class FlashBag
{

    static public function set($message,$type = 'success')
    {

        Session::set('flash',["message"=>$message,"type"=>$type]);
    }
    static public function get()
    {
        if(Session::get('flash') != null){
            return new FlashEntity(Session::get('flash'));
        }
        return null;

    }

}