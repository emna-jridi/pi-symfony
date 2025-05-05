<?php
// src/Entity/QuestionTechnique.php
namespace App\Entity;

use App\Repository\QuestionTechniqueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionTechniqueRepository::class)]
class QuestionTechnique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $question = null;

    #[ORM\Column]
    private array $options = [];

    #[ORM\Column]
    private ?int $reponseCorrecte = null;

    #[ORM\Column]
    private ?int $categorie = null;

    #[ORM\Column]
    private ?int $difficulte = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): static
    {
        $this->question = $question;
        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setOptions(array $options): static
    {
        $this->options = $options;
        return $this;
    }

    public function getReponseCorrecte(): ?int
    {
        return $this->reponseCorrecte;
    }

    public function setReponseCorrecte(int $reponseCorrecte): static
    {
        $this->reponseCorrecte = $reponseCorrecte;
        return $this;
    }

    public function getCategorie(): ?int
    {
        return $this->categorie;
    }

    public function setCategorie(int $categorie): static
    {
        $this->categorie = $categorie;
        return $this;
    }

    public function getDifficulte(): ?int
    {
        return $this->difficulte;
    }

    public function setDifficulte(int $difficulte): static
    {
        $this->difficulte = $difficulte;
        return $this;
    }
    #[ORM\Column(type: 'integer', nullable: true)]
private ?int $score = null;

public function getScore(): ?int
{
    return $this->score;
}

public function setScore(?int $score): static
{
    $this->score = $score;
    return $this;
}

}