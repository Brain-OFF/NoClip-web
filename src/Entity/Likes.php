<?php

namespace App\Entity;

use App\Repository\LikesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LikesRepository::class)
 */
class Likes
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $liker;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $liked;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLiker(): ?string
    {
        return $this->liker;
    }

    public function setLiker(?string $liker): self
    {
        $this->liker = $liker;

        return $this;
    }

    public function getLiked(): ?string
    {
        return $this->liked;
    }

    public function setLiked(?string $liked): self
    {
        $this->liked = $liked;

        return $this;
    }
}
