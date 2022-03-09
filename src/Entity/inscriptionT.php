<?php

namespace App\Entity;

use App\Repository\InscriptionTRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Tournoi;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=InscriptionTRepository::class)
 */
class inscriptionT
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
     * @Assert\NotBlank( message="write name")
     */
    private $user_name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="write email")
     *  * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotBlank(message="You need to agree on all terms")
     */
    private $etat;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $Rank;

    /**
     * @ORM\ManyToOne(targetEntity=Tournoi::class, inversedBy="inscriptions")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="no tournament")
     */
    private $tournoi;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;


    public function getUserName(): ?string
    {
        return $this->user_name;
    }

    public function setUserName(string $user_name): self
    {
        $this->user_name = $user_name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(bool $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getRank(): ?string
    {
        return $this->Rank;
    }

    public function setRank(?string $Rank): self
    {
        $this->Rank = $Rank;

        return $this;
    }


    public function getid(): ?int
    {
        return $this->id;
    }

    public function getTournoi(): ?Tournoi
    {
        return $this->tournoi;
    }

    public function setTournoi(?Tournoi $tournoi): self
    {
        $this->tournoi = $tournoi;

        return $this;
    }
    public function __toString()
    {
        return(string)$this->getUserName();
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
