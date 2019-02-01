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


}
