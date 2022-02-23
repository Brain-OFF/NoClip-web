<?php

namespace App\Entity;

use App\Repository\GamesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=GamesRepository::class)
 */
class Games
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="name is required")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="desc is required")
     */
    private $descreption;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="prix is required")
     * @Assert\Type(
     *     type="double",
     *     message="the value is not ."
     * )
     */
    private $prix;



    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="prix is required")
     */
    private $img;

    /**
     * @ORM\ManyToMany(targetEntity=Gamescat::class, inversedBy="Games")
     */
    private $cat;

    public function __construct()
    {
        $this->cat = new ArrayCollection();
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

    public function getDescreption(): ?string
    {
        return $this->descreption;
    }

    public function setDescreption(string $descreption): self
    {
        $this->descreption = $descreption;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }


    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(string $img): self
    {
        $this->img = $img;

        return $this;
    }

    /**
     * @return Collection|gamescat[]
     */
    public function getCat(): Collection
    {
        return $this->cat;
    }

    public function addCat(gamescat $cat): self
    {
        if (!$this->cat->contains($cat)) {
            $this->cat[] = $cat;
        }

        return $this;
    }

    public function removeCat(gamescat $cat): self
    {
        $this->cat->removeElement($cat);

        return $this;
    }

    public function __toString()
    {
        return(string)$this->getName();
    }

}
