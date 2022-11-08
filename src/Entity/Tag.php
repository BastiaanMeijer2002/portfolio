<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TagRepository::class)]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 25)]
    private ?string $Name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Description = null;

    #[ORM\ManyToMany(targetEntity: PortfolioElement::class, mappedBy: 'tags')]
    private Collection $portfolioElements;

    public function __construct()
    {
        $this->portfolioElements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    /**
     * @return Collection<int, PortfolioElement>
     */
    public function getPortfolioElements(): Collection
    {
        return $this->portfolioElements;
    }

    public function addPortfolioElement(PortfolioElement $portfolioElement): self
    {
        if (!$this->portfolioElements->contains($portfolioElement)) {
            $this->portfolioElements->add($portfolioElement);
            $portfolioElement->addTag($this);
        }

        return $this;
    }

    public function removePortfolioElement(PortfolioElement $portfolioElement): self
    {
        if ($this->portfolioElements->removeElement($portfolioElement)) {
            $portfolioElement->removeTag($this);
        }

        return $this;
    }
}
