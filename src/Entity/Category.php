<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $kkk = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKkk(): ?string
    {
        return $this->kkk;
    }

    public function setKkk(string $kkk): static
    {
        $this->kkk = $kkk;

        return $this;
    }
}
