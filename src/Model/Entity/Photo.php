<?php
namespace LeoGalleguillos\User\Model\Entity;

use DateTime;
use LeoGalleguillos\Photo\Model\Entity as PhotoEntity;
use LeoGalleguillos\User\Model\Entity as UserEntity;

class Photo
{
    protected $photoId;

    protected $original;

    protected $views;

    protected $created;

    protected $extension;
    protected $title;
    protected $description;

    public function getCreated() : DateTime
    {
        return $this->created;
    }

    public function getTitle() : string
    {
        return $this->title;
    }

    public function getViews() : int
    {
        return $this->views;
    }

    public function setCreated(DateTime $created) : UserEntity\Photo
    {
        $this->created = $created;
        return $this;
    }

    public function setTitle(string $title) : UserEntity\Photo
    {
        $this->title = $title;
        return $this;
    }

    public function setViews(int $views) : UserEntity\Photo
    {
        $this->views = $views;
        return $this;
    }
}
