<?php
namespace LeoGalleguillos\User\Model\Entity;

use LeoGalleguillos\User\Model\Entity as UserEntity;

class User
{
    public $emailAddresses = [];
    public $userId;
    public $username;
    public $firstName;
    public $lastName;

    protected $views = 0;

    protected $welcomeMessage = '';

    public function getUserId() : int
    {
        return $this->userId;
    }

    public function getUsername() : string
    {
        return $this->username;
    }

    public function getViews() : int
    {
        return $this->views;
    }

    public function getWelcomeMessage() : string
    {
        return $this->welcomeMessage;
    }

    public function setUserId(int $userId) : UserEntity\User
    {
        $this->userId = $userId;
        return $this;
    }

    public function setUsername(string $username) : UserEntity\User
    {
        $this->username = $username;
        return $this;
    }

    public function setViews(int $views) : UserEntity\User
    {
        $this->views = $views;
        return $this;
    }

    public function setWelcomeMessage(string $welcomeMessage) : UserEntity\User
    {
        $this->welcomeMessage = $welcomeMessage;
        return $this;
    }
}
