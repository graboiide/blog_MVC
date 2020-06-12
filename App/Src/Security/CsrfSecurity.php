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
       //dd('generate token');
        Session::set('token',$token);
        return $token;
    }

    /**
     * @param $token
     * @return bool
     */
    static public function isValid($token)
    {

        if($token != null && Session::get('token') == $token){
            return true;
        }
        //Session::unset('token');

        return false;
    }

}