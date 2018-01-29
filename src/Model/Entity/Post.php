<?php
namespace LeoGalleguillos\User\Model\Entity;

use LeoGalleguillos\User\Model\Entity as UserEntity;

class Post
{
    protected $postId;
    protected $fromUser;
    protected $message;
    protected $toUser;

    public function getMessage()
    {
        return $this->message;
    }

    public function getFromUser()
    {
        return $this->fromUser;
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
