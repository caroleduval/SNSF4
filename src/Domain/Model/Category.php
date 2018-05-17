<?php


namespace App\Domain\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Category
 *
 * @ORM\Table(name="category")
 *
 * @ORM\Entity
 */
class Category
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     *
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Domain\Model\Trick", mappedBy="category")
     */
    private $tricks;

    public function __construct()
    {
        $this->tricks   = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return Category
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
     * Set tricks
     *
     * @param array $tricks
     *
     * @return Category
     */
    public function setTricks($tricks)
    {
        $this->tricks = $tricks;

        return $this;
    }

    /**
     * Get tricks
     *
     * @return arrayCollection
     */
    public function getTricks()
    {
        return $this->tricks;
    }

    /**
     * Add trick
     *
     * @param Trick $trick
     *
     * @return Category
     */
    public function addTrick(Trick $trick)
    {
        $this->tricks[] = $trick;

        return $this;
    }

    /**
     * Remove trick
     *
     * @param Trick $trick
     */
    public function removeTrick(Trick $trick)
    {
        $this->tricks->removeElement($trick);
    }
}
