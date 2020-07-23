<?php

namespace App\Entity\Account;

use App\Entity\Article\Article;
use App\Entity\Article\Comment;
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

    /**
     * @ORM\Column(type="string", length=20, unique=true)
     * @Assert\Length(
     *      max = 20,
     *      maxMessage = "Votre pseudo ne peut pas dépasser les 20 caractères"
     * )
     * @Assert\Regex(
     *      pattern     = "/^[a-zA-Z0-9]+$/i",
     *      htmlPattern = "^[a-zA-Z0-9]+$",
     *      message = "Les caractères spéciaux ne sont pas autorisé"
     * )
     *
     *
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @ORM\OneToMany(targetEntity=Notification::class, mappedBy="sender")
     */
    private $notifications;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="createdBy")
     */
    private $articles;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="createdBy")
     */
    private $comments;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->notifications = new ArrayCollection();
        $this->articles = new ArrayCollection();
        $this->comments = new ArrayCollection();
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

    public function addSubscribeTo($id)
    {
        $date = new \DateTime();
        $newDate =  $date->format('Y-m-d H:i:s');

        $sub = $this->subscribeTo;
        $sub[$id] = ['subscribeAt' => $newDate];

        $this->setSubscribeTo($sub);

        return $this;
    }

    public function removeSubscribeTo($id)
    {
        $subs = $this->subscribeTo;
        unset($subs[$id]);

        $this->setSubscribeTo($subs);

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

    public function addFollowedBy($id)
    {
        $date = new \DateTime();
        $newDate =  $date->format('Y-m-d H:i:s');

        $follows = $this->followedBy;
        $follows[$id] = ['followedAt' => $newDate];

        $this->setFollowedBy($follows);

        return $this;
    }

    public function removeFollowedBy($id)
    {
        $follows = $this->followedBy;
        unset($follows[$id]);

        $this->setFollowedBy($follows);

        return $this;
    }

    public function getUsername(){
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return Collection|Notification[]
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications[] = $notification;
            $notification->setSender($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->contains($notification)) {
            $this->notifications->removeElement($notification);
            // set the owning side to null (unless already changed)
            if ($notification->getSender() === $this) {
                $notification->setSender(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setCreatedBy($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getCreatedBy() === $this) {
                $article->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setCreatedBy($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getCreatedBy() === $this) {
                $comment->setCreatedBy(null);
            }
        }

        return $this;
    }
}
