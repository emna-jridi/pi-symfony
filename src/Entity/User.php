<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\TestTechnique; 
use App\Repository\UserRepository;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'ID_User', type: 'integer')]
    private ?int $idUser = null;

    #[ORM\Column(name: 'NomUser', length: 100)]
    private ?string $nomUser = null;

    #[ORM\Column(name: 'PrenomUser', length: 100)]
    private ?string $prenomUser = null;

    #[ORM\Column(name: 'DateNaissanceUser', type: 'date')]
    private ?\DateTimeInterface $dateNaissanceUser = null;

    #[ORM\Column(name: 'AdresseUser', length: 100)]
    private ?string $adresseUser = null;

    #[ORM\Column(name: 'TelephoneUser', type: 'float')]
    private ?float $telephoneUser = null;

    #[ORM\Column(name: 'EmailUser', length: 100, unique: true)]
    private ?string $emailUser = null;

    #[ORM\Column(name: 'role', length: 20)]
    private ?string $role = null;

    #[ORM\Column(name: 'Password', length: 100)]
    private ?string $password = null;

    #[ORM\Column(name: 'isActive', type: 'boolean')]
    private ?bool $isActive = true;

    #[ORM\Column(name: 'reset_code', length: 6, nullable: true)]
    private ?string $resetCode = null;

    // Many-to-many relationship with Test
    #[ORM\ManyToMany(targetEntity: TestTechnique::class)]
#[ORM\JoinTable(
    name: 'user_test',
    joinColumns: [new ORM\JoinColumn(name: 'user_id', referencedColumnName: 'ID_User')],
    inverseJoinColumns: [new ORM\JoinColumn(name: 'test_id', referencedColumnName: 'id')]
)]    private Collection $tests;

    public function __construct()
    {
        $this->tests = new ArrayCollection();
    }

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function getNomUser(): ?string
    {
        return $this->nomUser;
    }

    public function setNomUser(string $nomUser): static
    {
        $this->nomUser = $nomUser;
        return $this;
    }

    public function getPrenomUser(): ?string
    {
        return $this->prenomUser;
    }

    public function setPrenomUser(string $prenomUser): static
    {
        $this->prenomUser = $prenomUser;
        return $this;
    }

    public function getDateNaissanceUser(): ?\DateTimeInterface
    {
        return $this->dateNaissanceUser;
    }

    public function setDateNaissanceUser(\DateTimeInterface $dateNaissanceUser): static
    {
        $this->dateNaissanceUser = $dateNaissanceUser;
        return $this;
    }

    public function getAdresseUser(): ?string
    {
        return $this->adresseUser;
    }

    public function setAdresseUser(string $adresseUser): static
    {
        $this->adresseUser = $adresseUser;
        return $this;
    }

    public function getTelephoneUser(): ?float
    {
        return $this->telephoneUser;
    }

    public function setTelephoneUser(float $telephoneUser): static
    {
        $this->telephoneUser = $telephoneUser;
        return $this;
    }

    public function getEmailUser(): ?string
    {
        return $this->emailUser;
    }

    public function setEmailUser(string $emailUser): static
    {
        $this->emailUser = $emailUser;
        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getResetCode(): ?string
    {
        return $this->resetCode;
    }

    public function setResetCode(?string $resetCode): static
    {
        $this->resetCode = $resetCode;
        return $this;
    }

    public function getRoles(): array
    {
        $roles = ['ROLE_USER'];
        
        if ($this->role) {
            $rolePrefix = 'ROLE_';
            $userRole = $this->role;
            
            // Si le rôle n'a pas déjà le préfixe ROLE_, l'ajouter
            if (!str_starts_with($userRole, $rolePrefix)) {
                $userRole = $rolePrefix . $userRole;
            }
            
            $roles[] = $userRole;
        }
        
        return array_unique($roles);
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->emailUser;
    }

    // Getter and setter for tests (ManyToMany)
    public function getTests(): Collection
    {
        return $this->tests;
    }

    public function addTest(TestTechnique $test): self
    {
        if (!$this->tests->contains($test)) {
            $this->tests[] = $test;
        }

        return $this;
    }

    public function removeTest(TestTechnique $test): self
    {
        $this->tests->removeElement($test);

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }
}
