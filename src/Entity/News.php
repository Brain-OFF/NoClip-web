<?php

namespace App\Entity;

use App\Repository\NewsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * @ORM\Entity(repositoryClass=NewsRepository::class)
 */
class News
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ("post:read")

     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Titre is required")
     * @Groups ("post:read")

     */
    private $Titre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Text is required")
     * @Groups ("post:read")

     */
    private $Text;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="jeu is required")
     * @Groups ("post:read")

     */
    private $jeu;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Assert\NotBlank(message="Date is required")
     * @Groups ("post:read")

     */
    private $Date;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="idnews")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Categorie is required")
     * @Groups ("post:read")
     */
    private $Categorie;

    /**
     * @ORM\OneToMany(targetEntity=Images::class, mappedBy="news", cascade={"persist"})
     */
    private $image;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $count;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $like_count;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $dislike_count;

    /**
     * @ORM\OneToMany(targetEntity=Like::class, mappedBy="Article")
     */
    private $likes;

    public function __construct()
    {
        $this->image = new ArrayCollection();
        $this->likes = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->Titre;
    }

    public function setTitre(?string $Titre): self
    {
        $this->Titre = $Titre;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->Text;
    }

    public function setText(?string $Text): self
    {
        $this->Text = $Text;

        return $this;
    }

    public function getJeu(): ?string
    {
        return $this->jeu;
    }

    public function setJeu(?string $jeu): self
    {
        $this->jeu = $jeu;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(?\DateTimeInterface $Date): self
    {
        $this->Date = $Date;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->Categorie;
    }

    public function setCategorie(?Categorie $Categorie): self
    {
        $this->Categorie = $Categorie;

        return $this;
    }

    /**
     * @return Collection<int, Images>
     */
    public function getImage(): Collection
    {
        return $this->image;
    }

    public function addImage(Images $image): self
    {
        if (!$this->image->contains($image)) {
            $this->image[] = $image;
            $image->setNews($this);
        }

        return $this;
    }

    public function removeImage(Images $image): self
    {
        if ($this->image->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getNews() === $this) {
                $image->setNews(null);
            }
        }

        return $this;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(?int $count): self
    {
        $this->count = $count;

        return $this;
    }

    public function getLikeCount(): ?int
    {
        return $this->like_count;
    }

    public function setLikeCount(?int $like_count): self
    {
        $this->like_count = $like_count;

        return $this;
    }

    public function getDislikeCount(): ?int
    {
        return $this->dislike_count;
    }

    public function setDislikeCount(?int $dislike_count): self
    {
        $this->dislike_count = $dislike_count;

        return $this;
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
            $like->setArticle($this);
        }

        return $this;
    }

    public function removeLike(Like $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getArticle() === $this) {
                $like->setArticle(null);
            }
        }

        return $this;
    }



}
