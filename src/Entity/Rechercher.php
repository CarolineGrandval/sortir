<?php

namespace App\Entity;

use App\Repository\RechercherRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RechercherRepository::class,readOnly=true)
 */
class Rechercher
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;
    private ?Campus $campus;
    private ?string $motclef = null;
    private ?\DateTimeInterface $dateDebut = null;
    private ?\DateTimeInterface $dateFin = null;
    private bool $organisateur = true;
    private bool $inscrit = true;
    private bool $pasInscrit = true;
    private bool $passees = false;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }
    /**
     * @return Campus|null
     */
    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    /**
     * @param Campus|null $campus
     */
    public function setCampus(?Campus $campus): void
    {
        $this->campus = $campus;
    }

    /**
     * @return string|null
     */
    public function getMotclef(): ?string
    {
        return $this->motclef;
    }

    /**
     * @param string|null $motclef
     */
    public function setMotclef(?string $motclef): void
    {
        $this->motclef = $motclef;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    /**
     * @param \DateTimeInterface|null $dateDebut
     */
    public function setDateDebut(?\DateTimeInterface $dateDebut): void
    {
        $this->dateDebut = $dateDebut;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    /**
     * @param \DateTimeInterface|null $dateFin
     */
    public function setDateFin(?\DateTimeInterface $dateFin): void
    {
        $this->dateFin = $dateFin;
    }

    /**
     * @return bool
     */
    public function isOrganisateur(): bool
    {
        return $this->organisateur;
    }

    /**
     * @param bool $organisateur
     */
    public function setOrganisateur(bool $organisateur): void
    {
        $this->organisateur = $organisateur;
    }

    /**
     * @return bool
     */
    public function isInscrit(): bool
    {
        return $this->inscrit;
    }

    /**
     * @param bool $inscrit
     */
    public function setInscrit(bool $inscrit): void
    {
        $this->inscrit = $inscrit;
    }

    /**
     * @return bool
     */
    public function isPasInscrit(): bool
    {
        return $this->pasInscrit;
    }

    /**
     * @param bool $pasInscrit
     */
    public function setPasInscrit(bool $pasInscrit): void
    {
        $this->pasInscrit = $pasInscrit;
    }

    /**
     * @return bool
     */
    public function isPassees(): bool
    {
        return $this->passees;
    }

    /**
     * @param bool $passees
     */
    public function setPassees(bool $passees): void
    {
        $this->passees = $passees;
    }

}
