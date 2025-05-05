<?php
namespace App\Entity;

use App\Repository\UserTestResultRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserTestResultRepository::class)]
#[ORM\Table(name: 'user_test_result')]
class UserTestResult
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'userTestResults')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'ID_User', nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: TestTechnique::class, inversedBy: 'userTestResults')]
    #[ORM\JoinColumn(name: 'test_id', referencedColumnName: 'id', nullable: false)]
    private ?TestTechnique $test = null;

    #[ORM\Column(type: 'float')]
    #[Assert\Range(min: 0, max: 100, minMessage: 'Le score doit être au moins {{ limit }}', maxMessage: 'Le score ne peut pas dépasser {{ limit }}')]
    private ?float $score = null;

    #[ORM\Column(type: 'datetime')]
    #[Assert\LessThanOrEqual('today', message: 'La date de passage ne peut pas être dans le futur')]
    private ?\DateTimeInterface $datePassed = null;

    // Getters and Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
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

    public function getScore(): ?float
    {
        return $this->score;
    }

    public function setScore(float $score): static
    {
        $this->score = $score;
        return $this;
    }

    public function getDatePassed(): ?\DateTimeInterface
    {
        return $this->datePassed;
    }

    public function setDatePassed(\DateTimeInterface $datePassed): static
    {
        $this->datePassed = $datePassed;
        return $this;
    }
}
