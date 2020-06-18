<?php

namespace App\Entity\Account;

use App\Repository\Account\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(
 *     fields="email",
 *     message="Cet e-mail est déjà utilisé"
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\Email(
     *     message = "Cet email '{{ value }}' n'est pas valide."
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="array")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $subscribeTo = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $followedBy = [];

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getSalt(){

    }

    public function getUsername(){
        return $this->email;
    }

    public function eraseCredentials(){

    }

    public function getSubscribeTo(): ?array
    {
        return $this->subscribeTo;
    }

    public function setSubscribeTo(array $subscribeTo): self
    {
        $this->subscribeTo = $subscribeTo;

        return $this;
    }

    public function getFollowedBy(): ?array
    {
        return $this->followedBy;
    }

    public function setFollowedBy(?array $followedBy): self
    {
        $this->followedBy = $followedBy;

        return $this;
    }

    public function addSubscribeTo($id)
    {
        $follows = $this->subscribeTo;
        $follows[$id] = ['subscribeAt' => new \Datetime()];

        $this->setSubscribeTo($follows);

        return $this;
    }

    public function removeSubscribeTo($id)
    {
        $follows = $this->subscribeTo;
        unset($follows[$id]);

        $this->setSubscribeTo($follows);

        return $this;
    }
}
