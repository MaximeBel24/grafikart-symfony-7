<?php

namespace App\Entity;

use App\Validator\BanWord;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CategoryRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[UniqueEntity('name')]
#[UniqueEntity('slug')]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 4, groups: ['Extra'])]
    #[BanWord(groups: ['Extra'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 4)]
    #[Assert\Regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', message: 'Ceci n\'est pas un slug valide.')]
    #[BanWord()]
    private ?string $slug = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }
}
