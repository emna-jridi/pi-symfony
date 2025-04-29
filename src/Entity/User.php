<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use App\Repository\UserRepository;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['emailUser'], message: 'Cet email est déjà utilisé')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'ID_User', type: 'integer')]
    private ?int $idUser = null;

    #[ORM\Column(name: 'NomUser', length: 100)]
    #[Assert\NotBlank(message: 'Le nom est obligatoire')]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Le nom doit contenir au moins {{ limit }} caractères',
        maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères'
    )]
    #[Assert\Regex(
        pattern: '/^[A-Za-zÀ-ÿ\s-]+$/',
        message: 'Le nom ne peut contenir que des lettres, espaces et tirets'
    )]
    private ?string $nomUser = null;

    #[ORM\Column(name: 'PrenomUser', length: 100)]
    #[Assert\NotBlank(message: 'Le prénom est obligatoire')]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Le prénom doit contenir au moins {{ limit }} caractères',
        maxMessage: 'Le prénom ne peut pas dépasser {{ limit }} caractères'
    )]
    #[Assert\Regex(
        pattern: '/^[A-Za-zÀ-ÿ\s-]+$/',
        message: 'Le prénom ne peut contenir que des lettres, espaces et tirets'
    )]
    private ?string $prenomUser = null;

    #[ORM\Column(name: 'DateNaissanceUser', type: 'date')]
    #[Assert\NotBlank(message: 'La date de naissance est obligatoire')]
    #[Assert\LessThanOrEqual('today', message: 'La date de naissance doit être antérieure à aujourd\'hui')]
    #[Assert\GreaterThanOrEqual('-100 years', message: 'La date de naissance n\'est pas valide')]
    #[Assert\LessThanOrEqual('-25 years', message: 'L\'utilisateur doit avoir au moins 25 ans')]
    private ?\DateTimeInterface $dateNaissanceUser = null;

    #[ORM\Column(name: 'AdresseUser', length: 100)]
    #[Assert\NotBlank(message: 'L\'adresse est obligatoire')]
    #[Assert\Length(
        min: 5,
        max: 255,
        minMessage: 'L\'adresse doit contenir au moins {{ limit }} caractères',
        maxMessage: 'L\'adresse ne peut pas dépasser {{ limit }} caractères'
    )]
    private ?string $adresseUser = null;

    #[ORM\Column(name: 'TelephoneUser', type: 'float')]
    #[Assert\NotBlank(message: 'Le numéro de téléphone est obligatoire')]
    #[Assert\Regex(
        pattern: '/^[0-9]{8}$/',
        message: 'Le numéro de téléphone doit contenir exactement 8 chiffres'
    )]
    private ?float $telephoneUser = null;

    #[ORM\Column(name: 'EmailUser', length: 100, unique: true)]
    #[Assert\NotBlank(message: 'L\'email est obligatoire')]
    #[Assert\Email(message: 'L\'email "{{ value }}" n\'est pas un email valide')]
    #[Assert\Length(
        max: 100,
        maxMessage: 'L\'email ne peut pas dépasser {{ limit }} caractères'
    )]
    private ?string $emailUser = null;

    #[ORM\Column(name: 'role', length: 20)]
    #[Assert\NotBlank(message: 'Le rôle est obligatoire')]
    #[Assert\Choice(choices: ['ResponsableRH', 'Employe', 'Candidat'], message: 'Veuillez choisir un rôle valide')]
    private ?string $role = null;

    #[ORM\Column(name: 'Password', length: 100)]
    private ?string $password = null;

    #[ORM\Column(name: 'isActive', type: 'boolean')]
    private ?bool $isActive = true;

    #[ORM\Column(name: 'reset_code', length: 6, nullable: true)]
    private ?string $resetCode = null;

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
}
