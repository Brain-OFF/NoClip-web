<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CategorieRepository::class)
 */
class Categorie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * * @Assert\NotBlank(message="Titre is required")
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=News::class, mappedBy="Categorie")
     * * @Assert\NotBlank(message="Titre is required")
     */
    private $idnews;

    public function __construct()
    {
        $this->idnews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, News>
     */
    public function getIdnews(): Collection
    {
        return $this->idnews;
    }

    public function addIdnews(News $idnews): self
    {
        if (!$this->idnews->contains($idnews)) {
            $this->idnews[] = $idnews;
            $idnews->setCategorie($this);
        }

        return $this;
    }

    public function removeIdnews(News $idnews): self
    {
        if ($this->idnews->removeElement($idnews)) {
            // set the owning side to null (unless already changed)
            if ($idnews->getCategorie() === $this) {
                $idnews->setCategorie(null);
            }
        }

        return $this;
    }
    public function __toString () {
        return $this->getNom();
    }
}
