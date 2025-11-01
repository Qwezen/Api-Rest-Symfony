<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(
        min: 3,
        max: 50,
        minMessage: "Le nom doit comporter au moins {{ limit }} caractères.",
        maxMessage: "Le nom ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $name = null;

    /**
     * @var Collection<int, VideoGame>
     */
    #[ORM\ManyToMany(targetEntity: VideoGame::class, mappedBy: 'VideoGame_Category')]
    private Collection $Category_VideoGame;

    public function __construct()
    {
        $this->Category_VideoGame = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, VideoGame>
     */
    public function getCategoryVideoGame(): Collection
    {
        return $this->Category_VideoGame;
    }

    public function addCategoryVideoGame(VideoGame $categoryVideoGame): static
    {
        if (!$this->Category_VideoGame->contains($categoryVideoGame)) {
            $this->Category_VideoGame->add($categoryVideoGame);
            $categoryVideoGame->addVideoGameCategory($this);
        }

        return $this;
    }

    public function removeCategoryVideoGame(VideoGame $categoryVideoGame): static
    {
        if ($this->Category_VideoGame->removeElement($categoryVideoGame)) {
            $categoryVideoGame->removeVideoGameCategory($this);
        }

        return $this;
    }
}
