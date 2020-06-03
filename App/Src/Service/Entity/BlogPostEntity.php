<?php

namespace App\Src\Service\Entity;

class BlogPostEntity extends Entity
{
    /*
     * Declare at protected the property save in database
     */
    protected $contain;
    protected $chapo;
    protected $image;
    protected $title;
    protected $slug;
    protected $date;
    protected $dateMaj;
    protected $isPublished;
    protected $userId;
    /*
    * property not saving
    */
    private $comments = [];
    private $nbComment;
    private $userBlogPost = [];
    private $author;

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author): void
    {
        $this->author = $author;
    }

    /**
     * @return mixed
     */
    public function getNbComment()
    {
        return $this->nbComment;
    }

    /**
     * @param mixed $nbComment
     */
    public function setNbComment($nbComment): void
    {
        $this->nbComment = $nbComment;
    }

    public function __construct($data = [])
    {
        parent::__construct($data);
    }


    /**
     * @return mixed
     */
    public function getDateMaj()
    {
        return $this->dateMaj;
    }

    /**
     * @param mixed $dateMaj
     */
    public function setDateMaj($dateMaj): void
    {
        $this->dateMaj = $dateMaj;
    }

    /**
     * @return array
     */
    public function getUserBlogPost(): array
    {
        return $this->userBlogPost;
    }

    /**
     * @param array $userBlogPost
     */
    public function setUserBlogPost(array $userBlogPost): void
    {
        $this->userBlogPost = $userBlogPost;
    }






    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id): void
    {
        $this->userId = $user_id;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return mixed
     */
    public function getContain()
    {
        return $this->contain;
    }

    /**
     * @param mixed $contain
     */
    public function setContain($contain): void
    {
        $this->contain = $contain;
    }

    /**
     * @return mixed
     */
    public function getChapo()
    {
        return $this->chapo;
    }

    /**
     * @param mixed $chapo
     */
    public function setChapo($chapo): void
    {
        $this->chapo = $chapo;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image): void
    {
        $this->image = $image;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug): void
    {
        $this->slug = $slug;
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
     * @param mixed $is_published
     */
    public function setIsPublished($is_published): void
    {
        $this->isPublished = $is_published;
    }

    /**
     * @return mixed
     */
    public function getIsPublished()
    {
        return $this->isPublished;
    }

    /**
     * @return mixed
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param mixed $comments
     */
    public function setComments($comments): void
    {
        $this->comments = $comments;
    }


   




}