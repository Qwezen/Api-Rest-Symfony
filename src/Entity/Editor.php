<?php

namespace App\Entity;

use App\Repository\EditorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EditorRepository::class)]
class Editor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(
        min: 3,
        max: 50,
        minMessage: 'Le nom ne doit pas être inférieur à {{ limit }} caractères',
        maxMessage: 'Le nom ne doit pas être dépassé {{ limit }} caractères',
    )]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(
        min: 2,
        max: 55,
        minMessage: "Le pays doit comporter au moins {{ limit }} caractères.",
        maxMessage: "Le pays ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $country = null;

    /**
     * @var Collection<int, VideoGame>
     */
    #[ORM\OneToMany(targetEntity: VideoGame::class, mappedBy: 'VideoGame_Editor')]
    private Collection $Editor_VideoGame;

    public function __construct()
    {
        $this->Editor_VideoGame = new ArrayCollection();
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

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): static
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection<int, VideoGame>
     */
    public function getEditorVideoGame(): Collection
    {
        return $this->Editor_VideoGame;
    }

    public function addEditorVideoGame(VideoGame $editorVideoGame): static
    {
        if (!$this->Editor_VideoGame->contains($editorVideoGame)) {
            $this->Editor_VideoGame->add($editorVideoGame);
            $editorVideoGame->setVideoGameEditor($this);
        }

        return $this;
    }

    public function removeEditorVideoGame(VideoGame $editorVideoGame): static
    {
        if ($this->Editor_VideoGame->removeElement($editorVideoGame)) {
            // set the owning side to null (unless already changed)
            if ($editorVideoGame->getVideoGameEditor() === $this) {
                $editorVideoGame->setVideoGameEditor(null);
            }
        }

        return $this;
    }
}
