<?php

namespace App\Entity;

use App\Repository\CategorieServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieServiceRepository::class)]
class CategorieService
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\OneToMany(targetEntity: DemandeService::class, mappedBy: 'categorieService')]
    private Collection $demandesServices;

    public function __construct()
    {
        $this->demandesServices = new ArrayCollection();
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

    public function getDemandesServices(): Collection
    {
        return $this->demandesServices;
    }

    public function addDemandesService(DemandeService $demandesService): static
    {
        if (!$this->demandesServices->contains($demandesService)) {
            $this->demandesServices->add($demandesService);
            $demandesService->setCategorieService($this);
        }
        return $this;
    }

    public function removeDemandesService(DemandeService $demandesService): static
    {
        if ($this->demandesServices->removeElement($demandesService)) {
            if ($demandesService->getCategorieService() === $this) {
                $demandesService->setCategorieService(null);
            }
        }
        return $this;
    }
}