<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $username = "";

    #[ORM\Column]
    private bool $isVerified = false;

    /**
     * @var Collection<int, Article>
     */
    #[ORM\OneToMany(targetEntity: Article::class, mappedBy: 'seller', orphanRemoval: true)]
    private Collection $articles;

    /**
     * @var Collection<int, Article>
     */
    #[ORM\OneToMany(targetEntity: Article::class, mappedBy: 'buyer')]
    private Collection $articles_buyed;

    /**
     * @var Collection<int, Favoris>
     */
    #[ORM\OneToMany(targetEntity: Favoris::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $favoris;

    /**
     * @var Collection<int, Message>
     */
    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'User', orphanRemoval: true)]
    private Collection $user;

    /**
     * @var Collection<int, Message>
     */
    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'user2', orphanRemoval: true)]
    private Collection $messageReceived;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->articles_buyed = new ArrayCollection();
        $this->favoris = new ArrayCollection();
        $this->user = new ArrayCollection();
        $this->messageReceived = new ArrayCollection();
    }

    // #[ORM\Column]
    // private ?bool $is_verified = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): static
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
            $article->setSeller($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): static
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getSeller() === $this) {
                $article->setSeller(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticlesBuyed(): Collection
    {
        return $this->articles_buyed;
    }

    public function addArticlesBuyed(Article $articlesBuyed): static
    {
        if (!$this->articles_buyed->contains($articlesBuyed)) {
            $this->articles_buyed->add($articlesBuyed);
            $articlesBuyed->setBuyer($this);
        }

        return $this;
    }

    public function removeArticlesBuyed(Article $articlesBuyed): static
    {
        if ($this->articles_buyed->removeElement($articlesBuyed)) {
            // set the owning side to null (unless already changed)
            if ($articlesBuyed->getBuyer() === $this) {
                $articlesBuyed->setBuyer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Favoris>
     */
    public function getFavoris(): Collection
    {
        return $this->favoris;
    }

    public function addFavori(Favoris $favori): static
    {
        if (!$this->favoris->contains($favori)) {
            $this->favoris->add($favori);
            $favori->setUser($this);
        }

        return $this;
    }

    public function removeFavori(Favoris $favori): static
    {
        if ($this->favoris->removeElement($favori)) {
            // set the owning side to null (unless already changed)
            if ($favori->getUser() === $this) {
                $favori->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getUserID(): Collection
    {
        return $this->userID;
    }

    public function addUserID(Message $userID): static
    {
        if (!$this->userID->contains($userID)) {
            $this->userID->add($userID);
            $userID->setUserID($this);
        }

        return $this;
    }

    public function removeUserID(Message $userID): static
    {
        if ($this->userID->removeElement($userID)) {
            // set the owning side to null (unless already changed)
            if ($userID->getUserID() === $this) {
                $userID->setUserID(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessageReceived(): Collection
    {
        return $this->messageReceived;
    }

    public function addMessageReceived(Message $messageReceived): static
    {
        if (!$this->messageReceived->contains($messageReceived)) {
            $this->messageReceived->add($messageReceived);
            $messageReceived->setUser2($this);
        }

        return $this;
    }

    public function removeMessageReceived(Message $messageReceived): static
    {
        if ($this->messageReceived->removeElement($messageReceived)) {
            // set the owning side to null (unless already changed)
            if ($messageReceived->getUser2() === $this) {
                $messageReceived->setUser2(null);
            }
        }

        return $this;
    }
}
