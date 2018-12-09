<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MediasRepository")
 */
class Medias
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
    private $urlMedia;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrlMedia(): ?string
    {
        return $this->urlMedia;
    }

    public function setUrlMedia(string $urlMedia): self
    {
        $this->urlMedia = $urlMedia;

        return $this;
    }
}
