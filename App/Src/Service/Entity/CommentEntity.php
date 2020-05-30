<?php

namespace App\Src\Service\Entity;

class CommentEntity extends Entity
{
    protected $date;
    protected $message;
    protected $isValidate;
    protected $postBlogId;
    protected $name;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }
    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message): void
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getIsValidate()
    {
        return $this->isValidate;
    }

    /**
     * @param mixed $is_validate
     */
    public function setIsValidate($is_validate): void
    {
        $this->isValidate = $is_validate;
    }

    /**
     * @return mixed
     */
    public function getPostBlogId()
    {
        return $this->postBlogId;
    }

    /**
     * @param mixed $post_blog_id
     */
    public function setPostBlogId($post_blog_id): void
    {
        $this->postBlogId = $post_blog_id;
    }


}