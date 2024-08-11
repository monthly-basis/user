<?php
namespace LeoGalleguillos\User\Model\Service\Photo;

use LeoGalleguillos\User\Model\Entity as UserEntity;
use LeoGalleguillos\User\Model\Table as UserTable;

class Upload
{
    /**
     * Construct.
     *
     * @param UserTable\Photo $photoTable
     */
    public function __construct(
        UserTable\Photo $photoTable
    ) {
        $this->photoTable = $photoTable;
    }

    /**
     * Upload
     *
     * @param UserEntity\User $userEntity
     * @param string $fileName
     * @param string $fileTmpName
     * @param string $title
     * @param string $description
     */
    public function upload(
        UserEntity\User $userEntity,
        string $fileName,
        string $fileTmpName,
        string $title,
        string $description
    ) {
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $fileExtension = preg_replace('/\W/', '', $fileExtension);

        $photoId = $this->photoTable->insert(
            $userEntity->getUserId(),
            $fileExtension,
            $title,
            $description
        );

        mkdir($_SERVER['DOCUMENT_ROOT'] . "/uploads/photos/$photoId");

        $uploadPath = $_SERVER['DOCUMENT_ROOT']
                    . "/uploads/photos/$photoId/original.$fileExtension";
        move_uploaded_file($fileTmpName, $uploadPath);
        chmod($uploadPath, 0777);
    }
}
