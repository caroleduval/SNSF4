<?php

namespace App\Domain\Model;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Domain\Repository\UserManager")
 * @ORM\Table(name="SN_user")
 * @UniqueEntity(fields={"username"}, message="Cet username est déjà utilisé.")
 * @UniqueEntity(fields={"email"}, message="Cet email est déjà utilisé.")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @var UuidInterface
     *
     * @ORM\Id
     *
     * @ORM\Column(type="uuid")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=25, unique=true)
     *
     * @Assert\NotBlank(message="Vous devez saisir un nom d'utilisateur.")
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=64)
     *
     * @Assert\NotBlank()
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=60, unique=true)
     *
     * @Assert\NotBlank(message="Vous devez saisir une adresse email.")
     *
     * @Assert\Email(message="Le format de l'adresse n'est pas correcte.")
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     *
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255)
     *
     * @Assert\NotBlank()
     */
    private $firstName;

    /**
     * @ORM\OneToOne(targetEntity="App\Domain\Model\Photo", cascade={"persist", "remove"})
     * @Assert\Valid()
     */
    private $photo;

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     */
    private $roles =[];

    /**
     * @var string Reset token.
     *
     * @ORM\Column(name="reset_token", type="string", length=32, nullable=true)
     */
    protected $resetToken;

    /**
     * @var int Unix Epoch timestamp when the reset token expires.
     *
     * @ORM\Column(name="reset_token_expires_at", type="integer", nullable=true)
     */
    protected $resetTokenExpiresAt;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->roles[] = 'ROLE_USER';
    }

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return User
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
     * Set name
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setfirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set photo
     *
     * @param Photo $photo
     *
     * @return User
     */
    public function setPhoto(Photo $photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return Photo
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
        $this->setUsername($email);
        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Retourne les rôles de l'user
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        // Afin d'être sûr qu'un user a toujours au moins 1 rôle
        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * Non utilisée : mot de passe non stocké en clair
     */
    public function eraseCredentials()
    {
    }

    /**
     * @return null|string
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize(): string
    {
        return serialize([
            $this->id,
            $this->username,
            $this->password
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized): void
    {
        [
            $this->id,
            $this->username,
            $this->password
        ] = unserialize($serialized);
    }

    /**
     * Generates new reset token which expires in specified period of time.
     *
     * @param \DateInterval $interval
     *
     * @return string Generated token.
     */
    public function generateResetToken(\DateInterval $interval): string
    {
        $now = new \DateTime();
        $this->resetToken          = Uuid::uuid4()->getHex();
        $this->resetTokenExpiresAt = $now->add($interval)->getTimestamp();
        return $this->resetToken;
    }

    /**
     * Clears current reset token.
     *
     * @return self
     */
    public function clearResetToken(): self
    {
        $this->resetToken          = null;
        $this->resetTokenExpiresAt = null;
        return $this;
    }

    /**
     * Checks whether specified reset token is valid.
     *
     * @param string $token
     *
     * @return bool
     */
    public function isResetTokenValid(string $token): bool
    {
        return
            $this->resetToken === $token        &&
            $this->resetTokenExpiresAt !== null &&
            $this->resetTokenExpiresAt > time();
    }
}
