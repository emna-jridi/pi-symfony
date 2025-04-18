<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\QuestionRepository;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
#[ORM\Table(name: 'questions')]
class Question
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

    #[ORM\Column(type: 'text', nullable: false)]
    private ?string $question = null;

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $correct_answer = null;

    public function getCorrect_answer(): ?string
    {
        return $this->correct_answer;
    }

    public function setCorrect_answer(string $correct_answer): self
    {
        $this->correct_answer = $correct_answer;
        return $this;
    }

    public function getCorrectAnswer(): ?string
    {
        return $this->correct_answer;
    }

    public function setCorrectAnswer(string $correct_answer): static
    {
        $this->correct_answer = $correct_answer;

        return $this;
    }

}
