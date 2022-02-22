<?php

namespace App\Entity;

use App\Repository\TournoiRepository;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(type="integer")
     */
    private $IdT;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdT(): ?int
    {
        return $this->IdT;
    }

    public function setIdT(int $IdT): self
    {
        $this->IdT = $IdT;

        return $this;
    }
}
