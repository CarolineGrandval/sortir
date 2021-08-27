<?php

namespace App\Entity;

use App\Repository\VilleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\NotBlank(message="Le nom de la ville est obligatoire")
     */
    private string $nom;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Le code postal est obligatoire")
     */
    private int $codePostal;

    // Récupération des données "eager" pour accéder direcement aux lieux quand on récupère la ville.
    /**
     * @ORM\OneToMany(targetEntity=Lieu::class, mappedBy="ville", fetch="EAGER")
     */
    private ?Collection $lieux;


    /**
     * Ville constructor.
     */
    public function __construct()
    {
        $this->lieux = new ArrayCollection();
    }

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
     * @return Collection|Lieu[]
     */
    public function getLieux(): ?Collection
    {
        return $this->lieux;
    }

    public function addLieux(Lieu $lieux): self
    {
        if (!$this->lieux->contains($lieux)) {
            $this->lieux[] = $lieux;
            $lieux->setVille($this);
        }

        return $this;
    }

    public function removeLieux(Lieu $lieux): self
    {
        if ($this->lieux->removeElement($lieux)) {
            // set the owning side to null (unless already changed)
            if ($lieux->getVille() === $this) {
                $lieux->setVille(null);
            }
        }

        return $this;
    }


}
