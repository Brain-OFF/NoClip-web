<?php

namespace App\Entity;

use App\Repository\TournoiRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\inscriptionT;
/**
 * @ORM\Entity(repositoryClass=TournoiRepository::class)
 */
class Tournoi
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Username is required")
     */
    private $nom;


    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="date is required")
     * @Assert\DateTime(message = "The date '{{ YYYY-MM-DD hh-mm-ss }}
     */

    private $Date;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="cathegorie is required")
     */
    private $cathegorie;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Discription is required")
     */
    private $Discription;

    /**
     * @ORM\OneToMany(targetEntity=inscriptionT::class, mappedBy="tournoi", orphanRemoval=true,cascade={"remove"})
     */
    private $inscriptions;



    public function __construct()
    {
        $this->Date = new \DateTime();
        $this->inscriptions = new ArrayCollection();
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): self
    {
        $this->Date = $Date;

        return $this;
    }

    public function getCathegorie(): ?string
    {
        return $this->cathegorie;
    }

    public function setCathegorie(string $cathegorie): self
    {
        $this->cathegorie = $cathegorie;

        return $this;
    }

    public function getDiscription(): ?string
    {
        return $this->Discription;
    }

    public function setDiscription(?string $Discription): self
    {
        $this->Discription = $Discription;

        return $this;
    }

    public function __toString()
    {
        return(string)$this->getNom();
    }

    /**
     * @return Collection|inscriptionT[]
     */
    public function getInscriptions(): Collection
    {
        return $this->inscriptions;
    }

    public function addInscription(inscriptionT $inscription): self
    {
        if (!$this->inscriptions->contains($inscription)) {
            $this->inscriptions[] = $inscription;
            $inscription->setTournoi($this);
        }

        return $this;
    }

    public function removeInscription(inscriptionT $inscription): self
    {
        if ($this->inscriptions->removeElement($inscription)) {
            // set the owning side to null (unless already changed)
            if ($inscription->getTournoi() === $this) {
                $inscription->setTournoi(null);
            }
        }

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }








}
