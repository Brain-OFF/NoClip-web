<?php

namespace App\Entity;

use App\Repository\PromosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PromosRepository::class)
 */
class Promos
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="date")
     */
    private $datefin;

    /**
     * @ORM\OneToMany(targetEntity=Games::class, mappedBy="promos")
     */
    private $games;

    public function __construct()
    {
        $this->games = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->datefin;
    }

    public function setDatefin(\DateTimeInterface $datefin): self
    {
        $this->datefin = $datefin;

        return $this;
    }
    public function __toString()
    {
        return(string)$this->getName();
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
            $game->setPromos($this);
        }

        return $this;
    }

    public function removeGame(Games $game): self
    {
        if ($this->games->removeElement($game)) {
            // set the owning side to null (unless already changed)
            if ($game->getPromos() === $this) {
                $game->setPromos(null);
            }
        }

        return $this;
    }

}
