<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TypeNutritionRepository")
 */
class TypeNutrition
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
     * @ORM\OneToMany(targetEntity="App\Entity\Fabricants", mappedBy="idTypeNutrition")
     */
    private $fabricants;

    public function __construct()
    {
        $this->fabricants = new ArrayCollection();
    }

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

    /**
     * @return Collection|Fabricants[]
     */
    public function getFabricants(): Collection
    {
        return $this->fabricants;
    }

    public function addFabricant(Fabricants $fabricant): self
    {
        if (!$this->fabricants->contains($fabricant)) {
            $this->fabricants[] = $fabricant;
            $fabricant->setIdTypeNutrition($this);
        }

        return $this;
    }

    public function removeFabricant(Fabricants $fabricant): self
    {
        if ($this->fabricants->contains($fabricant)) {
            $this->fabricants->removeElement($fabricant);
            // set the owning side to null (unless already changed)
            if ($fabricant->getIdTypeNutrition() === $this) {
                $fabricant->setIdTypeNutrition(null);
            }
        }

        return $this;
    }
}
