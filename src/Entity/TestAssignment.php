<?php
// src/Entity/TestAssignment.php
namespace App\Entity;

use App\Repository\TestAssignmentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TestAssignmentRepository::class)]
class TestAssignment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: TestTechnique::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?TestTechnique $test = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $assignedTo = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $assignedBy = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $assignedAt = null;
    
    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $dueDate = null;
    
    #[ORM\Column(type: 'boolean')]
    private bool $isCompleted = false;
    
    #[ORM\Column(length: 255)]
    private string $userType = 'candidate'; // 'candidate' or 'employee'

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTest(): ?TestTechnique
    {
        return $this->test;
    }

    public function setTest(?TestTechnique $test): self
    {
        $this->test = $test;
        return $this;
    }

    public function getAssignedTo(): ?User
    {
        return $this->assignedTo;
    }

    public function setAssignedTo(?User $assignedTo): self
    {
        $this->assignedTo = $assignedTo;
        return $this;
    }

    public function getAssignedBy(): ?User
    {
        return $this->assignedBy;
    }

    public function setAssignedBy(?User $assignedBy): self
    {
        $this->assignedBy = $assignedBy;
        return $this;
    }

    public function getAssignedAt(): ?\DateTimeInterface
    {
        return $this->assignedAt;
    }

    public function setAssignedAt(\DateTimeInterface $assignedAt): self
    {
        $this->assignedAt = $assignedAt;
        return $this;
    }
    
    public function getDueDate(): ?\DateTimeInterface
    {
        return $this->dueDate;
    }

    public function setDueDate(?\DateTimeInterface $dueDate): self
    {
        $this->dueDate = $dueDate;
        return $this;
    }
    
    public function getIsCompleted(): bool
    {
        return $this->isCompleted;
    }
    
    public function setIsCompleted(bool $isCompleted): self
    {
        $this->isCompleted = $isCompleted;
        return $this;
    }
    
    public function getUserType(): string
    {
        return $this->userType;
    }
    
    public function setUserType(string $userType): self
    {
        $this->userType = $userType;
        return $this;
    }
}
