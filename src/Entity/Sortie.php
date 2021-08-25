<?php

namespace App\Entity;

use App\Repository\SortieRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     *
     * @Assert\NotBlank(message="Veuillez donner un nom à votre sortie")
     * @Assert\Length(min=2, minMessage="Le nom doit contenir au minimum {{ limit }} caractères", max=250, maxMessage="Le nom doit contenir au maximum {{ limit }} caractères")
     */
    private ?string $nom;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank
     * @Assert\GreaterThan("today", message="La date de début doit être dans le futur !")
     */
    private ?DateTimeInterface $dateHeureDebut = null;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     * @Assert\GreaterThanOrEqual(1, message="La sortie doit durer au moins une heure !")
     * @Assert\LessThanOrEqual(24, message="La sortie doit durer au maximum 24 heures !")
     */
    private ?int $duree;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank
     * @Assert\LessThanOrEqual(propertyPath="dateHeureDebut", message="La date de fin des inscriptions doit être avant la date de début de sortie")
     */
    private ?DateTimeInterface $dateLimiteInscription = null;

    /**
     * @ORM\Column(type="text", length=5000)
     *
     * @Assert\NotBlank(message="Veuillez donner une description de la sortie ")
     * @Assert\Length(min=2, minMessage="La description doit contenir au minimum {{ limit }} caractères", max=5000, maxMessage="La description doit contenir au maximum {{ limit }} caractères")
     */
    private ?string $infosSortie;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $motifAnnulation;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Veuillez indiquer un nombre maximal de participants à la sortie ")
     */
    private ?int $nbParticipantsMax;

    /**
     * @ORM\ManyToOne(targetEntity=Etat::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Etat $etat;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class, inversedBy="sorties")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Veuillez indiquer un campus")
     */
    private ?Campus $campus;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="sortiesOrganisees")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $organisateur;

    /**
     * @ORM\ManyToOne(targetEntity=Lieu::class, inversedBy="sorties")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Lieu $lieu;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="sorties", fetch="EAGER")
     */
    private $participants;

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
     * @return DateTimeInterface|null
     */
    public function getDateHeureDebut(): ?DateTimeInterface
    {
        return $this->dateHeureDebut;
    }

    /**
     * @param DateTimeInterface|null $dateHeureDebut
     */
    public function setDateHeureDebut(?DateTimeInterface $dateHeureDebut): void
    {
        $this->dateHeureDebut = $dateHeureDebut;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getDateLimiteInscription(): ?DateTimeInterface
    {
        return $this->dateLimiteInscription;
    }

    /**
     * @param DateTimeInterface|null $dateLimiteInscription
     */
    public function setDateLimiteInscription(?DateTimeInterface $dateLimiteInscription): void
    {
        $this->dateLimiteInscription = $dateLimiteInscription;
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
     * @return User|null
     */
    public function getOrganisateur(): ?User
    {
        return $this->organisateur;
    }

    /**
     * @param User|null $organisateur
     * @return $this
     */
    public function setOrganisateur(?User $organisateur): self
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

    public function getLieu(): ?Lieu
    {
        return $this->lieu;
    }

    public function setLieu(?Lieu $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(User $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
        }

        return $this;
    }

    public function removeParticipant(User $participant): self
    {
        $this->participants->removeElement($participant);

        return $this;
    }

    public function isInscrit(User $user): bool
    {
        if ($this->getParticipants()->contains($user)) {
            return true;
        } else {
            return false;
        }
    }
}
