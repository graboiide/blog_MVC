<?php


namespace App\Src\Form\Validator;



use App\Src\Form\Field\Field;
use App\Src\Service\Hydrator;

abstract class Validator
{
    protected $errorMessage;
    /**
     * @var Field $fieldChild
     */
    protected $fieldChild;
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
        return $this->errorMessage;
    }

    /**
     * @param mixed $fieldChild
     */
    public function setFieldChild($fieldChild): void
    {
        $this->fieldChild = $fieldChild;
    }
    abstract public function isValid();

}