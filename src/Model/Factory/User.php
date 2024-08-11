<?php
namespace MonthlyBasis\User\Model\Factory;

use DateTime;
use MonthlyBasis\User\Model\Entity as UserEntity;
use MonthlyBasis\User\Model\Exception as UserException;
use MonthlyBasis\User\Model\Table\User as UserTable;

class User
{
    protected $cache = [];

    public function __construct(UserTable $userTable)
    {
        $this->userTable = $userTable;
    }

    public function buildFromArray(array $array): UserEntity\User
    {
        $userEntity = new UserEntity\User();

        $userEntity->setUserId($array['user_id']);

        if (isset($array['created'])) {
            $userEntity->setCreated(
                new DateTime($array['created'])
            );
        }
        if (isset($array['deleted_datetime'])) {
            $userEntity->setDeletedDateTime(new DateTime($array['deleted_datetime']));
        }
        if (isset($array['deleted_reason'])) {
            $userEntity->setDeletedReason($array['deleted_reason']);
        }
        if (isset($array['deleted_user_id'])) {
            $userEntity->setDeletedUserId($array['deleted_user_id']);
        }
        if (isset($array['display_name'])) {
            $userEntity->setDisplayName($array['display_name']);
        }
        if (isset($array['emoji_12_id'])) {
            $userEntity->emoji12Id = $array['emoji_12_id'];
        }
        if (isset($array['gender'])) {
            $userEntity->setGender($array['gender']);
        }
        if (isset($array['https_token'])) {
            $userEntity->setHttpsToken($array['https_token']);
        }
        if (isset($array['login_token'])) {
            $userEntity->setLoginToken($array['login_token']);
        }
        if (isset($array['open_ai_role'])) {
            $userEntity->openAiRole = $array['open_ai_role'];
        }
        if (isset($array['password_hash'])) {
            $userEntity->setPasswordHash($array['password_hash']);
        }
        if (isset($array['username'])) {
            $userEntity->setUsername($array['username']);
        }
        if (isset($array['welcome_message'])) {
            $userEntity->setWelcomeMessage($array['welcome_message']);
        }

        $userEntity->setViews(
            (int) ($array['views'] ?? 0)
        );

        return $userEntity;
    }

    public function buildFromUserId(int $userId): UserEntity\User
    {
        if (isset($this->cache[$userId])) {
            return $this->cache[$userId];
        }

        $result = $this->userTable->selectWhereUserId($userId);
        if (false == ($array = $result->current())) {
            throw new UserException('Invalid User ID.');
        }
        $userEntity = $this->buildFromArray($array);

        $this->cache[$userId] = $userEntity;

        return $userEntity;
    }

    /**
     * @throws UserException
     */
    public function buildFromUsername(string $username): UserEntity\User
    {
        $result = $this->userTable->selectWhereUsername($username);
        if (false == ($array = $result->current())) {
            throw new UserException('Invalid username');
        }

        return $this->buildFromArray(
            $array
        );
    }
}
