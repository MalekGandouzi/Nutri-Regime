<?php

namespace App\Entity;

use App\Repository\PlatRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlatRepository::class)]
class Plat
{
    #[ORM\Id, ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $nomPlat = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?string $cout = null;

    #[ORM\Column(type: 'integer')]
    private ?int $nbrCalories = null;

    #[ORM\Column(type: 'text')]
    private ?string $ingredients = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\ManyToOne(targetEntity: Regime::class, inversedBy: 'plats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Regime $regime = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomPlat(): ?string
    {
        return $this->nomPlat;
    }

    public function setNomPlat(string $nomPlat): self
    {
        $this->nomPlat = $nomPlat;
        return $this;
    }

    public function getCout(): ?string
    {
        return $this->cout;
    }

    public function setCout(string $cout): self
    {
        $this->cout = $cout;
        return $this;
    }

    public function getNbrCalories(): ?int
    {
        return $this->nbrCalories;
    }

    public function setNbrCalories(int $nbrCalories): self
    {
        $this->nbrCalories = $nbrCalories;
        return $this;
    }

    public function getIngredients(): ?string
    {
        return $this->ingredients;
    }

    public function setIngredients(string $ingredients): self
    {
        $this->ingredients = $ingredients;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;
        return $this;
    }

    public function getRegime(): ?Regime
    {
        return $this->regime;
    }

    public function setRegime(?Regime $regime): self
    {
        $this->regime = $regime;
        return $this;
    }
}
