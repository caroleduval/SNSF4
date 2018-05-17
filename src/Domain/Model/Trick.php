<?php

namespace App\Domain\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;


/**
 * Trick
 *
 * @ORM\Table(name="trick")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Trick
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=150, unique=true)
     *
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(name="slug", type="string", length=150, unique=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     *
     * @Assert\NotBlank(message="Veuillez décrire (même brièvement) le trick SVP.")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\Model\Category", inversedBy="tricks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Domain\Model\Photo", mappedBy="trick", cascade={"persist","remove"})
     *
     * @Assert\Valid()
     */
    private $photos;

    /**
     * @ORM\OneToMany(targetEntity="App\Domain\Model\Video", mappedBy="trick", cascade={"persist","remove"})
     *
     * @Assert\Valid()
     */
    private $videos;

    /**
     * @ORM\OneToMany(targetEntity="App\Domain\Model\Comment", mappedBy="trick", cascade={"persist","remove"})
     */
    private $comments;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     * @Assert\DateTime()
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_update", type="datetime")
     * @Assert\DateTime()
     */
    private $lastUpdate;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->photos   = new ArrayCollection();
        $this->videos = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    /**
     * @return UuidInterface
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Trick
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Trick
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Trick
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set category
     *
     * @param string category
     *
     * @return Trick
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set photos
     *
     * @param array $photos
     *
     * @return Trick
     */
    public function setPhotos($photos)
    {
        $this->photos = $photos;

        return $this;
    }
    /**
     * Add photo
     *
     * @param Photo $photo
     *
     * @return Trick
     */
    public function addPhoto(Photo $photo)
    {
        $this->photos[] = $photo;
        $photo->setTrick($this);

        return $this;
    }
    /**
     * Get photos
     *
     * @return ArrayCollection
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * Set videos
     *
     * @param array $videos
     *
     * @return Trick
     */
    public function setVideos($videos)
    {
        $this->videos = $videos;

        return $this;
    }

    /**
     * Get videos
     *
     * @return ArrayCollection
     */
    public function getVideos()
    {
        return $this->videos;
    }

    /**
     * Set comments
     *
     * @param array $comments
     *
     * @return Trick
     */
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * Get comments
     *
     * @return arrayCollection
     */
    public function getComments()
    {
        return $this->comments;
    }


    /**
     * Remove photo
     *
     * @param Photo $photo
     */
    public function removePhoto(Photo $photo)
    {
        $this->photos->removeElement($photo);
    }

    /**
     * Add video
     *
     * @param Video $video
     *
     * @return Trick
     */
    public function addVideo(Video $video)
    {
        $this->videos[] = $video;
        $video->setTrick($this);

        return $this;
    }

    /**
     * Remove video
     *
     * @param Video $video
     */
    public function removeVideo(Video $video)
    {
        $this->videos->removeElement($video);
    }

    /**
     * Add comment
     *
     * @param Comment $comment
     *
     * @return Trick
     */
    public function addComment(Comment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param Comment $comment
     */
    public function removeComment(Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    public function setLastUpdate($lastUpdate)
    {
        $this->lastUpdate = $lastUpdate;

        return $this;
    }

    public function getLastUpdate()
    {
        return $this->lastUpdate;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getThumbnail()
    {
        $thumbnail=$this->photos->first();
        return $thumbnail;
    }
    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateDate()
    {
        $this->setLastUpdate(new \Datetime());
    }

    /**
     * @return int
     */
    public function countItems()
    {
        return count($this->getPhotos())+ count ($this->getVideos());
    }
}
