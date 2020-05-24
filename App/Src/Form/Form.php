<?php


namespace App\Src\Form;


use App\Src\Form\Field\Field;
use App\Src\Service\HTTP\HttpRequest;

class Form
{
    /**
     * @var FormBuilder $formBuilder
     */
    private $formBuilder;
    private $fieldsError;
    private $request;

    public function __construct(FormBuilder $formBuilder,HttpRequest $request)
    {
        $this->formBuilder = $formBuilder;
        $this->request = $request;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function widget($name)
    {

        $dataEntity = $this->formBuilder->getEntity()->extractAttributes(false,false);
        /**
         * @var Field $field
         */
        //récupere le field dans le builder
        if(!array_key_exists($name,$this->formBuilder->getFields()))
            return '';
        $field = $this->formBuilder->getField($name);
        //on auto complete le champs si il correspond a une propriété de l'entity
        /*if(array_key_exists($name,$dataEntity)){
            $field->setValue($dataEntity[$name]);
        }*/
        return $field->getWidget();
    }

    public function field($name)
    {
        $dataEntity = $this->formBuilder->getEntity()->extractAttributes(false,false);

        if(!array_key_exists($name,$this->formBuilder->getFields()))
            return '';
        $field = $this->formBuilder->getField($name);
        if(array_key_exists($name,$dataEntity)){
            $field->setValue($dataEntity[$name]);
        }
        return $field;
    }

    public function createView()
    {

        $form = '<form class="form-contact comment_form" action="#">';
        /**
         * @var Field $field
         */
        foreach ($this->formBuilder->getFields() as $field){
            $form.= $field->getWidget();
        }
        $form .= '</form>';

        return $form;
    }

    /**
     * Boucle les champs vérifie leurs validators et retourne tous les fields avec des erreurs
     *
     * @return bool
     */
    public function isValid()
    {
        $valid = true;
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

    public function isSubmitted()
    {
        return $this->request->method() == 'POST';

    }


}