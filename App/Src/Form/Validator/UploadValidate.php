<?php


namespace App\Src\Form\Validator;


class UploadValidate extends Validator
{
    private $fileName;
    private $typeAccepted =['jpg','png','jpeg','gif'];
    private $sizeMax = 2;


    public function isValid()
    {

        $files = $_FILES[$this->fieldChild->getName()];
        // si aucuns fichiers charger on ne procede pas au validation
        if($files["name"] == null){
            return true;
        }
        $imageFileType = strtolower(pathinfo($files['name'],PATHINFO_EXTENSION));

        if(!in_array($imageFileType,$this->typeAccepted)){
            $this->listErrors['size'] = "Le fichier doit être une image jpg,png ou gif";
            return  false;
        }
        if($files['size']/1000000 >= $this->sizeMax){
            $this->listErrors['size'] = "Le fichier est trop volumieux (".($files['size']/1000000)." mo)";
            return false;
        }


        return true;
    }


    /**
     * @param mixed $typeAccepted
     */
    public function setTypeAccepted($typeAccepted): void
    {
        $this->typeAccepted = $typeAccepted;
    }

}