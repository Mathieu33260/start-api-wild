<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\DTO\User\UserGetInput;
use App\DTO\User\UserGetOutput;
use App\DTO\User\UserPostInput;
use App\DTO\User\UserPostOutput;
use App\DTO\User\UserListOutput;
use App\DTO\User\UserPutInput;
use App\DTO\User\UserPutOutput;

#[ApiResource(
    collectionOperations: [
        'post' => [
            'method' => 'POST',
            'input' => UserPostInput::class,
            'output' => UserPostOutput::class
        ],
        'get' => [
            'method' => 'GET',
            'output' => UserListOutput::class,
            'security' => "is_granted('ROLE_SUPER_ADMIN')",
        ]
    ],
    itemOperations: [
        'get' => [
            'method' => 'GET',
            'input' => UserGetInput::class,
            'output' => UserGetOutput::class,
            'security' => "is_granted('ROLE_SUPER_ADMIN') or object == user",
        ],
        'put' => [
            'method' => 'PUT',
            'input' => UserPutInput::class,
            'output' => UserPutOutput::class,
            'security' => "is_granted('ROLE_SUPER_ADMIN') or object == user",
        ],
        'delete' => [
            'method' => 'DELETE',
            'security' => "is_granted('ROLE_SUPER_ADMIN')",
        ],
    ],
)]
#[ApiFilter(
    SearchFilter::class,
    properties: [
        'email' => 'exact',
    ]
)]
/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private string $email;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private string $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $lastName;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
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
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getUsername(): string
    {
        return (string) $this->email;
    }

    public function getDisplayName(): string
    {
        return $this->firstName . ' ' . strtoupper($this->lastName[0]) . '.';
    }
}
