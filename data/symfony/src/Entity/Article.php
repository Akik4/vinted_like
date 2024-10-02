<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column(nullable: true)]           
    private ?int $fav = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $content = null;

    /**
     * @var Collection<int, Favoris>
     */
    #[ORM\OneToMany(targetEntity: Favoris::class, mappedBy: 'article', orphanRemoval: true)]
    private Collection $favoris;

    #[ORM\Column(options: ["default" => 0])]
    private ?bool $is_buy = false   ;

    public function __construct()
    {
        $this->favoris = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getFav(): ?int
    {
        return $this->fav;
    }

    public function setFav(int $fav): static
    {
        $this->fav = $fav;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

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
            $favori->setArticle($this);
        }

        return $this;
    }

    public function removeFavori(Favoris $favori): static
    {
        if ($this->favoris->removeElement($favori)) {
            // set the owning side to null (unless already changed)
            if ($favori->getArticle() === $this) {
                $favori->setArticle(null);
            }
        }

        return $this;
    }

    public function isBuy(): ?bool
    {
        return $this->is_buy;
    }

    public function setBuy(bool $is_buy): static
    {
        $this->is_buy = $is_buy;

        return $this;
    }
}
