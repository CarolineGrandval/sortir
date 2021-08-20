<?php

namespace App\Entity;

use App\Repository\RechercherRepository;
use DateTime;
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
    private ?DateTime $dateDebut = null;
    private ?DateTime $dateFin = null;
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
     * @return DateTime|null
     */
    public function getDateDebut(): ?DateTime
    {
        return $this->dateDebut;
    }

    /**
     * @param DateTime|null $dateDebut
     */
    public function setDateDebut(?DateTime $dateDebut): void
    {
        $this->dateDebut = $dateDebut;
    }

    /**
     * @return DateTime|null
     */
    public function getDateFin(): ?DateTime
    {
        return $this->dateFin;
    }

    /**
     * @param DateTime|null $dateFin
     */
    public function setDateFin(?DateTime $dateFin): void
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
