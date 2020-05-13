<?php


namespace App\Src\Service;


class HttpResponse
{
    private $view;

    /**
     * @param mixed $view
     */
    public function setView($view)
    {
        $this->view = $view;
    }

    /**
     * @return mixed
     */
    public function getView()
    {
        return $this->view;
    }


    public function addheader($header)
    {
        header($header);
    }


}