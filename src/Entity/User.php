<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\UserRepository;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'user')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $ID_User = null;

    public function getID_User(): ?int
    {
        return $this->ID_User;
    }

    public function setID_User(int $ID_User): self
    {
        $this->ID_User = $ID_User;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $NomUser = null;

    public function getNomUser(): ?string
    {
        return $this->NomUser;
    }

    public function setNomUser(string $NomUser): self
    {
        $this->NomUser = $NomUser;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $PrenomUser = null;

    public function getPrenomUser(): ?string
    {
        return $this->PrenomUser;
    }

    public function setPrenomUser(string $PrenomUser): self
    {
        $this->PrenomUser = $PrenomUser;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: false)]
    private ?\DateTimeInterface $DateNaissanceUser = null;

    public function getDateNaissanceUser(): ?\DateTimeInterface
    {
        return $this->DateNaissanceUser;
    }

    public function setDateNaissanceUser(\DateTimeInterface $DateNaissanceUser): self
    {
        $this->DateNaissanceUser = $DateNaissanceUser;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $AdresseUser = null;

    public function getAdresseUser(): ?string
    {
        return $this->AdresseUser;
    }

    public function setAdresseUser(string $AdresseUser): self
    {
        $this->AdresseUser = $AdresseUser;
        return $this;
    }

    #[ORM\Column(type: 'decimal', nullable: false)]
    private ?float $TelephoneUser = null;

    public function getTelephoneUser(): ?float
    {
        return $this->TelephoneUser;
    }

    public function setTelephoneUser(float $TelephoneUser): self
    {
        $this->TelephoneUser = $TelephoneUser;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $EmailUser = null;

    public function getEmailUser(): ?string
    {
        return $this->EmailUser;
    }

    public function setEmailUser(string $EmailUser): self
    {
        $this->EmailUser = $EmailUser;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $role = null;

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $Password = null;

    public function getPassword(): ?string
    {
        return $this->Password;
    }

    public function setPassword(string $Password): self
    {
        $this->Password = $Password;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $isActive = null;

    public function getIsActive(): ?int
    {
        return $this->isActive;
    }

    public function setIsActive(int $isActive): self
    {
        $this->isActive = $isActive;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $reset_code = null;

    public function getReset_code(): ?string
    {
        return $this->reset_code;
    }

    public function setReset_code(?string $reset_code): self
    {
        $this->reset_code = $reset_code;
        return $this;
    }

    #[ORM\OneToMany(targetEntity: TestAssignment::class, mappedBy: 'user')]
    private Collection $testAssignments;

    public function __construct()
    {
        $this->testAssignments = new ArrayCollection();
    }

    /**
     * @return Collection<int, TestAssignment>
     */
    public function getTestAssignments(): Collection
    {
        if (!$this->testAssignments instanceof Collection) {
            $this->testAssignments = new ArrayCollection();
        }
        return $this->testAssignments;
    }

    public function addTestAssignment(TestAssignment $testAssignment): self
    {
        if (!$this->getTestAssignments()->contains($testAssignment)) {
            $this->getTestAssignments()->add($testAssignment);
        }
        return $this;
    }

    public function removeTestAssignment(TestAssignment $testAssignment): self
    {
        $this->getTestAssignments()->removeElement($testAssignment);
        return $this;
    }

    public function getIDUser(): ?int
    {
        return $this->ID_User;
    }

    public function getResetCode(): ?string
    {
        return $this->reset_code;
    }

    public function setResetCode(?string $reset_code): static
    {
        $this->reset_code = $reset_code;

        return $this;
    }

}
