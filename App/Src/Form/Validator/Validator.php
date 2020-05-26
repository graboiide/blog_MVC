<?php


namespace App\Src\Form\Validator;



use App\Src\Form\Field\Field;
use App\Src\Service\Hydrator;

abstract class Validator
{
    protected $listErrors;
    /**
     * @var Field $fieldChild
     */
    protected $fieldChild;
    protected $customErrors;

    public function __construct($data)
    {
        $this->hydrate($data);
    }
    use Hydrator;
    /**
     * @return mixed
     */
    public function getErrorMessage()
    {
        return $this->listErrors;
    }

    /**
     * @param mixed $fieldChild
     */
    public function setFieldChild($fieldChild): void
    {
        $this->fieldChild = $fieldChild;
    }

    /**
     * @param mixed $customErrors
     */
    public function setCustomErrors($customErrors): void
    {
        $this->customErrors = $customErrors;
    }
    abstract public function isValid();

}