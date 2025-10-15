<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(length: 20)]
    private ?string $telephone = null;

    #[ORM\Column(length: 100)]
    private ?string $ville = null;

    #[ORM\Column(length: 10)]
    private ?string $codePostal = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageFilename = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'clients')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $utilisateur = null;

    #[ORM\OneToMany(targetEntity: DemandeService::class, mappedBy: 'client')]
    private Collection $demandesServices;

    #[ORM\ManyToMany(targetEntity: Prestataire::class, mappedBy: 'clients')]
    private Collection $prestataires;

    public function __construct()
    {
        $this->demandesServices = new ArrayCollection();
        $this->prestataires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;
        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;
        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;
        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): static
    {
        $this->codePostal = $codePostal;
        return $this;
    }

    public function getImageFilename(): ?string
    {
        return $this->imageFilename;
    }

    public function setImageFilename(?string $imageFilename): static
    {
        $this->imageFilename = $imageFilename;
        return $this;
    }

    public function getUtilisateur(): ?User
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?User $utilisateur): static
    {
        $this->utilisateur = $utilisateur;
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
            $demandesService->setClient($this);
        }
        return $this;
    }

    public function removeDemandesService(DemandeService $demandesService): static
    {
        if ($this->demandesServices->removeElement($demandesService)) {
            if ($demandesService->getClient() === $this) {
                $demandesService->setClient(null);
            }
        }
        return $this;
    }

    public function getPrestataires(): Collection
    {
        return $this->prestataires;
    }

    public function addPrestataire(Prestataire $prestataire): static
    {
        if (!$this->prestataires->contains($prestataire)) {
            $this->prestataires->add($prestataire);
            $prestataire->addClient($this);
        }
        return $this;
    }

    public function removePrestataire(Prestataire $prestataire): static
    {
        if ($this->prestataires->removeElement($prestataire)) {
            $prestataire->removeClient($this);
        }
        return $this;
    }
}