<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"mail"}, message="Cet email est déjà lié à un compte")
 * @UniqueEntity(fields={"pseudo"}, message="Ce pseudo est déjà pris, veuillez en choisir un autre")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=80)
     * @Assert\NotBlank
     * @Assert\Length(max=80, maxMessage="Le nom doit contenir au maximum {{ limit }} caractères")
     */
    private ?string $nom;

    /**
     * @ORM\Column(type="string", length=80)
     * @Assert\NotBlank
     * @Assert\Length(max=80, maxMessage="Le prénom doit contenir au maximum {{ limit }} caractères")
     */
    private ?string $prenom;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank
     * @Assert\Length(max=20, maxMessage="Le téléphone doit contenir au maximum {{ limit }} caractères")
     * @Assert\Regex(pattern="/^(0|\+33)[1-9]([-. ]?[0-9]{2}){4}$/", message="le format du numéro de téléphone n'est pas valide.")
     */
    private ?string $telephone;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank
     * @Assert\Length(max=180, maxMessage="Le prénom doit contenir au maximum {{ limit }} caractères")
     * @Assert\Email(message="Le mail fourni n'a pas un format valide")
     */
    private ?string $mail;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private ?string $password;

    /**
     * @var string|null The plain password
     *
     * @Assert\NotBlank(message="Le mot de passe est requis !", groups={"register"})
     * @Assert\Length(min=8, max=50, minMessage="Le mot de passe doit contenir au minimum {{ limit }} caractères", maxMessage="Le mot de passe doit contenir au maximum {{ limit }} caractères", groups={"register"})
     */
    private ?string $plainPassword;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $administrateur;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $actif;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     * @Assert\NotBlank
     * @Assert\Length(max=50, maxMessage="Le prénom doit contenir au maximum {{ limit }} caractères")
     */
    private ?string $pseudo;

    /**
     * @ORM\OneToMany(targetEntity=Sortie::class, mappedBy="organisateur", orphanRemoval=true)
     */
    private ?Collection $sortiesOrganisees;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Campus $campus;

    /**
     * @ORM\Column(type="binary", nullable=true)
     */
    private $photo;

    /**
     * User constructor.
     * Par défaut, les users sont actifs et non admin.
     */
    public function __construct()
    {
        $this->sortiesOrganisees = new ArrayCollection();
        $this->actif = true;
        $this->administrateur = false;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom(?string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return mixed
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param mixed $prenom
     */
    public function setPrenom(?string $prenom): void
    {
        $this->prenom = $prenom;
    }

    /**
     * @return mixed
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * @param mixed $telephone
     */
    public function setTelephone(?string $telephone): void
    {
        $this->telephone = $telephone;
    }

    /**
     * @return mixed
     */
    public function getAdministrateur()
    {
        return $this->administrateur;
    }

    /**
     * @param mixed $administrateur
     */
    public function setAdministrateur(?bool $administrateur): void
    {
        $this->administrateur = $administrateur;
    }

    /**
     * @return mixed
     */
    public function getActif()
    {
        return $this->actif;
    }

    /**
     * @param mixed $actif
     */
    public function setActif(?bool $actif): void
    {
        $this->actif = $actif;
    }

    /**
     * @return mixed
     */
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * @param mixed $pseudo
     */
    public function setPseudo(?string $pseudo): void
    {
        $this->pseudo = $pseudo;
    }

    /**
     * @return mixed
     */
    public function getSortiesOrganisees()
    {
        return $this->sortiesOrganisees;
    }

    /**
     * @param mixed $sortiesOrganisees
     */
    public function setSortiesOrganisees($sortiesOrganisees): void
    {
        $this->sortiesOrganisees = $sortiesOrganisees;
    }

    /**
     * @return Campus|null
     */
    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    /**
     * @param mixed $campus
     */
    public function setCampus(?Campus $campus): void
    {
        $this->campus = $campus;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(?string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->mail;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->mail;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function setPhoto($photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * @return string
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string|null $plainPassword
     */
    public function setPlainPassword(?string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }
}

