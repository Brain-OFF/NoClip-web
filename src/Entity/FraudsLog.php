<?php

namespace App\Entity;

use App\Repository\FraudsLogRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FraudsLogRepository::class)
 */
class FraudsLog
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $cdate;

    /**
     * @ORM\OneToOne(targetEntity=User::class, cascade={"persist", "remove"})
     */
    private $user_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCdate(): ?\DateTimeInterface
    {
        return $this->cdate;
    }

    public function setCdate(?\DateTimeInterface $cdate): self
    {
        $this->cdate = $cdate;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }
}
