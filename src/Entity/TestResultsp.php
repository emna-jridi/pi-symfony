<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\TestResultspRepository;

#[ORM\Entity(repositoryClass: TestResultspRepository::class)]
#[ORM\Table(name: 'test_resultsp')]
class TestResultsp
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

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $test_type = null;

    public function getTest_type(): ?string
    {
        return $this->test_type;
    }

    public function setTest_type(?string $test_type): self
    {
        $this->test_type = $test_type;
        return $this;
    }

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $raw_results = null;

    public function getRaw_results(): ?string
    {
        return $this->raw_results;
    }

    public function setRaw_results(?string $raw_results): self
    {
        $this->raw_results = $raw_results;
        return $this;
    }

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $analysis_result = null;

    public function getAnalysis_result(): ?string
    {
        return $this->analysis_result;
    }

    public function setAnalysis_result(?string $analysis_result): self
    {
        $this->analysis_result = $analysis_result;
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

    public function getTestDate(): ?\DateTimeInterface
    {
        return $this->test_date;
    }

    public function setTestDate(\DateTimeInterface $test_date): static
    {
        $this->test_date = $test_date;

        return $this;
    }

    public function getTestType(): ?string
    {
        return $this->test_type;
    }

    public function setTestType(?string $test_type): static
    {
        $this->test_type = $test_type;

        return $this;
    }

    public function getRawResults(): ?string
    {
        return $this->raw_results;
    }

    public function setRawResults(?string $raw_results): static
    {
        $this->raw_results = $raw_results;

        return $this;
    }

    public function getAnalysisResult(): ?string
    {
        return $this->analysis_result;
    }

    public function setAnalysisResult(?string $analysis_result): static
    {
        $this->analysis_result = $analysis_result;

        return $this;
    }

}
