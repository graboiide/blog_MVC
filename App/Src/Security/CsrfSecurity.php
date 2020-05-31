<?php


namespace App\Src\Security;


use App\Src\Service\HTTP\Session;

class CsrfSecurity
{
    /**
     * @return string
     */
    static public function generateToken()
    {
        $token = uniqid();
        Session::set('token',$token);
        return $token;
    }

    /**
     * @param $token
     * @return bool
     */
    static public function isValid($token)
    {

        if(Session::get('token') == $token){
            return true;
        }
        return false;
    }

}