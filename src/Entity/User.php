<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\UserRepository;
use App\State\UserCollectionProvider;
use App\State\UserPasswordHasher;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity('email')]
#[ApiResource(
    operations: [
        new GetCollection(provider: UserCollectionProvider::class),
        new Post(security: "is_granted('USER_POST', object)", validationContext: ['groups' => 'user:write'], processor: UserPasswordHasher::class),
        new Get(security: "is_granted('USER_ACCESS', object)"),
        new Put(security: "is_granted('USER_ACCESS', object)", processor: UserPasswordHasher::class),
        new Patch(security: "is_granted('USER_ACCESS', object)", processor: UserPasswordHasher::class),
        new Delete(security: "is_granted('USER_ACCESS', object)"),
    ],
    normalizationContext: ['groups' => ['user:read']],
    denormalizationContext: ['groups' => ['user:write']],
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[
        Assert\NotBlank,
        Assert\Email,
        Assert\Length(min: 1, max: 255),
    ]
    #[Groups(['user:write', 'user:read', 'customer:read'])]
    #[ApiProperty(
        example: 'user@example.com',
    )]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    #[Groups(['user:read','user:write', 'customer:read'])]
    private array $roles = ['ROLE_USER'];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[
        Assert\NotBlank,
        Assert\Length(min: 1, max: 255),
        Assert\PasswordStrength(minScore: Assert\PasswordStrength::STRENGTH_MEDIUM)
    ]
    #[Groups(['user:write'])]
    #[ApiProperty(
        example: 'Example.1234'
    )]
    private string $password;

    #[ORM\Column(length: 255)]
    #[
        Assert\NotBlank,
        Assert\Length(min: 1, max: 255),
        Assert\Type('string')
    ]
    #[Groups(['user:write', 'user:read', 'customer:read'])]
    #[ApiProperty(
        example: 'John'
    )]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    #[
        Assert\NotBlank,
        Assert\Length(min: 1, max: 255),
        Assert\Type('string')
    ]
    #[Groups(['user:write', 'user:read', 'customer:read'])]
    #[ApiProperty(
        example: 'Doe'
    )]
    private ?string $lastname = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['user:write', 'user:read'])]
    #[ApiFilter(SearchFilter::class, properties: ['customer.id'])]
    #[ApiProperty(
        example: 'api/customers/{id}'
    )]
    private ?Customer $customer = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
    }
    
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }
    
    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): static
    {
        $this->customer = $customer;

        return $this;
    }
}
