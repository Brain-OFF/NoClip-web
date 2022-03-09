<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints as Recaptcha;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 */
class User implements UserInterface
{


    /**
    * @Recaptcha\IsTrue
    */
    public $recaptcha;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ("post:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups ("post:read")
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     * @Assert\NotBlank(message="Email is required")
     * @Assert\Email(message = "The email '{{ value }}' is not a valid email.")
     * @Groups ("post:read")
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @Groups ("post:read")
     */

    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups ("post:read")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=50000, nullable=true)
     * @Groups ("post:read")
     */
    private $photo;

    /**
     * @ORM\Column(type="integer")
     * @Groups ("post:read")
     */
    private $points;

    /**
     * @ORM\Column(type="string", length=400, nullable=true)
     * @Groups ("post:read")
     */
    private $Bio;

    /**
     * @ORM\Column(type="boolean")
     * @Groups ("post:read")
     */
    private $isVerified = false;

    /**
     * @ORM\Column(type="string", length=300, nullable=true)
     */
    private $Reset_Token;

    /**
     * @ORM\OneToMany(targetEntity=inscriptionT::class, mappedBy="user", orphanRemoval=true)
     */
    private $inscriptionTs;

    /**
     * @ORM\OneToOne(targetEntity=Coach::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $coach;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="user")
     */
    private $reservations;

    /**
     * @ORM\OneToMany(targetEntity=Like::class, mappedBy="User")
     */
    private $likes;

    public function __construct()
    {
        $this->inscriptionTs = new ArrayCollection();
        $this->reservations = new ArrayCollection();
        $this->likes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(int $points): self
    {
        $this->points = $points;

        return $this;
    }

    public function getBio(): ?string
    {
        return $this->Bio;
    }

    public function setBio(?string $Bio): self
    {
        $this->Bio = $Bio;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->Reset_Token;
    }

    public function setResetToken( $Reset_Token): self
    {
        $this->Reset_Token = $Reset_Token;

        return $this;
    }

    /**
     * @return Collection|InscriptionT[]
     */
    public function getInscriptionTs(): Collection
    {
        return $this->inscriptionTs;
    }

    public function addInscriptionT(InscriptionT $inscriptionT): self
    {
        if (!$this->inscriptionTs->contains($inscriptionT)) {
            $this->inscriptionTs[] = $inscriptionT;
            $inscriptionT->setUser($this);
        }

        return $this;
    }

    public function removeInscriptionT(InscriptionT $inscriptionT): self
    {
        if ($this->inscriptionTs->removeElement($inscriptionT)) {
            // set the owning side to null (unless already changed)
            if ($inscriptionT->getUser() === $this) {
                $inscriptionT->setUser(null);
            }
        }

        return $this;
    }

    public function getCoach(): ?Coach
    {
        return $this->coach;
    }

    public function setCoach(Coach $coach): self
    {
        // set the owning side of the relation if necessary
        if ($coach->getUser() !== $this) {
            $coach->setUser($this);
        }

        $this->coach = $coach;

        return $this;
    }

    /**
     * @return Collection|Reservation[]
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setUser($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getUser() === $this) {
                $reservation->setUser(null);
            }
        }

        return $this;
    }

    public function __toString():String
    {
        return(string) "Name: ".$this->getUsername()." ID: ".$this->getId();
    }

    /**
     * @return Collection|Like[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setUser($this);
        }

        return $this;
    }

    public function removeLike(Like $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getUser() === $this) {
                $like->setUser(null);
            }
        }

        return $this;
    }

}
