<?php

namespace App\Entity\Account;

use App\Repository\Account\FollowRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FollowRepository::class)
 */
class Follow
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $userOne;

    /**
     * @ORM\Column(type="integer")
     */
    private $userTwo;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserOne()
    {
        return $this->userOne;
    }

    public function setUserOne($userOne): self
    {
        $this->userOne = $userOne;

        return $this;
    }

    public function getUserTwo()
    {
        return $this->userTwo;
    }

    public function setUserTwo($userTwo): self
    {
        $this->userTwo = $userTwo;

        return $this;
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
}
