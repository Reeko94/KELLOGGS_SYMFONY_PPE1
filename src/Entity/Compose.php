<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ComposeRepository")
 */
class Compose
{
    /**
     * @ORM\Column(type="integer")
     */
    private $quantite;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id()
     */
    private $id_facture;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id()
     */
    private $id_article;

    /**
     * @return mixed
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * @param mixed $quantite
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;
        return $this;
    }

}
