<?php

namespace App\Entity;

use App\Repository\VideoGameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VideoGameRepository::class)]
class VideoGame
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $releaseDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, Category>
     */
    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'Category_VideoGame')]
    private Collection $VideoGame_Category;

    #[ORM\ManyToOne(inversedBy: 'Editor_VideoGame')]
    private ?Editor $VideoGame_Editor = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $coverImage = null;

    public function __construct()
    {
        $this->VideoGame_Category = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getReleaseDate(): ?\DateTime
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(?\DateTime $releaseDate): static
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getVideoGameCategory(): Collection
    {
        return $this->VideoGame_Category;
    }

    public function addVideoGameCategory(Category $videoGameCategory): static
    {
        if (!$this->VideoGame_Category->contains($videoGameCategory)) {
            $this->VideoGame_Category->add($videoGameCategory);
        }

        return $this;
    }

    public function removeVideoGameCategory(Category $videoGameCategory): static
    {
        $this->VideoGame_Category->removeElement($videoGameCategory);

        return $this;
    }

    public function getVideoGameEditor(): ?Editor
    {
        return $this->VideoGame_Editor;
    }

    public function setVideoGameEditor(?Editor $VideoGame_Editor): static
    {
        $this->VideoGame_Editor = $VideoGame_Editor;

        return $this;
    }

    public function getCoverImage(): ?string
    {
        return $this->coverImage;
    }

    public function setCoverImage(?string $coverImage): static
    {
        $this->coverImage = $coverImage;

        return $this;
    }
}
