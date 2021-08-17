<?php

namespace App\Entity;

use App\Repository\SortieRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SortieRepository::class)
 */
class Sortie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=250)
     */
    private ?string $nom;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTime $dateHeureDebut;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $duree;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTime $dateLimiteInscription;

    /**
     * @ORM\Column(type="text", length=5000)
     */
    private ?string $infosSortie;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $motifAnnulation;

    /**
     * @ORM\ManyToOne(targetEntity=Etat::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private Etat $etat;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class, inversedBy="sorties")
     * @ORM\JoinColumn(nullable=false)
     */
    private Campus $campus;

    /**
     * @ORM\ManyToMany(targetEntity=User::class)
     */
    private Particpant $participants;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="sortiesOrganisees")
     * @ORM\JoinColumn(nullable=false)
     */
    private Participant $organisateur;

    /**
     * @ORM\Column(type="integer")
     */
    private ?integer $nbParticipantsMax;

    /**
     * Constructeur
     */
    public function __construct()
    {
        $this->participants = new ArrayCollection();
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
     * @param string $nom
     * @return $this
     */
    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDateHeureDebut(): DateTime
    {
        return $this->dateHeureDebut;
    }

    /**
     * @param DateTime $dateHeureDebut
     * @return $this
     */
    public function setDateHeureDebut(DateTime $dateHeureDebut): self
    {
        $this->dateHeureDebut = $dateHeureDebut;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getDuree(): ?int
    {
        return $this->duree;
    }

    /**
     * @param int $duree
     * @return $this
     */
    public function setDuree(int $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDateLimiteInscription(): DateTime
    {
        return $this->dateLimiteInscription;
    }

    /**
     * @param DateTime $dateLimiteInscription
     * @return $this
     */
    public function setDateLimiteInscription(DateTime $dateLimiteInscription): self
    {
        $this->dateLimiteInscription = $dateLimiteInscription;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getInfosSortie(): ?string
    {
        return $this->infosSortie;
    }

    /**
     * @param string|null $infosSortie
     * @return $this
     */
    public function setInfosSortie(?string $infosSortie): self
    {
        $this->infosSortie = $infosSortie;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMotifAnnulation(): ?string
    {
        return $this->motifAnnulation;
    }

    /**
     * @param string|null $motifAnnulation
     * @return $this
     */
    public function setMotifAnnulation(?string $motifAnnulation): self
    {
        $this->motifAnnulation = $motifAnnulation;

        return $this;
    }

    /**
     * @return Etat|null
     */
    public function getEtat(): ?Etat
    {
        return $this->etat;
    }

    /**
     * @param Etat|null $etat
     * @return $this
     */
    public function setEtat(?Etat $etat): self
    {
        $this->etat = $etat;

        return $this;
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
     * @return $this
     */
    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    /**
     * @return Collection|Participant[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    /**
     * @param Participant $participant
     * @return $this
     */
    public function addParticipant(Participant $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
        }

        return $this;
    }

    /**
     * @param Participant $participant
     * @return $this
     */
    public function removeParticipant(Participant $participant): self
    {
        $this->participants->removeElement($participant);

        return $this;
    }

    /**
     * @return Participant|null
     */
    public function getOrganisateur(): ?Participant
    {
        return $this->organisateur;
    }

    /**
     * @param Participant|null $organisateur
     * @return $this
     */
    public function setOrganisateur(?Participant $organisateur): self
    {
        $this->organisateur = $organisateur;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getNbParticipantsMax(): ?int
    {
        return $this->nbParticipantsMax;
    }

    /**
     * @param int|null $nbParticipantsMax
     * @return $this
     */
    public function setNbParticipantsMax(?int $nbParticipantsMax): self
    {
        $this->nbParticipantsMax = $nbParticipantsMax;

        return $this;
    }
}
