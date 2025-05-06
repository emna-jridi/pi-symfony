<?php
namespace App\Entity;

use App\Repository\TestCandidatRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TestCandidatRepository::class)]
class TestCandidat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?TestTechnique $test = null;

    #[ORM\Column(length: 255)]
    private ?string $nomCandidat = null;

    #[ORM\Column(length: 255)]
    private ?string $emailCandidat = null;

    #[ORM\Column]
    private array $reponses = [];

    #[ORM\Column]
    private ?int $score = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $datePassage = null;

    #[ORM\Column]
    private ?bool $termine = false;

    public function __construct()
    {
        $this->datePassage = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTest(): ?TestTechnique
    {
        return $this->test;
    }

    public function setTest(?TestTechnique $test): static
    {
        $this->test = $test;
        return $this;
    }

    public function getNomCandidat(): ?string
    {
        return $this->nomCandidat;
    }

    public function setNomCandidat(string $nomCandidat): static
    {
        $this->nomCandidat = $nomCandidat;
        return $this;
    }

    public function getEmailCandidat(): ?string
    {
        return $this->emailCandidat;
    }

    public function setEmailCandidat(string $emailCandidat): static
    {
        $this->emailCandidat = $emailCandidat;
        return $this;
    }

    public function getReponses(): array
    {
        return $this->reponses;
    }

    public function setReponses(array $reponses): static
    {
        $this->reponses = $reponses;
        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): static
    {
        $this->score = $score;
        return $this;
    }

    public function getDatePassage(): ?\DateTimeImmutable
    {
        return $this->datePassage;
    }

    public function setDatePassage(\DateTimeInterface $datePassage): self
{
    $this->datePassage = $datePassage;
    return $this;
}
    public function isTermine(): ?bool
    {
        return $this->termine;
    }
    private ?\DateTimeInterface $dateSoumission = null;

    public function getDateSoumission(): ?\DateTimeInterface
{
    return $this->dateSoumission;
}

public function setDateSoumission(\DateTimeInterface $dateSoumission): self
{
    $this->dateSoumission = $dateSoumission;
    return $this;
}

    public function setTermine(bool $termine): static
    {
        $this->termine = $termine;
        return $this;
    }
}