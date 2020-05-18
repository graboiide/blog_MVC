<?php


namespace App\Src\Service\HTTP;


class HttpResponse
{

    public function addheader($header)
    {
        header($header);
    }


}