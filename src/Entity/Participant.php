<?php
//
//namespace App\Entity;
//
//use App\Repository\ParticipantRepository;
//use Doctrine\Common\Collections\ArrayCollection;
//use Doctrine\Common\Collections\Collection;
//use Doctrine\ORM\Mapping as ORM;
//
///**
// * @ORM\Entity(repositoryClass=ParticipantRepository::class)
// */
//class Participant
//{
//    /**
//     * @ORM\Id
//     * @ORM\GeneratedValue
//     * @ORM\Column(type="integer")
//     */
//    private $id;
//
//    /**
//     * @ORM\Column(type="string", length=80)
//     */
//    private $nom;
//
//    /**
//     * @ORM\Column(type="string", length=80)
//     */
//    private $prenom;
//
//    /**
//     * @ORM\Column(type="string", length=20)
//     */
//    private $telephone;
//
//    /**
//     * @ORM\Column(type="string", length=100, unique=true)
//     */
//    private $mail;
//
//    /**
//     * @ORM\Column(type="string", length=250)
//     */
//    private $password;
//
//    /**
//     * @ORM\Column(type="boolean")
//     */
//    private $administrateur;
//
//    /**
//     * @ORM\Column(type="boolean")
//     */
//    private $actif;
//
//    /**
//     * @ORM\Column(type="string", length=50, unique=true)
//     */
//    private $pseudo;
//
//    /**
//     * @ORM\OneToMany(targetEntity=Sortie::class, mappedBy="organisateur", orphanRemoval=true)
//     */
//    private $sortiesOrganisees;
//
//    /**
//     * @ORM\ManyToOne(targetEntity=Campus::class)
//     * @ORM\JoinColumn(nullable=false)
//     */
//    private $campus;
//
//    public function __construct()
//    {
//        $this->sortiesOrganisees = new ArrayCollection();
//    }
//
//    public function getId(): ?int
//    {
//        return $this->id;
//    }
//
//    public function getNom(): ?string
//    {
//        return $this->nom;
//    }
//
//    public function setNom(string $nom): self
//    {
//        $this->nom = $nom;
//
//        return $this;
//    }
//
//    public function getPrenom(): ?string
//    {
//        return $this->prenom;
//    }
//
//    public function setPrenom(string $prenom): self
//    {
//        $this->prenom = $prenom;
//
//        return $this;
//    }
//
//    public function getTelephone(): ?string
//    {
//        return $this->telephone;
//    }
//
//    public function setTelephone(string $telephone): self
//    {
//        $this->telephone = $telephone;
//
//        return $this;
//    }
//
//    public function getMail(): ?string
//    {
//        return $this->mail;
//    }
//
//    public function setMail(string $mail): self
//    {
//        $this->mail = $mail;
//
//        return $this;
//    }
//
//    public function getPassword(): ?string
//    {
//        return $this->password;
//    }
//
//    public function setPassword(string $password): self
//    {
//        $this->password = $password;
//
//        return $this;
//    }
//
//    public function getAdministrateur(): ?bool
//    {
//        return $this->administrateur;
//    }
//
//    public function setAdministrateur(bool $administrateur): self
//    {
//        $this->administrateur = $administrateur;
//
//        return $this;
//    }
//
//    public function getActif(): ?bool
//    {
//        return $this->actif;
//    }
//
//    public function setActif(bool $actif): self
//    {
//        $this->actif = $actif;
//
//        return $this;
//    }
//
//    public function getPseudo(): ?string
//    {
//        return $this->pseudo;
//    }
//
//    public function setPseudo(string $pseudo): self
//    {
//        $this->pseudo = $pseudo;
//
//        return $this;
//    }
//
//    /**
//     * @return Collection|Sortie[]
//     */
//    public function getSortiesOrganisees(): Collection
//    {
//        return $this->sortiesOrganisees;
//    }
//
//    public function addSortiesOrganisee(Sortie $sortiesOrganisee): self
//    {
//        if (!$this->sortiesOrganisees->contains($sortiesOrganisee)) {
//            $this->sortiesOrganisees[] = $sortiesOrganisee;
//            $sortiesOrganisee->setOrganisateur($this);
//        }
//
//        return $this;
//    }
//
//    public function removeSortiesOrganisee(Sortie $sortiesOrganisee): self
//    {
//        if ($this->sortiesOrganisees->removeElement($sortiesOrganisee)) {
//            // set the owning side to null (unless already changed)
//            if ($sortiesOrganisee->getOrganisateur() === $this) {
//                $sortiesOrganisee->setOrganisateur(null);
//            }
//        }
//
//        return $this;
//    }
//
//    public function getCampus(): ?Campus
//    {
//        return $this->campus;
//    }
//
//    public function setCampus(?Campus $campus): self
//    {
//        $this->campus = $campus;
//
//        return $this;
//    }
//}
