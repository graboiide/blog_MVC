<?php


namespace App\Src\Service\Upload;


class UploadFile
{
    private $fileName;
    private $path ;

    public function __construct($path = "App/Public/uploads/")
    {

        $this->path = $path;
    }
    public function setFile($fileName)
    {
        $this->fileName = $fileName;
        return $this;
    }
    public function saveFile()
    {

        if(!isset($_FILES[$this->fileName]) || isset($_FILES[$this->fileName]) && $_FILES[$this->fileName]["name"] == null)
            return null;
        $target_dir = $this->path;
        $fileName = uniqid().basename($_FILES[$this->fileName]["name"]);
        $target_file = $target_dir . $fileName;

        if(isset($_FILES)){
            move_uploaded_file($_FILES[$this->fileName]["tmp_name"], $target_file);
            return '/'.$target_file;
        }
        return null;
    }
}