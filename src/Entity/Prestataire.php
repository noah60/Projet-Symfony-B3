<?php

namespace App\Entity;

use App\Repository\PrestataireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PrestataireRepository::class)]
class Prestataire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $competences = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $tarifHoraire = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $biographie = null;

    #[ORM\Column(length: 50)]
    private ?string $statutdisponible = null;

    #[ORM\Column]
    private ?int $nombreavis = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 3, scale: 2)]
    private ?string $note = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'prestataires')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $utilisateur = null;

    #[ORM\ManyToMany(targetEntity: Client::class, inversedBy: 'prestataires')]
    #[ORM\JoinTable(name: 'collaborer')]
    private Collection $clients;

    #[ORM\ManyToMany(targetEntity: DemandeService::class, inversedBy: 'prestataires')]
    #[ORM\JoinTable(name: 'demande')]
    private Collection $demandesServices;

    public function __construct()
    {
        $this->clients = new ArrayCollection();
        $this->demandesServices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompetences(): ?string
    {
        return $this->competences;
    }

    public function setCompetences(string $competences): static
    {
        $this->competences = $competences;
        return $this;
    }

    public function getTarifHoraire(): ?string
    {
        return $this->tarifHoraire;
    }

    public function setTarifHoraire(string $tarifHoraire): static
    {
        $this->tarifHoraire = $tarifHoraire;
        return $this;
    }

    public function getBiographie(): ?string
    {
        return $this->biographie;
    }

    public function setBiographie(string $biographie): static
    {
        $this->biographie = $biographie;
        return $this;
    }

    public function getStatutdisponible(): ?string
    {
        return $this->statutdisponible;
    }

    public function setStatutdisponible(string $statutdisponible): static
    {
        $this->statutdisponible = $statutdisponible;
        return $this;
    }

    public function getNombreavis(): ?int
    {
        return $this->nombreavis;
    }

    public function setNombreavis(int $nombreavis): static
    {
        $this->nombreavis = $nombreavis;
        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(string $note): static
    {
        $this->note = $note;
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

    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(Client $client): static
    {
        if (!$this->clients->contains($client)) {
            $this->clients->add($client);
        }
        return $this;
    }

    public function removeClient(Client $client): static
    {
        $this->clients->removeElement($client);
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
        }
        return $this;
    }

    public function removeDemandesService(DemandeService $demandesService): static
    {
        $this->demandesServices->removeElement($demandesService);
        return $this;
    }
}