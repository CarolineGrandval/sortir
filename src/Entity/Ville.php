<?php

namespace App\Entity;

use App\Repository\VilleRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VilleRepository::class)
 */
class Ville
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $nom;

    /**
     * @ORM\Column(type="integer")
     */
    private int $codePostal;

    //relation bidirectionnelle pour pouvoir choisir les lieux à partir de la ville (page créer une sortie)
    /**
     * @ORM\OneToMany(targetEntity=Lieu::class, mappedBy="ville")
     */
    private Collection $lieux;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * @param string|null $nom
     * @return $this
     */
    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getCodePostal(): ?int
    {
        return $this->codePostal;
    }

    /**
     * @param int|null $codePostal
     * @return $this
     */
    public function setCodePostal(?int $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getLieux(): Collection
    {
        return $this->lieux;
    }

    /**
     * @param Collection $lieux
     */
    public function setLieux(Collection $lieux): void
    {
        $this->lieux = $lieux;
    }


}
