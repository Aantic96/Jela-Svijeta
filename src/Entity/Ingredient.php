<?php

namespace App\Entity;

use App\Repository\IngredientsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: IngredientsRepository::class)]
class Ingredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('ingredient')]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups('ingredient')]
    private ?string $title = null;

    #[ORM\Column(length: 100)]
    #[Groups('ingredient')]
    private ?string $slug = null;

    #[ORM\Column]
    #[Groups('ingredient')]
    private ?\DateTimeImmutable $createdAt;

    #[ORM\ManyToMany(targetEntity: Food::class, mappedBy: 'ingredients', cascade: ['all'], orphanRemoval: true)]
    private Collection $food;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->food = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, Food>
     */
    public function getFood(): Collection
    {
        return $this->food;
    }

    public function addFood(Food $food): self
    {
        if (!$this->food->contains($food)) {
            $this->food->add($food);
            $food->addIngredient($this);
        }

        return $this;
    }

    public function removeFood(Food $food): self
    {
        if ($this->food->removeElement($food)) {
            $food->removeIngredient($this);
        }

        return $this;
    }
}
