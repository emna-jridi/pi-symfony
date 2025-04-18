<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\TestResultRepository;

#[ORM\Entity(repositoryClass: TestResultRepository::class)]
#[ORM\Table(name: 'test_results')]
class TestResult
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

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $employee_id = null;

    public function getEmployee_id(): ?int
    {
        return $this->employee_id;
    }

    public function setEmployee_id(int $employee_id): self
    {
        $this->employee_id = $employee_id;
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
    private ?int $total_questions = null;

    public function getTotal_questions(): ?int
    {
        return $this->total_questions;
    }

    public function setTotal_questions(int $total_questions): self
    {
        $this->total_questions = $total_questions;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $correct_answers = null;

    public function getCorrect_answers(): ?int
    {
        return $this->correct_answers;
    }

    public function setCorrect_answers(int $correct_answers): self
    {
        $this->correct_answers = $correct_answers;
        return $this;
    }

    #[ORM\Column(type: 'decimal', nullable: false)]
    private ?float $score = null;

    public function getScore(): ?float
    {
        return $this->score;
    }

    public function setScore(float $score): self
    {
        $this->score = $score;
        return $this;
    }

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $analysis_notes = null;

    public function getAnalysis_notes(): ?string
    {
        return $this->analysis_notes;
    }

    public function setAnalysis_notes(?string $analysis_notes): self
    {
        $this->analysis_notes = $analysis_notes;
        return $this;
    }

    #[ORM\Column(type: 'datetime', nullable: false)]
    private ?\DateTimeInterface $test_date = null;

    public function getTest_date(): ?\DateTimeInterface
    {
        return $this->test_date;
    }

    public function setTest_date(\DateTimeInterface $test_date): self
    {
        $this->test_date = $test_date;
        return $this;
    }

    public function getEmployeeId(): ?int
    {
        return $this->employee_id;
    }

    public function setEmployeeId(int $employee_id): static
    {
        $this->employee_id = $employee_id;

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

    public function getTotalQuestions(): ?int
    {
        return $this->total_questions;
    }

    public function setTotalQuestions(int $total_questions): static
    {
        $this->total_questions = $total_questions;

        return $this;
    }

    public function getCorrectAnswers(): ?int
    {
        return $this->correct_answers;
    }

    public function setCorrectAnswers(int $correct_answers): static
    {
        $this->correct_answers = $correct_answers;

        return $this;
    }

    public function getAnalysisNotes(): ?string
    {
        return $this->analysis_notes;
    }

    public function setAnalysisNotes(?string $analysis_notes): static
    {
        $this->analysis_notes = $analysis_notes;

        return $this;
    }

    public function getTestDate(): ?\DateTimeInterface
    {
        return $this->test_date;
    }

    public function setTestDate(\DateTimeInterface $test_date): static
    {
        $this->test_date = $test_date;

        return $this;
    }

}
