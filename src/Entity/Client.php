<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClientRepository")
 */
class Client extends Utilisateur
{
    /**
     * @ORM\Column(type="date")
     */
    private $date_inscription;

    /**
     * @ORM\Column(type="date")
     */
    private $date_naissance;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Factures", mappedBy="client")
     */
    private $factures;

    public function __construct()
    {
        parent::__construct();
        $this->factures = new ArrayCollection();
    }

    public function getDateInscription(): ?\DateTimeInterface
    {
        return $this->date_inscription;
    }

    public function setDateInscription(\DateTime $date_inscription): self
    {
        $this->date_inscription = $date_inscription;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->date_naissance;
    }

    /**
     * @param mixed $date_naissance
     */
    public function setDateNaissance(\DateTimeInterface $date_naissance): self
    {
        $this->date_naissance = $date_naissance;

        return $this;
    }

    /**
     * @return Collection|Factures[]
     */
    public function getFactures(): Collection
    {
        return $this->factures;
    }

    public function addFacture(Factures $facture): self
    {
        if (!$this->factures->contains($facture)) {
            $this->factures[] = $facture;
            $facture->setClient($this);
        }

        return $this;
    }

    public function removeFacture(Factures $facture): self
    {
        if ($this->factures->contains($facture)) {
            $this->factures->removeElement($facture);
            // set the owning side to null (unless already changed)
            if ($facture->getClient() === $this) {
                $facture->setClient(null);
            }
        }

        return $this;
    }


}
