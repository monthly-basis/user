<?php
namespace LeoGalleguillos\User\Model\Entity;

use DateTime;
use LeoGalleguillos\User\Model\Entity as UserEntity;

class Post
{
    protected $postId;
    protected $fromUser;
    protected $message;
    protected $toUser;
    protected $dateTime;

    public function getCreated() : DateTime
    {
        return $this->created;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getFromUser()
    {
        return $this->fromUser;
    }

    public function setCreated(DateTime $created) : UserEntity\Post
    {
        $this->created = $created;
        return $this;
    }

    public function setMessage(string $message) : UserEntity\Post
    {
        $this->message = $message;
        return $this;
    }

    public function setPostId(int $postId) : UserEntity\Post
    {
        $this->postId = $postId;
        return $this;
    }

    public function setFromUser(UserEntity\User $fromUser) : UserEntity\Post
    {
        $this->fromUser = $fromUser;
        return $this;
    }

    public function setToUser(UserEntity\User $toUser) : UserEntity\Post
    {
        $this->toUser = $toUser;
        return $this;
    }
}
