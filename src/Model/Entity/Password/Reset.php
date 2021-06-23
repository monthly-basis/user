<?php
namespace MonthlyBasis\User\Model\Entity\Password;

use DateTime;
use MonthlyBasis\User\Model\Entity as UserEntity;

class Reset
{
    protected DateTime $accessed;
    protected string $code;
    protected DateTime $created;
    protected int $resetId;
    protected DateTime $used;
    protected int $userId;

    public function getAccessed(): DateTime
    {
        return $this->accessed;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getCreated(): DateTime
    {
        return $this->created;
    }

    public function getResetId(): int
    {
        return $this->resetId;
    }

    public function getUsed(): DateTime
    {
        return $this->used;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setAccessed(DateTime $accessed): UserEntity\Password\Reset
    {
        $this->accessed = $accessed;
        return $this;
    }

    public function setCreated(DateTime $created): UserEntity\Password\Reset
    {
        $this->created = $created;
        return $this;
    }

    public function setUsed(DateTime $used): UserEntity\Password\Reset
    {
        $this->used = $used;
        return $this;
    }

    public function setCode(string $code): UserEntity\Password\Reset
    {
        $this->code = $code;
        return $this;
    }

    public function setResetId(int $resetId): UserEntity\Password\Reset
    {
        $this->resetId = $resetId;
        return $this;
    }

    public function setUserId(int $userId): UserEntity\Password\Reset
    {
        $this->userId = $userId;
        return $this;
    }
}
