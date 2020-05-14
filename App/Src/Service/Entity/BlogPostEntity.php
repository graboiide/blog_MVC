<?php


namespace App\Src\Service\Entity;


class BlogPostEntity extends Entity
{
    private $contain;
    private $chapo;
    private $image;
    private $title;
    private $slug;
    private $date;
    private $dateMaj;
    private $isPublished;
    private $userId;
    private $comments = [];
    private $userBlogPost = [];
    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->useId;
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
     * @return mixed
     */
    public function getDateMaj()
    {
        return $this->dat_maj;
    }

    /**
     * @param mixed $dat_maj
     */
    public function setDateMaj($dat_maj): void
    {
        $this->dat_maj = $dat_maj;
    }

    /**
     * @return mixed
     */
    public function getIsPublished()
    {
        return $this->is_published;
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

    /**
     * @return mixed
     */
    public function getUserblogpost()
    {
        return $this->userblogpost;
    }

    /**
     * @param mixed $userblogpost
     */
    public function setUserblogpost($userblogpost): void
    {
        $this->userblogpost = $userblogpost;
    }
   




}