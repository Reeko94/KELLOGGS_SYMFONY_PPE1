<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticlesRepository")
 */
class Articles
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;

    /**
     * @ORM\Column(type="boolean")
     */
    private $disponibilite;

    /**
     * @ORM\Column(type="float")
     */
    private $prix;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Fabricants", inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fabricant;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Factures", inversedBy="articles")
     */
    private $factures;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getDisponibilite(): ?bool
    {
        return $this->disponibilite;
    }

    public function setDisponibilite(bool $disponibilite): self
    {
        $this->disponibilite = $disponibilite;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getFabricant(): ?Fabricants
    {
        return $this->fabricant;
    }

    public function setFabricant(?Fabricants $fabricant): self
    {
        $this->fabricant = $fabricant;

        return $this;
    }

    public function getFactures(): ?Factures
    {
        return $this->factures;
    }

    public function setFactures(?Factures $factures): self
    {
        $this->factures = $factures;

        return $this;
    }
}
