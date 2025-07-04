<?php

namespace App\Entity;

use App\Repository\TestTechniqueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TestTechniqueRepository::class)]
class TestTechnique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $dureeMinutes = null;

    #[ORM\ManyToMany(targetEntity: QuestionTechnique::class)]
    private Collection $questions;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    // Many-to-many relationship with User
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'tests')]
    private Collection $users;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->users = new ArrayCollection();  // Initialize the users collection
        $this->createdAt = new \DateTimeImmutable();
        $this->testResults = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getDureeMinutes(): ?int
    {
        return $this->dureeMinutes;
    }

    public function setDureeMinutes(int $dureeMinutes): static
    {
        $this->dureeMinutes = $dureeMinutes;
        return $this;
    }

    /**
     * @return Collection<int, QuestionTechnique>
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(QuestionTechnique $question): static
    {
        if (!$this->questions->contains($question)) {
            $this->questions->add($question);
        }

        return $this;
    }

    public function removeQuestion(QuestionTechnique $question): static
    {
        $this->questions->removeElement($question);
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addTest($this); 
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeTest($this); 
        }

        return $this;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
    /** @var bool Runtime-only flag, not persisted */

    private $completed = false;

    /**
     * @var Collection<int, UserTestResult>
     */
    #[ORM\OneToMany(targetEntity: UserTestResult::class, mappedBy: 'test', orphanRemoval: true)]
    private Collection $testResults;

    public function getCompleted(): bool
    {
        return $this->completed;
    }

    public function setCompleted(bool $completed): self
    {
        $this->completed = $completed;

        return $this;
    }

    /**
     * @return Collection<int, UserTestResult>
     */
    public function getTestResults(): Collection
    {
        return $this->testResults;
    }

    public function addTestResult(UserTestResult $testResult): static
    {
        if (!$this->testResults->contains($testResult)) {
            $this->testResults->add($testResult);
            $testResult->setTest($this);
        }

        return $this;
    }

    public function removeTestResult(UserTestResult $testResult): static
    {
        if ($this->testResults->removeElement($testResult)) {
            // set the owning side to null (unless already changed)
            if ($testResult->getTest() === $this) {
                $testResult->setTest(null);
            }
        }

        return $this;
    }
}
