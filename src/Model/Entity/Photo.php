<?php
namespace LeoGalleguillos\User\Model\Entity;

use DateTime;
use LeoGalleguillos\Image\Model\Entity as ImageEntity;
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
    protected $userId;

    public function getCreated() : DateTime
    {
        return $this->created;
    }

    public function getDescription() : string
    {
        return $this->description;
    }

    public function getOriginal() : ImageEntity\Image
    {
        return $this->original;
    }

    public function getPhotoId() : int
    {
        return $this->photoId;
    }

    public function getTitle() : string
    {
        return $this->title;
    }

    public function getUserId() : int
    {
        return $this->userId;
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

    public function setDescription(string $description) : UserEntity\Photo
    {
        $this->description = $description;
        return $this;
    }

    public function setOriginal(ImageEntity\Image $original) : UserEntity\Photo
    {
        $this->original = $original;
        return $this;
    }

    public function setPhotoId(int $photoId) : UserEntity\Photo
    {
        $this->photoId = $photoId;
        return $this;
    }

    public function setTitle(string $title) : UserEntity\Photo
    {
        $this->title = $title;
        return $this;
    }

    public function setUserId(int $userId) : UserEntity\Photo
    {
        $this->userId = $userId;
        return $this;
    }

    public function setViews(int $views) : UserEntity\Photo
    {
        $this->views = $views;
        return $this;
    }
}
