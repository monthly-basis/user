<?php
namespace MonthlyBasis\User\Model\Entity;

use DateTime;
use MonthlyBasis\User\Model\Entity as UserEntity;

class User
{
    public $emailAddresses = [];
    public $username;
    public $firstName;
    public $lastName;

    protected $created;
    protected $deletedDateTime;
    protected $deletedReason;
    protected $deletedUserId;
    protected $displayName;
    protected int $emoji12Id;
    protected $gender;
    protected $groups = [];
    protected string $httpsToken;
    protected string $loginToken;
    public string $openAiRole;
    protected $userId;
    protected $views = 0;
    protected $welcomeMessage;

    public function __get(string $name): mixed
    {
        return $this->$name;
    }

    public function __isset(string $name): bool
    {
        return isset($this->$name);
    }

    public function __set(string $name, mixed $value): void
    {
        $this->$name = $value;
    }

    public function getCreated() : DateTime
    {
        return $this->created;
    }

    public function getDeletedDateTime(): DateTime
    {
        return $this->deletedDateTime;
    }

    public function getDeletedReason(): string
    {
        return $this->deletedReason;
    }

    public function getDeletedUserId(): int
    {
        return $this->deletedUserId;
    }

    public function getDisplayName() : string
    {
        return $this->displayName;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function getGroups(): array
    {
        return $this->groups;
    }

    public function getHttpsToken(): string
    {
        return $this->httpsToken;
    }

    public function getLoginToken(): string
    {
        return $this->loginToken;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

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

    public function setCreated(DateTime $created) : UserEntity\User
    {
        $this->created = $created;
        return $this;
    }

    public function setDeletedDateTime(DateTime $deletedDateTime): UserEntity\User
    {
        $this->deletedDateTime = $deletedDateTime;
        return $this;
    }

    public function setDeletedReason(string $deletedReason): UserEntity\User
    {
        $this->deletedReason = $deletedReason;
        return $this;
    }

    public function setDeletedUserId(int $deletedUserId): UserEntity\User
    {
        $this->deletedUserId = $deletedUserId;
        return $this;
    }

    public function setDisplayName(string $displayName) : UserEntity\User
    {
        $this->displayName = $displayName;
        return $this;
    }

    public function setGender(string $gender): UserEntity\User
    {
        $this->gender = $gender;
        return $this;
    }

    public function setGroups(array $groups): UserEntity\User
    {
        $this->groups = $groups;
        return $this;
    }

    public function setHttpsToken(string $httpsToken): UserEntity\User
    {
        $this->httpsToken = $httpsToken;
        return $this;
    }

    public function setLoginToken(string $loginToken): UserEntity\User
    {
        $this->loginToken = $loginToken;
        return $this;
    }

    public function setPasswordHash(string $passwordHash): UserEntity\User
    {
        $this->passwordHash = $passwordHash;
        return $this;
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
