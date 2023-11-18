<?php

namespace App\Entity;

use App\Repository\RessourceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RessourceRepository::class)]
class Ressource
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups("res")]
    private ?string $Intitule = null;

    #[ORM\Column(length: 255)]
    private ?string $unite = null;

    #[ORM\Column]
    #[Groups("res")]
    private ?float $poid = null;

    #[ORM\ManyToOne(inversedBy: 'ressources')]
    #[ORM\JoinColumn(nullable: false)]
    private ?categorie $categorie = null;

    #[ORM\OneToMany(mappedBy: 'ressource', targetEntity: Action::class)]
    private Collection $action;

    public function __construct()
    {
        $this->User = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIntitule(): ?string
    {
        return $this->Intitule;
    }

    public function setIntitule(string $Intitule): static
    {
        $this->Intitule = $Intitule;

        return $this;
    }

    public function getUnite(): ?string
    {
        return $this->unite;
    }

    public function setUnite(string $unite): static
    {
        $this->unite = $unite;

        return $this;
    }

    public function getPoid(): ?float
    {
        return $this->poid;
    }

    public function setPoid(float $poid): static
    {
        $this->poid = $poid;

        return $this;
    }

    public function getCategorie(): ?categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?categorie $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection<int, Action>
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(Action $action): static
    {
        if (!$this->user->contains($action)) {
            $this->user->add($action);
            $action->setRessource($this);
        }

        return $this;
    }

    public function removeUser(Action $action): static
    {
        if ($this->User->removeElement($action)) {
            // set the owning side to null (unless already changed)
            if ($action->getRessource() === $this) {
                $action->setRessource(null);
            }
        }

        return $this;
    }
}
