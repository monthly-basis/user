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

        $imagick = new \Imagick($fileTmpName);
        $orientation = $imagick->getImageOrientation();
		switch ($orientation) {
			case \Imagick::ORIENTATION_BOTTOMRIGHT:
				$imagick->rotateimage("#000", 180); // rotate 180 degrees
			break;

			case \Imagick::ORIENTATION_RIGHTTOP:
				$imagick->rotateimage("#000", 90); // rotate 90 degrees CW
			break;

			case \Imagick::ORIENTATION_LEFTBOTTOM:
				$imagick->rotateimage("#000", -90); // rotate 90 degrees CCW
			break;
		}
        $imagick->setImageOrientation(\Imagick::ORIENTATION_TOPLEFT);

        mkdir($_SERVER['DOCUMENT_ROOT'] . "/uploads/photos/$photoId");

        $uploadPath = $_SERVER['DOCUMENT_ROOT']
                    . "/uploads/photos/$photoId/original.$fileExtension";

        $imagick->writeImage($uploadPath);
    }
}
