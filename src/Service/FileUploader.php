<?php

namespace App\Service;

use App\Domain\Model\Photo;

class FileUploader
{
    /**
     * @var
     */
    private $photosDir;

    /**
     * FileUploader constructor.
     * @param $photosDir
     */
    public function __construct($photosDir)
    {
        $this->photosDir = $photosDir;
    }

    /**
     * @param Photo $photo
     */
    public function preUpload(Photo $photo)
    {
        // Si jamais il n'y a pas de fichier (champ facultatif), on ne fait rien
        if (null === $photo->getFile()) {
            return;
        }
        $file=$photo->getFile();
        $photo->setType($file->guessExtension());
        $photo->setAlt($file->getClientOriginalName());
        $photo->setFileName(md5(uniqid()));
    }

    /**
     * @param Photo $photo
     */
    public function upload(Photo $photo)
    {
        // Si jamais il n'y a pas de fichier (champ facultatif), on ne fait rien
        if (null === $photo->getFile()) {
            return;
        }
        // Si on avait un ancien fichier (attribut tempFilename non null), on le supprime
        if (null !== $photo->getTempFilename()) {
            $oldFile = $this->photosDir.'/'.$photo->getFileName().'.'.$photo->getTempFilename();
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }

        // On déplace le fichier envoyé dans le répertoire de notre choix
        $photo->getFile()->move(
            $this->photosDir,
            $photo->getFileName().'.'.$photo->getType()
        );
    }

    /**
     * @param Photo $photo
     */
    public function preRemoveUpload(Photo $photo)
    {
        $photo->setTempFilename($this->photosDir.'/'.$photo->getFileName().'.'.$photo->getType());
    }

    /**
     * @param Photo $photo
     */
    public function removeUpload(Photo $photo)
    {
        if (file_exists($photo->getTempFilename())) {
            unlink($photo->getTempFilename());
        }
    }
}
