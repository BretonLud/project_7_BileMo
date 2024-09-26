<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[UniqueEntity('name')]
#[ApiResource]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[
        Assert\NotBlank,
        Assert\Type(['type' => 'string']),
        Assert\Length([
            'max' => 255,
            'maxMessage' => 'Product name is too long.',
            'min' => 1,
            'minMessage' => 'Product name is too short.',
        ])
    ]
    private ?string $name = null;
    
    #[ORM\Column(length: 255)]
    #[
        Assert\NotBlank,
        Assert\Type(['type' => 'string']),
        Assert\Length([
            'max' => 255,
            'maxMessage' => 'Product brand is too long.',
            'min' => 1,
            'minMessage' => 'Product brand is too short.',
        ])
    ]
    private ?string $brand = null;

    #[ORM\Column(length: 255)]
    #[
        Assert\NotBlank,
        Assert\Type(['type' => 'string']),
        Assert\Length([
            'max' => 255,
            'maxMessage' => 'Product color is too long.',
            'min' => 1,
            'minMessage' => 'Product color is too short.',
        ])
    ]
    private ?string $color = null;

    #[ORM\Column]
    #[
        Assert\NotBlank,
        Assert\Type(
            type: 'float',
            message: 'The value {{ value }} is not a valid {{ type }}.',
        ),
    ]
    private ?float $price = null;
    
    #[ORM\Column(length: 255)]
    #[
        Assert\NotBlank,
        Assert\Type(['type' => 'string']),
        Assert\Length([
            'max' => 255,
            'maxMessage' => 'Product screen size is too long.',
            'min' => 1,
            'minMessage' => 'Product screen size is too short.',
        ])
    ]
    private ?string $screenSize = null;

    #[ORM\Column(length: 10000, nullable: true)]
    #[
        Assert\Type(['type' => ['string',null]]),
        Assert\Length([
            'max' => 10000,
            'maxMessage' => 'Product description is too long.',
        ])
    ]
    private ?string $description = null;

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

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getScreenSize(): ?string
    {
        return $this->screenSize;
    }

    public function setScreenSize(string $screenSize): static
    {
        $this->screenSize = $screenSize;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
