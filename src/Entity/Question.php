<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuestionRepository::class)
 */
class Question
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $reponse;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $urlImage;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $urlAudio;

    /**
     * @ORM\ManyToMany(targetEntity=Liste::class, inversedBy="questions")
     */
    private $idListe;

    public function __construct()
    {
        $this->idListe = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReponse(): ?string
    {
        return $this->reponse;
    }

    public function setReponse(string $reponse): self
    {
        $this->reponse = $reponse;

        return $this;
    }

    public function getUrlImage(): ?string
    {
        return $this->urlImage;
    }

    public function setUrlImage(?string $urlImage): self
    {
        $this->urlImage = $urlImage;

        return $this;
    }

    public function getUrlAudio(): ?string
    {
        return $this->urlAudio;
    }

    public function setUrlAudio(string $urlAudio): self
    {
        $this->urlAudio = $urlAudio;

        return $this;
    }

    /**
     * @return Collection|Liste[]
     */
    public function getIdListe(): Collection
    {
        return $this->idListe;
    }

    public function addIdListe(Liste $idListe): self
    {
        if (!$this->idListe->contains($idListe)) {
            $this->idListe[] = $idListe;
        }

        return $this;
    }

    public function removeIdListe(Liste $idListe): self
    {
        $this->idListe->removeElement($idListe);

        return $this;
    }
}
