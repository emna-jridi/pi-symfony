<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\ResultspRepository;

#[ORM\Entity(repositoryClass: ResultspRepository::class)]
#[ORM\Table(name: 'resultsp')]
class Resultsp
{
    #[ORM\ManyToOne(targetEntity: Testp::class, inversedBy: 'resultsps')]
    #[ORM\JoinColumn(name: 'id', referencedColumnName: 'id')]
    private ?Testp $testp = null;

    public function getTestp(): ?Testp
    {
        return $this->testp;
    }

    public function setTestp(?Testp $testp): self
    {
        $this->testp = $testp;
        return $this;
    }



    #[ORM\Id]  // Marque cette propriété comme clé primaire
    #[ORM\GeneratedValue]  // Doctrine génère la valeur de l'id automatiquement
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    // Les autres propriétés de l'entité...

    // Getter et Setter pour la clé primaire `id`
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
    private ?int $test_id = null;

    public function getTest_id(): ?int
    {
        return $this->test_id;
    }

    public function setTest_id(int $test_id): self
    {
        $this->test_id = $test_id;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $employee_id = null;

    public function getEmployee_id(): ?string
    {
        return $this->employee_id;
    }

    public function setEmployee_id(string $employee_id): self
    {
        $this->employee_id = $employee_id;
        return $this;
    }

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $response_json = null;

    public function getResponse_json(): ?string
    {
        return $this->response_json;
    }

    public function setResponse_json(?string $response_json): self
    {
        $this->response_json = $response_json;
        return $this;
    }

    #[ORM\Column(type: 'datetime', nullable: false)]
    private ?\DateTimeInterface $submitted_at = null;

    public function getSubmitted_at(): ?\DateTimeInterface
    {
        return $this->submitted_at;
    }

    public function setSubmitted_at(\DateTimeInterface $submitted_at): self
    {
        $this->submitted_at = $submitted_at;
        return $this;
    }

    public function getTestId(): ?int
    {
        return $this->test_id;
    }

    public function setTestId(int $test_id): static
    {
        $this->test_id = $test_id;

        return $this;
    }

    public function getEmployeeId(): ?string
    {
        return $this->employee_id;
    }

    public function setEmployeeId(string $employee_id): static
    {
        $this->employee_id = $employee_id;

        return $this;
    }

    public function getResponseJson(): ?string
    {
        return $this->response_json;
    }

    public function setResponseJson(?string $response_json): static
    {
        $this->response_json = $response_json;

        return $this;
    }

    public function getSubmittedAt(): ?\DateTimeInterface
    {
        return $this->submitted_at;
    }

    public function setSubmittedAt(\DateTimeInterface $submitted_at): static
    {
        $this->submitted_at = $submitted_at;

        return $this;
    }

}
