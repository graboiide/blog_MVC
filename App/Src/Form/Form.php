<?php


namespace App\Src\Form;


use App\Src\Form\Field\Field;
use App\Src\Form\Field\Hidden;
use App\Src\Security\CsrfSecurity;
use App\Src\Service\HTTP\HttpRequest;
use App\Src\Service\HTTP\Session;


class Form
{
    /**
     * @var FormBuilder $formBuilder
     */
    private $formBuilder;
    private $fieldsError;
    private $request;
    private $template;
    private $csrf = false;

    public function __construct(FormBuilder $formBuilder,HttpRequest $request)
    {
        $this->formBuilder = $formBuilder;
        $this->request = $request;
        $this->template = 'bootstrap';
    }

    /**
     * Construit le widget et affiche le message derreur en dessous
     * @param $name
     * @return mixed
     */
    public function widget($name)
    {

        /**
         * @var Field $field
         */
        //si le field exist pas on return un chaine vide
        if(!array_key_exists($name,$this->formBuilder->getFields()))
            return '';
        //récupere le field dans le builder
        $field = $this->formBuilder->getField($name);
        $widget='';

        $widget .= $this->addCsrfProtection();

        if($field->getErrors() != null)
            foreach ($field->getErrors() as $error)
                $widget .= '<span class="error text-danger">'.$error.'</span><br>';
        return $widget.($field->getLabel() == null ? '' : $field->createLabel()).' <div class="form-group">'.$field->getWidget().'</div>';
    }
    public function error($nameWidget)
    {
        $errors = '';

        /**
         * @var Field $field
         */
        $field = $this->formBuilder->getField($nameWidget);

        if($field->getErrors() != null){
            foreach ($field->getErrors() as $error)
                $errors .= '<span class="error text-danger">'.$error.'</span><br>';


        }
        
        $field->resetErrors();
        return $errors;


    }



    /**
     * Recupere seulement le champs, permet de recuperer leur valeurs simplement
     * @param $name
     * @return mixed|string
     */
    public function field($name)
    {
        if(!array_key_exists($name,$this->formBuilder->getFields()))
            return '';

        return $this->formBuilder->getField($name);
    }

    /**
     * Affiche l'intégralité du formulaire avec les erreurs
     * @return string
     */
    public function createView()
    {

        $form = '<form action="#">';
        /**
         * @var Field $field
         */
        foreach ($this->formBuilder->getFields() as $field){
            $form.= $this->widget($field->getName());
        }
        $form .= '</form>';

        return $form;
    }

    /**
     * Ajout de validation par token
     * @return string
     */
    private function addCsrfProtection()
    {
        if(!$this->csrf){
            $this->csrf = true;
            if(!$this->isSubmitted())
                $token = CsrfSecurity::generateToken();
            else
                $token = $this->request->post('token');

            $field = new Hidden(["name"=>"token","value"=>$token]);
            return $field->getWidget();
        }
        return '';
    }
    /**
     * Boucle les champs vérifie leurs validators et retourne tous les fields avec des erreurs
     *
     * @return bool
     */
    public function isValid()
    {

        $valid = true;
        if(!CsrfSecurity::isValid($this->request->post('token'))){
            echo 'Csrf error';
            return false;
        }
        /**
         * @var Field $field
         */
        // on boucle les champ et vérifie leur validité
        foreach ($this->formBuilder->getFields() as $field){
             if(!$field->isValid()){
                 $valid = false;
                 $this->fieldsError[]=$field;
             }
        }




        return $valid;
    }

    /**
     * Verifie que le formulaire a bien ete soumis
     * @return bool
     */
    public function isSubmitted()
    {
        return $this->request->method() == 'POST';

    }


}