<?php

namespace App\Entity;

use App\Repository\ActionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActionRepository::class)]
class Action
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $nombreRessources = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\ManyToOne(inversedBy: 'user')]
    private ?ressource $ressource = null;

    #[ORM\ManyToOne(inversedBy: 'actions')]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombreRessources(): ?float
    {
        return $this->nombreRessources;
    }

    public function setNombreRessources(float $nombreRessources): static
    {
        $this->nombreRessources = $nombreRessources;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getRessource(): ?ressource
    {
        return $this->ressource;
    }

    public function setRessource(?ressource $ressource): static
    {
        $this->ressource = $ressource;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $user): static
    {
        $this->User = $user;

        return $this;
    }
}
