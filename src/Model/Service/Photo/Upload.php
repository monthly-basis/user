<?php
namespace LeoGalleguillos\User\Model\Service\Photo;

use ArrayObject;
use LeoGalleguillos\User\Model\Entity as UserEntity;
use LeoGalleguillos\User\Model\Factory as UserFactory;
use LeoGalleguillos\User\Model\Service as UserService;
use LeoGalleguillos\User\Model\Table as UserTable;

class Upload
{
    public function __construct(
        UserTable\Photo $photoTable
    ) {
        $this->photoTable = $photoTable;
    }

    /**
     * Upload
     */
    public function upload(
        UserEntity\User $userEntity,
        string $fileName,
        string $fileTmpName
    ) {
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $fileExtension = preg_replace('/\W/', '', $fileExtension);

        $photoId = $this->photoTable->insert(
            $userEntity->getUserId(),
            $fileExtension
        );

        mkdir($_SERVER['DOCUMENT_ROOT'] . "/uploads/photos/$photoId");

        $uploadPath = $_SERVER['DOCUMENT_ROOT']
                    . "/uploads/photos/$photoId/original.$fileExtension";
        move_uploaded_file($fileTmpName, $uploadPath);
    }
}
