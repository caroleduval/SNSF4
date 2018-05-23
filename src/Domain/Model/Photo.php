<?php

namespace App\Domain\Model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Table(name="photo")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Photo
{
    /**
     * @var UuidInterface
     *
     * @ORM\Id
     *
     * @ORM\Column(name="id", type="uuid")
     */
    private $id;

    /**
     * @ORM\Column(name="file_name", type="string", length=255)
     */
    private $fileName;

    /**
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(name="alt", type="string", length=255)
     */
    private $alt;

    /**
     * @var UploadedFile
     *
     * @Assert\File(
     *     maxSize = "2M",
     *     mimeTypes = {"image/jpeg","image/jpg", "image/gif", "image/png"},
     *     mimeTypesMessage = "Format photo non valide",
     *     notFoundMessage = "Le fichier photo n'a pas été trouvé sur le disque",
     *     uploadErrorMessage = "Erreur dans l'upload du fichier photo"
     * )
     */
    private $file;

    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\Model\Trick", inversedBy="photos")
     */
    private $trick;

    // Stockage temporaire du nom du fichier
    private $tempFilename;

    /**
     * Photo constructor.
     */
    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }

    /**
     * @return UuidInterface
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $fileName
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $alt
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;
    }
    /**
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }
    /**
     * @param string $tempFilename
     */
    public function setTempFilename($tempFilename)
    {
        $this->tempFilename = $tempFilename;
    }
    /**
     * @return string
     */
    public function getTempFilename()
    {
        return $this->tempFilename;
    }
    /**
     * @param UploadedFile $file
     */
    // On modifie le setter de File, pour prendre en compte l'upload d'un fichier lorsqu'il en existe déjà un autre
    public function setFile(UploadedFile $file)
    {
        $this->file = $file;
        if (null !== $this->type) {
            $this->tempFilename = $this->type;
            $this->type = null;
            $this->alt = null;
        }
    }
    /**
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param Trick $trick
     * @return $this
     */
    public function setTrick(Trick $trick)
    {
        $this->trick = $trick;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getTrick()
    {
        return $this->trick;
    }

    public function getWebPath()
    {
        return 'upload/photos/'.$this->getFileName().'.'.$this->getType();
    }
}
