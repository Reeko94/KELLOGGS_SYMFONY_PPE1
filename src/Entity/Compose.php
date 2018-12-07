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
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private $id_facture;

    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
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
     * @return mixed
     */
    public function getIdArticle()
    {
        return $this->id_article;
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
