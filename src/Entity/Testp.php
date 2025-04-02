<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\TestpRepository;

#[ORM\Entity(repositoryClass: TestpRepository::class)]
#[ORM\Table(name: 'testp')]
class Testp
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

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $test_id = null;

    public function getTest_id(): ?string
    {
        return $this->test_id;
    }

    public function setTest_id(string $test_id): self
    {
        $this->test_id = $test_id;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $test_title = null;

    public function getTest_title(): ?string
    {
        return $this->test_title;
    }

    public function setTest_title(string $test_title): self
    {
        $this->test_title = $test_title;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $test_link = null;

    public function getTest_link(): ?string
    {
        return $this->test_link;
    }

    public function setTest_link(string $test_link): self
    {
        $this->test_link = $test_link;
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

    #[ORM\OneToMany(targetEntity: Resultsp::class, mappedBy: 'testp')]
    private Collection $resultsps;

    public function __construct()
    {
        $this->resultsps = new ArrayCollection();
    }

    /**
     * @return Collection<int, Resultsp>
     */
    public function getResultsps(): Collection
    {
        if (!$this->resultsps instanceof Collection) {
            $this->resultsps = new ArrayCollection();
        }
        return $this->resultsps;
    }

    public function addResultsp(Resultsp $resultsp): self
    {
        if (!$this->getResultsps()->contains($resultsp)) {
            $this->getResultsps()->add($resultsp);
        }
        return $this;
    }

    public function removeResultsp(Resultsp $resultsp): self
    {
        $this->getResultsps()->removeElement($resultsp);
        return $this;
    }

    public function getTestId(): ?string
    {
        return $this->test_id;
    }

    public function setTestId(string $test_id): static
    {
        $this->test_id = $test_id;

        return $this;
    }

    public function getTestTitle(): ?string
    {
        return $this->test_title;
    }

    public function setTestTitle(string $test_title): static
    {
        $this->test_title = $test_title;

        return $this;
    }

    public function getTestLink(): ?string
    {
        return $this->test_link;
    }

    public function setTestLink(string $test_link): static
    {
        $this->test_link = $test_link;

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

}
