<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 */
class Reservation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("coach")
     * @Groups("reservation")
     */
    private $id;



    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="tempsstart is required")
     * @Assert\DateTime(message = "The date '{{ YYYY-MM-DD hh-mm-ss }}' is not a valid")
     * @Assert\GreaterThan("+2 hours ")
     * @Assert\Expression("this.getTempsstart() < this.getTempsend()",message="La date fin ne doit pas être antérieure à la date début")
     * @Groups("reservation")
     */
    private $tempsstart;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="tempsend is required")
     * @Assert\DateTime(message = "The date '{{ YYYY-MM-DD hh-mm-ss }}' is not a valid")
     * @Assert\GreaterThan("+3 hours")
     * @Assert\Expression("this.getTempsstart() < this.getTempsend()",message="La date fin ne doit pas être antérieure à la date début")
     * @Groups("reservation")
     */
    private $tempsend;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups("reservation")
     */
    private $dispo;

    /**
     * @ORM\ManyToOne(targetEntity=Coach::class, inversedBy="reservations")
     * @Assert\NotBlank(message="coach is required")
     * @Groups("reservation")
     */
    private $coach;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getTempsstart(): ?\DateTimeInterface
    {
        return $this->tempsstart;
    }

    public function setTempsstart(\DateTimeInterface $tempsstart): self
    {
        $this->tempsstart = $tempsstart;

        return $this;
    }

    public function getTempsend(): ?\DateTimeInterface
    {
        return $this->tempsend;
    }

    public function setTempsend(\DateTimeInterface $tempsend): self
    {
        $this->tempsend = $tempsend;

        return $this;
    }

    public function getDispo(): ?bool
    {
        return $this->dispo;
    }

    public function setDispo(?bool $dispo): self
    {
        $this->dispo = $dispo;

        return $this;
    }

    public function getCoach(): ?Coach
    {
        return $this->coach;
    }

    public function setCoach(?Coach $coach): self
    {
        $this->coach = $coach;

        return $this;
    }
    public function __toString()
    {
        return(string)$this->getCoach();
    }
    public function __toStringdispo()
    {
        return(string)$this->getDispo();
    }


}
