<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\TestAssignmentRepository;

#[ORM\Entity(repositoryClass: TestAssignmentRepository::class)]
#[ORM\Table(name: 'test_assignments')]
class TestAssignment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'testAssignments')]
    #[ORM\JoinColumn(name: 'employee_id', referencedColumnName: 'ID_User')]
    private ?User $user = null;

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $nomEmployee = null;

    public function getNomEmployee(): ?string
    {
        return $this->nomEmployee;
    }

    public function setNomEmployee(string $nomEmployee): self
    {
        $this->nomEmployee = $nomEmployee;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $test_category = null;

    public function getTest_category(): ?string
    {
        return $this->test_category;
    }

    public function setTest_category(string $test_category): self
    {
        $this->test_category = $test_category;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $assigned_by = null;

    public function getAssigned_by(): ?int
    {
        return $this->assigned_by;
    }

    public function setAssigned_by(int $assigned_by): self
    {
        $this->assigned_by = $assigned_by;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $questions_count = null;

    public function getQuestions_count(): ?int
    {
        return $this->questions_count;
    }

    public function setQuestions_count(int $questions_count): self
    {
        $this->questions_count = $questions_count;
        return $this;
    }

    #[ORM\Column(type: 'datetime', nullable: false)]
    private ?\DateTimeInterface $deadline = null;

    public function getDeadline(): ?\DateTimeInterface
    {
        return $this->deadline;
    }

    public function setDeadline(\DateTimeInterface $deadline): self
    {
        $this->deadline = $deadline;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $status = null;

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $notes = null;

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;
        return $this;
    }

    #[ORM\Column(type: 'datetime', nullable: false)]
    private ?\DateTimeInterface $created_at = null;

    public function getCreated_at(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreated_at(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;
        return $this;
    }

    #[ORM\Column(type: 'datetime', nullable: false)]
    private ?\DateTimeInterface $updated_at = null;

    public function getUpdated_at(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdated_at(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    public function getTestCategory(): ?string
    {
        return $this->test_category;
    }

    public function setTestCategory(string $test_category): static
    {
        $this->test_category = $test_category;

        return $this;
    }

    public function getAssignedBy(): ?int
    {
        return $this->assigned_by;
    }

    public function setAssignedBy(int $assigned_by): static
    {
        $this->assigned_by = $assigned_by;

        return $this;
    }

    public function getQuestionsCount(): ?int
    {
        return $this->questions_count;
    }

    public function setQuestionsCount(int $questions_count): static
    {
        $this->questions_count = $questions_count;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

}
