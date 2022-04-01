<?php

namespace App\Entity;

use App\Repository\GamesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * @ORM\Entity(repositoryClass=GamesRepository::class)
 */
class Games
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("post:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="name is required")
     * @Groups("post:read")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="desc is required")
     * @Groups("post:read")
     */
    private $descreption;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="prix is required")
     * @Assert\Type(
     *     type="double",
     *     message="the value is not ."
     * )
     * @Groups("post:read")
     */
    private $prix;



    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="prix is required")
     * @Groups("post:read")
     */
    private $img;

    /**
     * @ORM\ManyToMany(targetEntity=Gamescat::class, inversedBy="Games")
     */
    private $cat;

    /**
     * @ORM\ManyToOne(targetEntity=Promos::class, inversedBy="games")
     */
    private $promos;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="games")
     */
    private $favoris;

    /**
     * @ORM\OneToMany(targetEntity=Rating::class, mappedBy="idgame")
     */
    private $ratings;


    public function __construct()
    {
        $this->cat = new ArrayCollection();
        $this->favoris = new ArrayCollection();
        $this->ratings = new ArrayCollection();
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

    public function getPromos(): ?Promos
    {
        return $this->promos;
    }

    public function setPromos(?Promos $promos): self
    {
        $this->promos = $promos;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getFavoris(): Collection
    {
        return $this->favoris;
    }

    public function addFavori(User $favori): self
    {
        if (!$this->favoris->contains($favori)) {
            $this->favoris[] = $favori;
        }

        return $this;
    }
    public function verify_fav(User $favori)
    {
        if (!$this->favoris->contains($favori)) {
            $this->favoris[] = $favori;
            return false;
        }
        return true;

    }
    public function addFavorielse(User $favori): self
    {
        if (!$this->favoris->contains($favori)) {
            $this->favoris[] = $favori;
        }
        else
            $this->removeFavori( $favori);


        return $this;
    }


    public function removeFavori(User $favori): self
    {
        $this->favoris->removeElement($favori);

        return $this;
    }

    /**
     * @return Collection<int, Rating>
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    public function addRating(Rating $rating): self
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings[] = $rating;
            $rating->setIdgame($this);
        }

        return $this;
    }

    public function removeRating(Rating $rating): self
    {
        if ($this->ratings->removeElement($rating)) {
            // set the owning side to null (unless already changed)
            if ($rating->getIdgame() === $this) {
                $rating->setIdgame(null);
            }
        }

        return $this;
    }



}
