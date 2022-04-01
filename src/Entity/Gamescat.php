<?php

namespace App\Entity;

use App\Repository\GamescatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=GamescatRepository::class)
 */
class Gamescat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("post:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="nom is required")
     * @Groups("post:read")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="desc is required")
     * @Groups("post:read")
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity=Games::class, mappedBy="cat")
     */
    private $games;

    public function __construct()
    {
        $this->games = new ArrayCollection();
    }

    public function __toString()
    {
        return(string)$this->getNom();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Games[]
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Games $game): self
    {
        if (!$this->games->contains($game)) {
            $this->games[] = $game;
            $game->addCat($this);
        }

        return $this;
    }

    public function removeGame(Games $game): self
    {
        if ($this->games->removeElement($game)) {
            $game->removeCat($this);
        }

        return $this;
    }

}
